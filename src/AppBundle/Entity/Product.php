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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BoxProduct", mappedBy="product")
     */
    private $boxProduct;


    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getLabel();
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
     * Set label
     *
     * @param string $label
     *sutekinabox.sqlite
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
        $this->suppliers = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add boxProduct
     *
     * @param \AppBundle\Entity\BoxProduct $boxProduct
     *
     * @return Product
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
