<?php
namespace LHelene\OrderBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Category
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var ArrayCollection|Product[]
     */
    protected $products;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->products = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function addProduct($product)
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function removeProduct($product)
    {
        $this->products->remove($product);

        return $this;
    }

    /**
     * @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}
