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
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Workflow\Workflow;

class ProductManager
{
    private $em;
    private $wf;

    public function __construct(Workflow $wf, ObjectManager $em)
    {
        $this->em = $em;
        $this->wf = $wf;
    }

    public function changeState(BoxProduct $boxProduct, $state)
    {
        if($this->wf->can($boxProduct, $state))
        {
            $this->wf->apply($boxProduct, $state);
            $this->em->persist($boxProduct);
            $this->em->flush();
        }
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