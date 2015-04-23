<?php
namespace LHelene\OrderBundle\Tests\Unit\Promotion;

use LHelene\OrderBundle\Promotion\ThreeForTwoPromotion;

class ThreeForTwoPromotionTest extends PromotionBaseTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->promotion = new ThreeForTwoPromotion($this->productContainer);
    }

    /**
     * Test calculator when the list is empty
     */
    public function testNonePromotionWhenEmpty()
    {
        $this->assertEquals(0.0, $this->promotion->calculate());
    }

    /**
     * Test calculator when there are 4 products, we got 1 free
     */
    public function testNonePromotionWhen4Products()
    {
        $total = $this->shampoo1->getPrice() + $this->other1->getPrice()
                 + $this->condi3->getPrice();

        $this->productContainer->addProduct($this->shampoo1);
        $this->productContainer->addProduct($this->shampoo3);
        $this->productContainer->addProduct($this->condi3);
        $this->productContainer->addProduct($this->other1);

        $this->assertEquals($total, $this->promotion->calculate());
    }

    /**
     * Test calculator when there are 6 products, we got 2 free
     */
    public function testNonePromotionWhen2Shampoos1Condi()
    {
        $total = $this->shampoo1->getPrice() + $this->condi3->getPrice()
                 + $this->other1->getPrice() + $this->other2->getPrice();

        $this->productContainer->addProduct($this->shampoo1);
        $this->productContainer->addProduct($this->shampoo3);
        $this->productContainer->addProduct($this->condi3);
        $this->productContainer->addProduct($this->other1);
        $this->productContainer->addProduct($this->other2);
        $this->productContainer->addProduct($this->other3);

        $this->assertEquals($total, $this->promotion->calculate());
    }

    /**
     * Test calculator when there are 2 products, we got nothing free
     */
    public function testNonePromotionWhen2Products()
    {
        $total = $this->shampoo4->getPrice() + $this->other2->getPrice();

        $this->productContainer->addProduct($this->shampoo4);
        $this->productContainer->addProduct($this->other2);

        $this->assertEquals($total, $this->promotion->calculate());
    }
}
