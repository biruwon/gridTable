<?php

namespace Biruwon\SupplierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Order
 *
 * @ORM\Table(name="StoreOrder")
 * @ORM\Entity(repositoryClass="Biruwon\SupplierBundle\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Order
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
     * @ORM\Column(name="store")
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumn(name="store", referencedColumnName="id")
     */
    private $store;

    /**
     * @ORM\Column(name="items")
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order")
     * @ORM\JoinColumn(name="items", referencedColumnName="id")
     */
    private $items;

    /** @ORM\Column(name="created_at", type="date") */
    private $createdAt;

    /** @ORM\PrePersist */
    public function createDateOrder()
    {
        $numDays = rand(-30, 30);
        $this->createdAt = new \DateTime($numDays .' days');
    }

    public function __construct(Store $store)
    {
        $this->store = $store;
        $this->items = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->id;
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
     * Set store
     *
     * @param string $store
     * @return Order
     */
    public function setStore($store)
    {
        $this->store = $store;
    
        return $this;
    }

    /**
     * Get store
     *
     * @return string 
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set items
     *
     * @param string $items
     * @return Order
     */
    public function setItems($items)
    {
        $this->items = $items;
    
        return $this;
    }

    /**
     * Get items
     *
     * @return string 
     */
    public function getItems()
    {
        return $this->items;
    }
}