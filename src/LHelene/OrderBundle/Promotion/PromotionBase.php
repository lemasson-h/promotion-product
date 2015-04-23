<?php
namespace LHelene\OrderBundle\Promotion;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Exception\PromotionNotFoundException;
use LHelene\OrderBundle\Model\Product;
use Psr\Log\LoggerInterface;

abstract class PromotionBase
{
    /**
     * @var  ProductContainer
     */
    protected $productContainer;

    /**
     * @param ProductContainer $productContainer
     */
    public function __construct(ProductContainer $productContainer)
    {
        $this->productContainer = $productContainer;
    }

    /**
     * @return float
     */
    abstract public function calculate();

    /**
     * @return string
     */
    public function getName()
    {
        return static::PROMOTION_NAME;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::PROMOTION_DESC;
    }

    /**
     * @param Product $a
     * @param Product $b
     *
     * @return int
     */
    protected static function orderByPrice(Product $a, Product $b)
    {
        if ($a->getPrice() > $b->getPrice()) {
            return 1;
        } else if($a->getPrice() < $b->getPrice()) {
            return -1;
        }

        return 0;
    }

    /**
     * @param Product $a
     * @param Product $b
     *
     * @return int
     */
    protected static function orderByPriceDesc(Product $a, Product $b)
    {
        return self::orderByPrice($a, $b) * -1;
    }
}
