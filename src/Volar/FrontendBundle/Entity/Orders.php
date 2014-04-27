<?php

namespace Volar\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Orders
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="orderNumber", type="string", length=12)
     */
    private $orderNumber;

    /**
     * @var string
     * @ORM\Column(name="userOrderNumber", type="string", length=8)
     */
    private $userOrderNumber;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="productId", type="integer")
     */
    private $productId;

    /**
     * @var string
     * @ORM\Column(name="anonymousPresent", type="string", length=5, nullable=true)
     */
    private $anonymousPresent;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var integer   
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="authorisationCode", type="integer", nullable=true)
     */
    private $authorisationCode;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=10, nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="joinDate", type="string", length=10)
     */
    private $joinDate;    

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=5, nullable=true)
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="responseCode", type="integer", nullable=true)
     */
    private $responseCode;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=100)
     */
    private $surname;

        /**
     * @var string
     *
     * @ORM\Column(name="mailPresent", type="string", length=100, nullable=true)
     */
    private $mailPresent;

    /**
     * @var string
     *
     * @ORM\Column(name="namePresent", type="string", length=50, nullable=true)
     */
    private $namePresent;

    /**
     * @var string
     *
     * @ORM\Column(name="surnamePresent", type="string", length=100, nullable=true)
     */
    private $surnamePresent;

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     * @return Order
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string 
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set userOrderNumber
     *
     * @param string $orderNumber
     * @return Order
     */
    public function setUserOrderNumber($orderNumber)
    {
        $this->userOrderNumber = $orderNumber;
    
        return $this;
    }

    /**
     * Get userOrderNumber
     *
     * @return string 
     */
    public function getUserOrderNumber()
    {
        return $this->userOrderNumber;
    }    

    /**
     * Set productId
     *
     * @param integer $productId
     * @return Order
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    
        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Order
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Order
     */
    public function setMailPresent($mail)
    {
        $this->mailPresent = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMailPresent()
    {
        return $this->mailPresent;
    }    

    /**
     * Set status
     *
     * @param integer $status
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set authorisationCode
     *
     * @param integer $authorisationCode
     * @return Orders
     */
    public function setAuthorisationCode($authorisationCode)
    {
        $this->authorisationCode = $authorisationCode;
    
        return $this;
    }

    /**
     * Get authorisationCode
     *
     * @return integer 
     */
    public function getAuthorisationCode()
    {
        return $this->authorisationCode;
    }

    /**
     * Set dateTime
     *
     * @param string $dateTime
     * @return Orders
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return string 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set joinDate
     *
     * @param string $date
     * @return Orders
     */
    public function setJoinDate($date)
    {
        $this->joinDate = $date;
    
        return $this;
    }

    /**
     * Get joinDate
     *
     * @return string 
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }    

    /**
     * Set responseCode
     *
     * @param integer $responseCode
     * @return Orders
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    
        return $this;
    }

    /**
     * Get responseCode
     *
     * @return integer 
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return Orders
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param string $time
     * @return Orders
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return string 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Orders
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
     * Set surname
     *
     * @param string $surname
     * @return Orders
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Orders
     */
    public function setNamePresent($name)
    {
        $this->namePresent = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getNamePresent()
    {
        return $this->namePresent;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Orders
     */
    public function setSurnamePresent($surname)
    {
        $this->surnamePresent = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurnamePresent()
    {
        return $this->surnamePresent;
    }    

    /**
     * Set anonymousPresent
     *
     * @param string $anonymous
     * @return Order
     */
    public function setAnonymousPresent($anonymous)
    {
        $this->anonymousPresent = $anonymous;
    
        return $this;
    }

    /**
     * Get anonymousPresent
     *
     * @return string 
     */
    public function getAnonymousPresent()
    {
        return $this->anonymousPresent;
    }
}
