<?php
namespace LHelene\OrderBundle\Tests\Unit\Promotion;

use LHelene\OrderBundle\Promotion\NonePromotion;

class NonePromotionTest extends PromotionBaseTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->promotion = new NonePromotion($this->productContainer);
    }

    public function testNonePromotionWhenEmpty()
    {
        $this->assertEquals(0.0, $this->promotion->calculate());
    }

    public function testNonePromotionWhen3Product()
    {
        $total = $this->shampoo1->getPrice() + $this->shampoo2->getPrice()
                 + $this->other1->getPrice() + $this->other2->getPrice();

        $this->productContainer->addProduct($this->shampoo1);
        $this->productContainer->addProduct($this->shampoo2);
        $this->productContainer->addProduct($this->other1);
        $this->productContainer->addProduct($this->other2);

        $this->assertEquals($total, $this->promotion->calculate());
    }
}
