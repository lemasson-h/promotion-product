<?php
namespace LHelene\OrderBundle\Container;

use Doctrine\Common\Collections\ArrayCollection;
use LHelene\OrderBundle\Model\Category;
use LHelene\OrderBundle\Model\Product;

class ProductContainer
{
    /**
     * @var ArrayCollection|Category[]
     */
    protected $categories;

    /**
     * @var ArrayCollection|Product[]
     */
    protected $products;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->products   = new ArrayCollection();
    }

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function addCategory(Category $category)
    {
        if (!$this->categories->contains($category)) {
            foreach ($category->getProducts() as $product){
                if (!$this->products->contains($product)) {
                    $this->products->add($product);
                }
            }
            $this->categories->add($category);

            return true;
        }

        return false;
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    public function addProduct(Product $product)
    {
        if (!$this->products->contains($product)){
            if (($category = $product->getCategory()) instanceof Category) {
                if (!$this->categories->contains($category)) {
                $this->categories->add($category);
                }
            }
            $this->products->add($product);

            return true;
        }

        return false;
    }

    /**
     * @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return ArrayCollection|Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string|null $name
     *
     * @return Category|null
     */
    public function findCategoryByName($name = null)
    {
        if (!empty($name)) {
            foreach ($this->categories as $category){
                if ($name === $category->getName()) {
                    return $category;
                }
            }
        }

        return null;
    }
}
