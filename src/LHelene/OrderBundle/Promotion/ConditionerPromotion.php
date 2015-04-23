<?php
namespace LHelene\OrderBundle\Promotion;

use Doctrine\Common\Collections\ArrayCollection;
use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Exception\PromotionNotFoundException;
use LHelene\OrderBundle\Model\Category;
use LHelene\OrderBundle\Model\Product;
use Psr\Log\LoggerInterface;

class ConditionerPromotion extends PromotionBase
{
    const PROMOTION_NAME = "promo.conditioner";
    const PROMOTION_DESC = "Buy Shampoo & get Conditioner for 50% off";

    const SHAMPOO_CATEGORY     = "Shampoo";
    const CONDITIONER_CATEGORY = "Conditioner";

    /**
     * {@inheritdoc}
     */
    public function calculate()
    {
        $total = 0.0;
        $shampooList = [];
        $conditionerList = [];

        foreach ($this->productContainer->getProducts() as $product) {
            if (($category = $product->getCategory()) instanceof Category){
                switch ($category->getName()){
                    case self::SHAMPOO_CATEGORY:
                        $shampooList[] = $product;
                        $total += $product->getPrice();
                        break;
                    case self::CONDITIONER_CATEGORY:
                        $conditionerList[] = $product;
                        break;
                    default:
                        $total += $product->getPrice();
                }
            }
        }

        usort($shampooList, array(PromotionBase::class, 'orderByPrice'));
        usort($conditionerList, array(PromotionBase::class, 'orderByPrice'));

        $countShampoo = count($shampooList);
        foreach ($conditionerList as $conditioner) {
            if ($countShampoo > 0) {
                $total += ($conditioner->getPrice() / 2.0);
                --$countShampoo;
            } else {
                $total += $conditioner->getPrice();
            }
        }

        return $total;
    }
}
