<?php
namespace LHelene\OrderBundle\Tests\Unit\Promotion;

use LHelene\OrderBundle\Promotion\ConditionerPromotion;

class ConditionerPromotionTest extends PromotionBaseTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->promotion = new ConditionerPromotion($this->productContainer);
    }

    /**
     * Test calculator when the list is empty
     */
    public function testNonePromotionWhenEmpty()
    {
        $this->assertEquals(0.0, $this->promotion->calculate());
    }

    /**
     * Test calculator when there are less shampoos than conditioners
     */
    public function testNonePromotionWhen2Shampoos3CondiAndOther()
    {
        $total = $this->shampoo1->getPrice() + $this->shampoo2->getPrice() + $this->other1->getPrice()
                 + ($this->condi1->getPrice() / 2.0) + ($this->condi2->getPrice() / 2.0)
                 + $this->condi3->getPrice();

        $this->productContainer->addProduct($this->shampoo1);
        $this->productContainer->addProduct($this->shampoo2);
        $this->productContainer->addProduct($this->condi1);
        $this->productContainer->addProduct($this->condi2);
        $this->productContainer->addProduct($this->condi3);
        $this->productContainer->addProduct($this->other1);

        $this->assertEquals($total, $this->promotion->calculate());
    }

    /**
     * Test calculator when there are more shampoos than conditioners
     */
    public function testNonePromotionWhen2Shampoos1Condi()
    {
        $total = $this->shampoo3->getPrice() + ($this->condi4->getPrice() / 2.0)
                 + $this->condi3->getPrice();

        $this->productContainer->addProduct($this->shampoo3);
        $this->productContainer->addProduct($this->condi3);
        $this->productContainer->addProduct($this->condi4);

        $this->assertEquals($total, $this->promotion->calculate());
    }

    /**
     * Test calculator when there are as much shampoos as conditioners
     */
    public function testNonePromotionWhen2Shampoos2Condi()
    {
        $total = $this->shampoo3->getPrice() + $this->shampoo4->getPrice()
                 + ($this->condi1->getPrice() / 2.0) + ($this->condi3->getPrice() / 2.0);

        $this->productContainer->addProduct($this->shampoo3);
        $this->productContainer->addProduct($this->shampoo4);
        $this->productContainer->addProduct($this->condi1);
        $this->productContainer->addProduct($this->condi3);

        $this->assertEquals($total, $this->promotion->calculate());
    }
}
