<?php
namespace LHelene\OrderBundle\Tests\Unit\Loader;

use \DOMNode;
use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Loader\ProductLoader;
use Monolog\Logger;
use \Phake;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

class ProductLoaderTest extends \PHPUnit_Framework_TestCase
{
    CONST FILENAME               = "/example/example.xml";
    CONST FILENAME_COPY          = "/example/example2.xml";
    CONST FILENAME_MISS_XML      = "/example/example_missing_xml.xml";
    CONST FILENAME_MISS_ORDER    = "/example/example_missing_order.xml";
    CONST FILENAME_MISS_PRICE    = "/example/example_missing_price.xml";
    CONST FILENAME_MISS_PRODUCTS = "/example/example_missing_products.xml";
    CONST FILENAME_MISS_TITLE    = "/example/example_missing_title.xml";
    CONST FILENAME_MISS_TOTAL    = "/example/example_missing_total.xml";
    CONST FILENAME_MISS_CATEGORY = "/example/example_missing_category.xml";
    CONST FILENAME_INVALID       = "/example/example_invalid.xml";

    /**
     * @var ProductContainer
     */
    protected $productContainer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ProductLoader
     */
    protected $productLoader;

    /**
     * @var string $filename
     */
    protected $filename;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var \DOMNode
     */
    protected $totalNode;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->productContainer = Phake::mock(ProductContainer::class);
        $this->logger           = Phake::mock(Logger::class);
        $fs                     = new Filesystem();
        $this->filename         = __DIR__ . self::FILENAME_COPY;
        $this->productLoader    = new ProductLoader($this->productContainer, $this->logger);

        $fs->copy(__DIR__ . self::FILENAME, $this->filename);

    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->filename);
    }

    /**
     * Try that when the file doesn't exist, we've got a warning log
     */
    public function testValidFileWhenItDoesntExist()
    {
        $invalidName = __DIR__ . self::FILENAME_INVALID;
        $code = $this->productLoader->validFile($invalidName);

        $this->assertEquals(false, $code);
        Phake::verify($this->logger, Phake::times(1))->warning(sprintf('Invalid filename: %s', $invalidName));
    }

    /**
     * Try that When the xml is invalid we got a warning log
     */
    public function testLoadProductsMissingXML()
    {
        $filename = __DIR__ . self::FILENAME_MISS_XML;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::never())->addProduct(Phake::anyParameters());
        Phake::verify($this->logger, Phake::times(1))->warning(sprintf("Invalid content for file: %s", $filename));
        $this->assertNull($this->productLoader->getTotalNode());
    }

    /**
     * Try that When the node order is missing, nothing is done
     */
    public function testLoadProductsMissingOrder()
    {
        $filename = __DIR__ . self::FILENAME_MISS_ORDER;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::never())->addProduct(Phake::anyParameters());
        Phake::verify($this->logger, Phake::never())->warning(Phake::anyParameters());
        $this->assertNull($this->productLoader->getTotalNode());
    }

    /**
     * Try that When the node products is missing, there aren't any product added
     */
    public function testLoadProductsMissingProducts()
    {
        $filename = __DIR__ . self::FILENAME_MISS_PRODUCTS;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::never())->addProduct(Phake::anyParameters());
        Phake::verify($this->logger, Phake::never())->warning(Phake::anyParameters());
        $this->assertInstanceOf(DOMNode::class, $this->productLoader->getTotalNode());
    }

    /**
     * Try that When the node total is missing the property totalNode isn't sed
     */
    public function testLoadProductsMissingTotal()
    {
        $filename = __DIR__ . self::FILENAME_MISS_TOTAL;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::times(3))->addProduct(Phake::anyParameters());
        Phake::verify($this->logger, Phake::never())->warning(Phake::anyParameters());
        $this->assertNull($this->productLoader->getTotalNode());
    }

    /**
     * Try that When the attribute price is missing for a product, the product isn't added
     */
    public function testLoadProductsMissingPrice()
    {
        $filename = __DIR__ . self::FILENAME_MISS_PRICE;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::times(2))->addProduct(Phake::anyParameters());
        Phake::verify($this->productContainer, Phake::times(1))->findCategoryByName('Shampoo');
        Phake::verify($this->productContainer, Phake::never())->findCategoryByName('Conditioner');
        Phake::verify($this->productContainer, Phake::times(1))->findCategoryByName('Lipstick');
        Phake::verify($this->logger, Phake::never())->warning(Phake::anyParameters());
        $this->assertInstanceOf(DOMNode::class, $this->productLoader->getTotalNode());
    }

    /**
     * Try that When the attribute title is missing for a product, the product isn't added
     */
    public function testLoadProductsMissingTitle()
    {
        $filename = __DIR__ . self::FILENAME_MISS_TITLE;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::times(1))->addProduct(Phake::anyParameters());
        Phake::verify($this->productContainer, Phake::times(1))->findCategoryByName('Shampoo');
        Phake::verify($this->productContainer, Phake::never())->findCategoryByName('Lipstick');
        Phake::verify($this->logger, Phake::never())->warning(Phake::anyParameters());
        $this->assertInstanceOf(DOMNode::class, $this->productLoader->getTotalNode());
    }

    /**
     * Try that When the node category is missing for a product, the product isn't added
     */
    public function testLoadProductsMissingCategory()
    {
        $filename = __DIR__ . self::FILENAME_MISS_CATEGORY;
        $this->productLoader->load($filename);

        Phake::when($this->productContainer)->addProduct(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->addCategory(Phake::anyParameters())->thenReturn($this->productContainer);
        Phake::when($this->productContainer)->findCategoryByName(Phake::anyParameters())->thenReturn(null);

        Phake::verify($this->productContainer, Phake::times(1))->addProduct(Phake::anyParameters());
        Phake::verify($this->productContainer, Phake::times(1))->findCategoryByName(Phake::anyParameters());
        Phake::verify($this->productContainer, Phake::times(1))->addCategory(Phake::anyParameters());
        Phake::verify($this->logger, Phake::never())->warning(Phake::anyParameters());
        $this->assertInstanceOf(DOMNode::class, $this->productLoader->getTotalNode());
    }

    /**
     * Test if when we try to set the value for total node that is not defined, we've got a notice log
     */
    public function testUpdateTotalWhenTotalNodeNotSet()
    {
        $this->productLoader->updateTotal('45.50');

        $this->assertNull($this->productLoader->getTotalNode());
        Phake::verify($this->logger, Phake::times(1))->notice('[ProductLoader:updateTotal] Total node isn\'t a valid DOMNode');

    }

    /**
     * Test if when we set the value for total node it is set on the file
     */
    public function testUpdateTotalWhenTotalNodeIsSet()
    {
        $this->productLoader->load($this->filename);
        $this->productLoader->updateTotal('45.50');

        $this->assertInstanceOf(DOMNode::class, $this->productLoader->getTotalNode());
        Phake::verify($this->logger, Phake::never())->notice(Phake::anyParameters());
        $this->assertEquals(45.50, floatval($this->productLoader->getTotalNode()->nodeValue));
    }
}
