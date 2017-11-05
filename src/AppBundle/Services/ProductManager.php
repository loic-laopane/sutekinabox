<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/10/2017
 * Time: 15:14
 */

namespace AppBundle\Services;


use AppBundle\Entity\BoxProduct;
use AppBundle\Entity\Product;
use AppBundle\Event\BoxEvent;
use AppBundle\Event\Listener\BoxProductEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Workflow;

class ProductManager
{
    private $em;
    private $wf;
    private $dispatcher;

    public function __construct(Workflow $wf, ObjectManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->wf = $wf;
        $this->dispatcher = $dispatcher;
    }

    public function changeState(BoxProduct $boxProduct, $state)
    {
        if($this->wf->can($boxProduct, $state))
        {
            $this->apply($boxProduct, $state);
        }
    }

    private function apply(BoxProduct $boxProduct, $state)
    {
        $this->wf->apply($boxProduct, $state);
        $this->em->persist($boxProduct);
        $this->em->flush();

        $this->dispatcher->dispatch(BoxEvent::PRODUCT, new BoxEvent($boxProduct->getBox()));
    }

    public function getStates()
    {
        return [
            'out_of_stock',
            'stock',
            'request',
            'purchase',
            'shipment',
            'receipt',
            'is_ok',
            'is_nok',
            'back_to_supplier',
            'complete',
            'boxing'
        ];
    }

}