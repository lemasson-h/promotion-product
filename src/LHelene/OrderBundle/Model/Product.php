<?php
namespace LHelene\OrderBundle\Model;

class Product
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category)
    {
        if (null !== $this->category) {
            $this->category->removeProduct($this);
        }
        $this->category = $category;
        $this->category->addProduct($this);

        return $this;
    }
}
