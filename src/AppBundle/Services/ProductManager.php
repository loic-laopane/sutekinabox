<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/10/2017
 * Time: 15:14
 */

namespace AppBundle\Services;


use Doctrine\Common\Persistence\ObjectManager;

class ProductManager
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }



}