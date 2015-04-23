<?php

namespace LHelene\OrderBundle\Factory;

use LHelene\OrderBundle\Model\Category;
use LHelene\OrderBundle\Model\Product;

class ProductFactory
{
    /**
     * @param Category $category
     * @param string   $title
     * @param string   $price
     *
     * @return Product
     */
    public static function create(Category $category, $title, $price)
    {
        $product = new Product();
        $product->setCategory($category);
        $product->setTitle($title);
        $product->setPrice(floatval($price));

        return $product;
    }
}
