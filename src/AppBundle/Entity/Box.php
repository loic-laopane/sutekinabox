<?php

namespace AppBundle\Entity;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BoxProduct", mappedBy="box")
     */
    private $boxProduct;


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
     * Constructor
     */
    public function __construct()
    {
        $this->setState('created');
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
}
