<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/10/2017
 * Time: 15:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Box;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Workflow;

class BoxManager
{
    private $em;
    private $wf_box;
    private $wf_product;
    private $session;
    private $dispatcher;


    public function __construct(Workflow $wf_box, Workflow $wf_product, ObjectManager $em, SessionInterface $session, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        //Todo: Creer un service pour le workflow box
        $this->wf_box = $wf_box;
        $this->wf_product = $wf_product;
        $this->session = $session;
        $this->dispatcher = $dispatcher;

    }

    /**
     * @param Box $box
     * Insertion d'une box
     */
    public function insert(Box $box){
        $this->em->persist($box);
        $this->em->flush();
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
        $this->em->persist($box);
        $this->em->flush();
        $this->em->remove($box);
        $this->em->flush();
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
        if($this->wf_box->can($box, 'complete'))
        {
            //Verifier que tous les produits
            $boxProducts = $this->getBoxProduct($box);
            foreach($boxProducts as $boxProduct)
            {
                if(!in_array($boxProduct->getState(), array('cancelled', 'ready')))
                {
                    $this->session->getFlashBag()->add('danger', 'Le produit '.$boxProduct->getProduct()->getLabel().' est à l\'état '.$boxProduct->getState());
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
            $this->dispatcher->dispatch('', '');
            $this->session->getFlashBag()->add('success', 'La box a changé d\'état');
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
        if($key)
        {
            return isset($states[$key+1]) ? $states[$key+1] : $state;
        }
        else {
            return false;
        }

    }
}