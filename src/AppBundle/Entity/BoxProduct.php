<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BoxProduct
 *
 * @ORM\Table(name="box_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BoxProductRepository")
 */
class BoxProduct
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
     * @var Box
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Box", inversedBy="boxProduct")
     * @ORM\JoinColumn(nullable=false)
     */
    private $box;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="boxProduct")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->setState('requested');
        $this->setUpdatedAt(new \DateTime());
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
     * Set state
     *
     * @param string $state
     *
     * @return BoxProduct
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return BoxProduct
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set box
     *
     * @param \AppBundle\Entity\Box $box
     *
     * @return BoxProduct
     */
    public function setBox(\AppBundle\Entity\Box $box)
    {
        $this->box = $box;

        return $this;
    }

    /**
     * Get box
     *
     * @return \AppBundle\Entity\Box
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return BoxProduct
     */
    public function setProduct(\AppBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
