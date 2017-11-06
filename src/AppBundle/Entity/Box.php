<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Box
 *
 * @ORM\Table(name="box")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BoxRepository")
 */
class Box
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;


    /**
     * @var float
     * @ORM\Column(name="budget", type="float")
     */
    private $budget;

    /**
     * @var
     * @ORM\Column(name="validate", type="boolean", nullable=true)
     */
    private $validate = false;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $creator;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BoxProduct", mappedBy="box", cascade={"all"})
     * @ORM\JoinColumn(unique=true)
     */
    private $boxProduct;

    private $products;

    // Important
    public function getProducts()
    {
        $products = new ArrayCollection();

        foreach ($this->boxProduct as $p) {
            $products[] = $p->getProduct();
        }
        return $products;
    }

    // Important

    /**
     * @param $products
     * Replis l'ArrayCollection $boxProduct
     */
    public function setProducts($products)
    {
        $this->boxProduct = new ArrayCollection();
        foreach($products as $product)
        {
            $bp = new BoxProduct();

            $bp->setBox($this);
            $bp->setProduct($product);

            $this->addBoxProduct($bp);
        }
        $this->products = $products;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setState('created');
        $this->setValidate(false);
        $this->boxProduct = new ArrayCollection();
        $this->products = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Box
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Box
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set budget
     *
     * @param float $budget
     *
     * @return Box
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return float
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Add boxProduct
     *
     * @param \AppBundle\Entity\BoxProduct $boxProduct
     *
     * @return Box
     */
    public function addBoxProduct(\AppBundle\Entity\BoxProduct $boxProduct)
    {
        $this->boxProduct[] = $boxProduct;

        return $this;
    }

    /**
     * Remove boxProduct
     *
     * @param \AppBundle\Entity\BoxProduct $boxProduct
     */
    public function removeBoxProduct(\AppBundle\Entity\BoxProduct $boxProduct)
    {
        $this->boxProduct->removeElement($boxProduct);
    }

    /**
     * Get boxProduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoxProduct()
    {
        return $this->boxProduct;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set open
     *
     * @param boolean $validate
     *
     * @return Box
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get open
     *
     * @return boolean
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     * @return Box
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
        return $this;
    }

}
