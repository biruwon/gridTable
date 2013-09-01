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
     * @ORM\Column(name="product_id", type="integer")
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $product_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="store_id", type="integer")
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $store_id;

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

    /**
     * Set cost
     *
     * @param integer $cost
     * @return StoreOrder
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
     * Set product_id
     *
     * @param integer $productId
     * @return OrderItem
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;

        return $this;
    }

    /**
     * Get product_id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set store_id
     *
     * @param integer $storeId
     * @return OrderItem
     */
    public function setStoreId($storeId)
    {
        $this->store_id = $storeId;

        return $this;
    }

    /**
     * Get store_id
     *
     * @return integer
     */
    public function getStoreId()
    {
        return $this->store_id;
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