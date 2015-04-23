<?php
namespace LHelene\OrderBundle\Tests\Unit\Container;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Promotion\NonePromotion;
use LHelene\OrderBundle\Promotion\ThreeForTwoPromotion;
use LHelene\OrderBundle\Service\PromotionService;
use \Phake;

class PromotionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PromotionService
     */
    protected $promotionService;

    /**
     * @var ProductContainer
     */
    protected $productContainer;

    protected function setUp()
    {
        $this->promotionService = new PromotionService();
        $this->productContainer = new ProductContainer();
    }

    public function testGetPromotionListWhenEmpty()
    {
        $this->assertCount(0, $this->promotionService->getPromotionList());
    }

    public function testAddPromotion()
    {
        $promotion = new NonePromotion($this->productContainer);

        $this->promotionService->addPromotion($promotion);
        $this->assertCount(1, $this->promotionService->getPromotionList());
        $this->assertEquals($promotion, $this->promotionService->getPromotion(NonePromotion::PROMOTION_NAME));
    }

    public function testAddManyPromotions()
    {
        $promotion1 = new NonePromotion($this->productContainer);
        $promotion2 = new ThreeForTwoPromotion($this->productContainer);

        $this->promotionService->addPromotion($promotion1);
        $this->promotionService->addPromotion($promotion2);
        $this->assertCount(2, $this->promotionService->getPromotionList());
        $this->assertEquals($promotion1, $this->promotionService->getPromotion(NonePromotion::PROMOTION_NAME));
        $this->assertEquals($promotion2, $this->promotionService->getPromotion(ThreeForTwoPromotion::PROMOTION_NAME));
    }

    public function testGetFirstPromotionWhenListEmpty()
    {
        $this->assertNull($this->promotionService->getFirstPromotion());
    }

    public function testGetFirstPromotionWhenListNotEmpty()
    {
        $promotion1 = new NonePromotion($this->productContainer);
        $promotion2 = new ThreeForTwoPromotion($this->productContainer);

        $this->promotionService->addPromotion($promotion1);
        $this->promotionService->addPromotion($promotion2);
        $this->assertEquals($promotion1, $this->promotionService->getFirstPromotion());
    }

    /**
     * @expectedException \LHelene\OrderBundle\Exception\PromotionNotFoundException
     */
    public function testGetPromotionWhenInvalidCode()
    {
        $promotion = new NonePromotion($this->productContainer);

        $this->promotionService->addPromotion($promotion);
        $this->promotionService->getPromotion('INVALID_PROMO');
    }
}
