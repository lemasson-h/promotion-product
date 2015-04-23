<?php
namespace LHelene\OrderBundle\Tests\Unit\Promotion;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Factory\ProductFactory;
use LHelene\OrderBundle\Model\Category;
use LHelene\OrderBundle\Promotion\PromotionBase;

class PromotionBaseTest extends \PHPUnit_Framework_TestCase
{
    const CATEGORY_SHAMPOO = 'Shampoo';
    const CATEGORY_CONDI   = 'Conditioner';
    const CATEGORY_OTHER1  = 'Lipstick';
    const CATEGORY_OTHER2  = 'Other';

    const SHAMPOO1_NAME = 'shampoo1';
    const SHAMPOO2_NAME = 'shampoo2';
    const SHAMPOO3_NAME = 'shampoo3';
    const SHAMPOO4_NAME = 'shampoo4';

    const SHAMPOO1_PRICE = '4.40';
    const SHAMPOO2_PRICE = '5.30';
    const SHAMPOO3_PRICE = '2.80';
    const SHAMPOO4_PRICE = '3.60';

    const CONDI1_NAME = 'condi1';
    const CONDI2_NAME = 'condi2';
    const CONDI3_NAME = 'condi3';
    const CONDI4_NAME = 'condi4';

    const CONDI1_PRICE = '6.30';
    const CONDI2_PRICE = '3.20';
    const CONDI3_PRICE = '11.80';
    const CONDI4_PRICE = '4.50';

    const OTHER1_NAME = 'other1';
    const OTHER2_NAME = 'other2';
    const OTHER3_NAME = 'other3';

    const OTHER1_PRICE = '3.10';
    const OTHER2_PRICE = '6.20';
    const OTHER3_PRICE = '1.80';

    /**
     * @var  ProductContainer
     */
    protected $productContainer;

    protected $shampoo1;
    protected $shampoo2;
    protected $shampoo3;
    protected $shampoo4;

    protected $condi1;
    protected $condi2;
    protected $condi3;
    protected $condi4;

    protected $other1;
    protected $other2;
    protected $other3;

    protected $categoryShampoo;
    protected $categoryCondi;
    protected $categoryOther1;
    protected $categoryOther2;

    /**
     * @var PromotionBase
     */
    protected $promotion;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->productContainer = new ProductContainer();

        $this->categoryShampoo = new Category(self::CATEGORY_SHAMPOO);
        $this->categoryCondi   = new Category(self::CATEGORY_CONDI);
        $this->categoryOther1 = new Category(self::CATEGORY_OTHER1);
        $this->categoryOther2 = new Category(self::CATEGORY_OTHER2);

        $this->shampoo1 = ProductFactory::create($this->categoryShampoo, self::SHAMPOO1_NAME, self::SHAMPOO1_PRICE);
        $this->shampoo2 = ProductFactory::create($this->categoryShampoo, self::SHAMPOO2_NAME, self::SHAMPOO2_PRICE);
        $this->shampoo3 = ProductFactory::create($this->categoryShampoo, self::SHAMPOO3_NAME, self::SHAMPOO3_PRICE);
        $this->shampoo4 = ProductFactory::create($this->categoryShampoo, self::SHAMPOO4_NAME, self::SHAMPOO4_PRICE);

        $this->condi1 = ProductFactory::create($this->categoryCondi, self::CONDI1_NAME, self::CONDI1_PRICE);
        $this->condi2 = ProductFactory::create($this->categoryCondi, self::CONDI2_NAME, self::CONDI2_PRICE);
        $this->condi3 = ProductFactory::create($this->categoryCondi, self::CONDI3_NAME, self::CONDI3_PRICE);
        $this->condi4 = ProductFactory::create($this->categoryCondi, self::CONDI4_NAME, self::CONDI4_PRICE);

        $this->other1 = ProductFactory::create($this->categoryOther1, self::OTHER1_NAME, self::OTHER1_PRICE);
        $this->other2 = ProductFactory::create($this->categoryOther1, self::OTHER2_NAME, self::OTHER2_PRICE);
        $this->other3 = ProductFactory::create($this->categoryOther2, self::OTHER3_NAME, self::OTHER3_PRICE);
    }
}
