<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/10/2017
 * Time: 15:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Box;
use Doctrine\Common\Persistence\ObjectManager;

class BoxManager
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function insert(Box $box){
        $this->em->persist($box);
        $this->em->flush();

    }

    public function delete(Box $box)
    {
        foreach($box->getProducts() as $product)
        {
            $box->removeProduct($product);
        }
        $this->em->remove($box);
        //$this->em->flush();
    }
}