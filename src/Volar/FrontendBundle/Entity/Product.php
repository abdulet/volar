<?php

namespace Volar\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="string", length=150)
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="longDescription", type="string", length=255)
    private $longDescription;
     */

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product",cascade={"persist"})
     * */
    protected $images;

    public function __construct(){
        $this->images = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Product
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    
        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     * @return Product
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    
        return $this;
    }

    /**
     * Get longDescription
     *
     * @return string 
    public function getLongDescription()
    {
        return $this->longDescription;
    }
     */

    /**
     * Set price
     *
     * @param float $price
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
     * Add images
     *
     * @param \Volar\FrontendBundle\Entity\productImage $images
     * @return Product
     */
    public function addImage(\Volar\FrontendBundle\Entity\productImage $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Volar\FrontendBundle\Entity\productImage $images
     */
    public function removeImage(\Volar\FrontendBundle\Entity\productImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
}
