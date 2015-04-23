<?php
namespace LHelene\OrderBundle\Tests\Unit\Container;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Factory\ProductFactory;
use LHelene\OrderBundle\Model\Category;
use LHelene\OrderBundle\Promotion\NonePromotion;
use LHelene\OrderBundle\Promotion\ThreeForTwoPromotion;
use LHelene\OrderBundle\Service\PromotionService;
use \Phake;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class ProductContainerTest extends \PHPUnit_Framework_TestCase
{
    const CATEGORY1 = 'category1';
    const CATEGORY2 = 'category2';

    const PRODUCT1 = 'product1';
    const PRODUCT2 = 'product2';
    const PRODUCT3 = 'product3';

    const PRODUCT1_PRICE = '3.10';
    const PRODUCT2_PRICE = '1.50';
    const PRODUCT3_PRICE = '4.30';

    /**
     * @var ProductContainer
     */
    protected $productContainer;

    protected function setUp()
    {
        $this->productContainer = new ProductContainer();
    }

    /**
     * Test when category list is empty
     */
    public function testGetCategoriesWhenEmpty()
    {
        $this->assertCount(0, $this->productContainer->getCategories());
    }

    /**
     * Test when product list is empty
     */
    public function testGetProductsWhenEmpty()
    {
        $this->assertCount(0, $this->productContainer->getProducts());
    }

    /**
     * Test add a product
     */
    public function testAddProduct()
    {
        $category = new Category(self::CATEGORY1);
        $product  = ProductFactory::create($category, self::PRODUCT1, self::PRODUCT1_PRICE);

        $this->productContainer->addProduct($product);

        $this->assertCount(1, $this->productContainer->getCategories());
        $this->assertCount(1, $this->productContainer->getProducts());
    }

    /**
     * Test add many products linked to a same category, that it's not added many times
     */
    public function testAddManyProducts()
    {
        $category1 = new Category(self::CATEGORY1);
        $category2 = new Category(self::CATEGORY1);
        $product1  = ProductFactory::create($category1, self::PRODUCT1, self::PRODUCT1_PRICE);
        $product2  = ProductFactory::create($category1, self::PRODUCT2, self::PRODUCT2_PRICE);
        $product3  = ProductFactory::create($category2, self::PRODUCT3, self::PRODUCT3_PRICE);

        $this->productContainer->addProduct($product1);
        $this->productContainer->addProduct($product2);
        $this->productContainer->addProduct($product3);

        $this->assertCount(2, $this->productContainer->getCategories());
        $this->assertCount(3, $this->productContainer->getProducts());
    }

    /**
     * Test add a product
     */
    public function testAddCategory()
    {
        $category = new Category(self::CATEGORY1);
        $product  = ProductFactory::create($category, self::PRODUCT1, self::PRODUCT1_PRICE);

        $this->productContainer->addCategory($category);

        $this->assertCount(1, $this->productContainer->getCategories());
        $this->assertCount(1, $this->productContainer->getProducts());
    }

    /**
     * Test add many category linked to same products, that it's not added many times
     */
    public function testAddManyCategories()
    {
        $category1 = new Category(self::CATEGORY1);
        $category2 = new Category(self::CATEGORY1);
        ProductFactory::create($category1, self::PRODUCT1, self::PRODUCT1_PRICE);
        ProductFactory::create($category1, self::PRODUCT2, self::PRODUCT2_PRICE);
        ProductFactory::create($category2, self::PRODUCT3, self::PRODUCT3_PRICE);

        $this->productContainer->addCategory($category1);
        $this->productContainer->addCategory($category2);

        $this->assertCount(2, $this->productContainer->getCategories());
        $this->assertCount(3, $this->productContainer->getProducts());
    }

    /**
     * Test when the category name exists
     */
    public function testFindCategoryByNameSuccess()
    {
        $category1 = new Category(self::CATEGORY1);
        $product1  = ProductFactory::create($category1, self::PRODUCT1, self::PRODUCT1_PRICE);

        $this->productContainer->addProduct($product1);
        $categoryFound = $this->productContainer->findCategoryByName(self::CATEGORY1);
        $this->assertEquals($categoryFound, $category1);
    }

    /**
     * Test when the category name doesn't exist
     */
    public function testFindCategoryByNameFailed()
    {
        $categoryFound = $this->productContainer->findCategoryByName(self::CATEGORY1);
        $this->assertNull($categoryFound);
    }
}
