<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/10/2017
 * Time: 15:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Box;
use AppBundle\Entity\BoxProduct;
use AppBundle\Event\BoxEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Workflow\Workflow;

class BoxManager
{
    private $em;
    private $wf_box;
    private $wf_product;
    private $session;
    private $dispatcher;
    private $translator;


    public function __construct(Workflow $wf_box,
                                Workflow $wf_product,
                                ObjectManager $em,
                                SessionInterface $session,
                                EventDispatcherInterface $dispatcher,
                                TranslatorInterface $translator)
    {
        $this->em = $em;
        //Todo: Creer un service pour le workflow box
        $this->wf_box = $wf_box;
        $this->wf_product = $wf_product;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;

    }

    /**
     * @param Box $box
     * Insertion d'une box
     */
    public function insert(Box $box){

        if($this->isBoxValid($box))
        {
            $this->em->persist($box);
            $this->em->flush();
            return true;
        }
        else {
            $this->session->getFlashBag()->add('danger', 'Le coût total des produits ('.$this->getProductsCost($box).'€) dépasse le bugdet de '.$box->getBudget().'€');
            return false;
        }

    }

    /**
     * @param Box $box
     * Effacement d'un box et remove des des produits associété
     */
    public function delete(Box $box)
    {
        foreach($box->getProducts() as $product)
        {
            $box->removeProduct($product);
        }

        //vide les boxProduct
        $this->em->persist($box);
        $this->em->flush();

        //On supprime la box
        $this->em->remove($box);
        $this->em->flush();
    }

    public function save(Box $box)
    {

        //Suppression des boxProduct deja associés à la box
        $boxProducts = $this->em->getRepository('AppBundle:BoxProduct')->findByBox($box);
        $this->cleanBoxProduct($boxProducts);

        //Si la box est valide, on persist
        if($this->isBoxValid($box))
        {
            $this->em->flush();
            return true;
        }
        else {
            $this->session->getFlashBag()->add('danger', 'Le coût total des produits ('.$this->getProductsCost($box).'€) dépasse le bugdet de '.$box->getBudget().'€');
            return false;
        }
    }

    /**
     * @param Box $box
     * Set la box a un status validate
     */
    public function validate(Box $box)
    {
        if($this->wf_box->can($box, 'request') && $this->toRequest($box))
        {
            $this->applyState($box, 'request');
        }
        else {

        }
    }

    public function changeState(Box $box)
    {
        if($this->wf_box->can($box, 'request'))
        {
            if($this->toRequest($box)) {
                $this->applyState($box, 'request');
            }
            else {
                $this->session->getFlashBag()->add('danger', 'La box n\'a pas de produit');
            }
            return;
        }

        if($this->wf_box->can($box, 'provisionning'))
        {
            $this->applyState($box, 'provisionning');
            return;
        }

        if($this->wf_box->can($box, 'unvailable'))
        {
            //Verifier que tous les produits
            $boxProducts = $this->getBoxProduct($box);
            foreach($boxProducts as $boxProduct)
            {
                if($boxProduct->getState() == 'cancelled')
                {
                    $this->applyState($box, 'unvailable');
                    return;
                }
            }
        }

        if($this->wf_box->can($box, 'complete'))
        {
            //Verifier que tous les produits
            $boxProducts = $this->getBoxProduct($box);
            foreach($boxProducts as $boxProduct)
            {
                if($boxProduct->getState() !== 'ready')
                {
                    $this->session->getFlashBag()->add('danger', 'Le produit '.$boxProduct->getProduct()->getLabel().' est à l\'état '.$this->translator->trans($boxProduct->getState()));
                    return false;
                }
            }
            $this->applyState($box, 'complete');
            return;
        }



            /*
        created
        - requested
        - provisionned
        - completed
        - unvailable
            */
    }

    public function toRequest(Box $box)
    {
        $boxProduct = $this->em->getRepository('AppBundle:BoxProduct')->findByBox($box);
        return count($boxProduct);
    }

    public function getStates()
    {
        return [
            'created',
            'requested',
            'provisionned',
            'completed',
            'unvailable'
        ];
    }

    public function applyState(Box $box, $state)
    {
        try {
            $this->wf_box->apply($box, $state);
            $this->em->persist($box);
            $this->em->flush();
            $this->dispatcher->dispatch(BoxEvent::STATE, new BoxEvent($box));
        }
        catch (LogicException $e)
        {
            foreach($e->getMessage() as $msg)
                $this->session->getFlashBag()->add('danger', $msg);
        }

    }

    /**
     * @param Box $box
     * @return ArrayCollection
     */
    public function getBoxProduct(Box $box)
    {
        return $this->em->getRepository('AppBundle:BoxProduct')->findBoxProducts($box);
    }

    /**
     * @param $state
     * @return bool|mixed
     */
    public function getNextState($state)
    {

        $states = $this->getStates();
        $key = array_search($state, $states);
        if($key !== false)
        {
            return isset($states[$key+1]) ? $states[$key+1] : $state;
        }
        else {
            return false;
        }

    }

    /**
     * @return int
     */
    public function getProductsCost(Box $box)
    {
        dump($box->getBoxProduct());
        $sum = 0;
        foreach($box->getBoxProduct() as $boxProduct)
        {
            $sum += $boxProduct->getProduct()->getPrice();
        }
        return $sum;
    }

    /**
     * @param Box $box
     * @return bool
     */
    public function isBoxValid(Box $box)
    {
        return $this->getProductsCost($box) <= $box->getBudget();
    }

    public function cleanBoxProduct(array $boxProducts)
    {
        foreach($boxProducts as $boxProduct)
        {
            //$box->removeBoxProduct($boxProduct);
            $this->em->remove($boxProduct);
        }
        $this->em->flush();
    }
}