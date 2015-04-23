<?php
namespace LHelene\OrderBundle\Service;

use LHelene\OrderBundle\Exception\PromotionNotFoundException;
use LHelene\OrderBundle\Promotion\PromotionBase;

class PromotionService
{
    /**
     * @var PromotionBase[]
     */
    protected $promotionList;

    public function __construct()
    {
        $this->promotionList = [];
    }

    /**
     * @return PromotionBase[]
     */
    public function getPromotionList()
    {
        return $this->promotionList;
    }

    /**
     * @param PromotionBase[] $promotionList
     */
    public function setPromotionList($promotionList)
    {
        foreach($promotionList as $promotion) {
            $this->addPromotion($promotion);
        }
    }

    public function addPromotion(PromotionBase $promotion)
    {
        $this->promotionList[$promotion->getName()] = $promotion;
    }

    public function getPromotion($identifier)
    {
        if (isset($this->promotionList[$identifier])) {
            return $this->promotionList[$identifier];
        }

        throw new PromotionNotFoundException($identifier);
    }

    /**
     * @return PromotionBase|null
     */
    public function getFirstPromotion()
    {
        if (count($this->promotionList) > 0) {
            return reset($this->promotionList);
        }

        return null;
    }
}
