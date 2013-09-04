<?php

namespace Biruwon\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderItem
 *
 * @ORM\Table(name="OrderItem")
 * @ORM\Entity(repositoryClass="Biruwon\SupplierBundle\Repository\OrderItemRepository")
 */
class OrderItem
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
     * @ORM\Column(name="product")
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product", referencedColumnName="id")
     */
    private $product;

    /**
     * @ORM\Column(name="orderItems")
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumn(name="order", referencedColumnName="id")
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    public function __construct(Product $product, Order $order, $cost = null, $amount = 1)
    {
        $this->product = $product;
        $this->order = $order;
        $this->cost = $cost;
        $this->amount = $amount;
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
     * Set product
     *
     * @param integer $product
     * @return OrderItem
     */
    public function setProduct($product)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return integer 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return OrderItem
     */
    public function setOrder($order)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set cost
     *
     * @param integer $cost
     * @return OrderItem
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    
        return $this;
    }

    /**
     * Get cost
     *
     * @return integer 
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return OrderItem
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }
}