<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @Serializer\ExclusionPolicy("ALL")
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
     * @Assert\NotBlank(message="Le label ne peut etre vide")
     * @Serializer\Expose
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="La référence ne peut etre vide")
     * @Serializer\Expose
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     *
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     * @Assert\NotBlank(message="Le prix ne peut etre vide")
     * @Serializer\Expose
     */
    private $price;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BoxProduct", mappedBy="product", cascade={"all"})
     */
    private $boxProduct;


    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @var Supplier
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Supplier")
     * @ORM\JoinColumn(nullable=true)
     */
    private $supplier;


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

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }


    public function __toString()
    {
        return $this->label;
    }

    public function displayName()
    {
        return ''.$this->getLabel()." : ".$this->getPrice().'€';
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
        $this->supplier[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param \AppBundle\Entity\Supplier $supplier
     */
    public function removeSupplier(\AppBundle\Entity\Supplier $supplier)
    {
        $this->supplier->removeElement($supplier);
    }

    /**
     * Get supplier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
}
