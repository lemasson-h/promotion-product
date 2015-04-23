<?php
namespace LHelene\OrderBundle\Promotion;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Exception\PromotionNotFoundException;
use Psr\Log\LoggerInterface;

class ThreeForTwoPromotion extends PromotionBase
{
    const PROMOTION_NAME = "promo.3_for_2";
    const PROMOTION_DESC = "3 for the price of 2";

    /**
     * {@inheritdoc}
     */
    public function calculate()
    {
        $total    = 0.0;
        $products = $this->productContainer->getProducts()->toArray();
        $count    = count($products);
        $i        = 0;
        $idxFree  = ceil(($count * (2/3)));

        usort($products, array(PromotionBase::class, 'orderByPriceDesc'));

        foreach ($products as $product) {
            if ($i >= $idxFree) {
                break;
            }
            $total += $product->getPrice();
            ++$i;
        }

        return $total;
    }
}
