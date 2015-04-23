<?php
namespace LHelene\OrderBundle\Promotion;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Exception\PromotionNotFoundException;
use Psr\Log\LoggerInterface;

class NonePromotion extends PromotionBase
{
    const PROMOTION_NAME = "promo.none";
    const PROMOTION_DESC = "none";

    /**
     * {@inheritdoc}
     */
    public function calculate()
    {
        $total = 0.0;

        foreach ($this->productContainer->getProducts() as $product) {
            $total += $product->getPrice();
        }

        return $total;
    }
}
