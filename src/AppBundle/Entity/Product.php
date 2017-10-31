<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Box")
     */
    private $boxes;


    /**
     * @var array
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Supplier")
     */
    private $suppliers;

    /**
     * @var string
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;


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
     * Set label
     *
     * @param string $label
     *
     * @return Product
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Product
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->boxes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->suppliers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setState('in_stock');
    }

    /**
     * Add box
     *
     * @param \AppBundle\Entity\Box $box
     *
     * @return Product
     */
    public function addBox(\AppBundle\Entity\Box $box)
    {
        $this->boxes[] = $box;

        return $this;
    }

    /**
     * Remove box
     *
     * @param \AppBundle\Entity\Box $box
     */
    public function removeBox(\AppBundle\Entity\Box $box)
    {
        $this->boxes->removeElement($box);
    }

    /**
     * Get boxes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoxes()
    {
        return $this->boxes;
    }

    /**
     * Add supplier
     *
     * @param \AppBundle\Entity\Supplier $supplier
     *
     * @return Product
     */
    public function addSupplier(\AppBundle\Entity\Supplier $supplier)
    {
        $this->suppliers[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param \AppBundle\Entity\Supplier $supplier
     */
    public function removeSupplier(\AppBundle\Entity\Supplier $supplier)
    {
        $this->suppliers->removeElement($supplier);
    }

    /**
     * Get suppliers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSuppliers()
    {
        return $this->suppliers;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Product
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
}
