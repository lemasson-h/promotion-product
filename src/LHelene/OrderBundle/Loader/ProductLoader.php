<?php
namespace LHelene\OrderBundle\Loader;

use LHelene\OrderBundle\Container\ProductContainer;
use LHelene\OrderBundle\Factory\ProductFactory;
use LHelene\OrderBundle\Model\Category;
use LHelene\OrderBundle\Model\Product;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\DomCrawler\Crawler;

class ProductLoader
{
    const FILE_ORDER_NAME    = "order";
    const FILE_PRODUCTS_NAME = "products";
    const FILE_PRODUCT_NAME  = "product";
    const FILE_PRODUCT_TITLE = "title";
    const FILE_PRODUCT_PRICE = "price";
    const FILE_CATEGORY_NAME = "category";
    const FILE_TOTAL_NAME    = "total";

    /**
     * @var ProductContainer
     */
    protected $productContainer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
     * @param ProductContainer $productContainer
     * @param LoggerInterface  $logger
     */
    public function __construct(ProductContainer $productContainer, LoggerInterface $logger)
    {
        $this->productContainer = $productContainer;
        $this->logger = $logger;
    }

    /**
     * Allow to instantiate the crawler for a new Document
     *
     * @param string $filename
     *
     * @return bool
     */
    public function validFile($filename)
    {
        $content = null;

        if (!file_exists($filename)) {
            $this->logger->warning(sprintf('Invalid filename: %s', $filename));

            return false;
        }

        if (false === ($content = file_get_contents($filename))){
            $this->logger->warning(sprintf("Enable to get file content: %s", $filename));

            return false;
        }

        $this->filename  = $filename;
        $this->crawler   = new Crawler();
        $this->totalNode = null;
        $document        = new \DOMDocument();

        try {
            $document->loadXML($content);
        } catch (\Exception $e) {
            $this->logger->warning(sprintf("Invalid content for file: %s", $filename));

            return false;
        }
        $this->crawler->addDocument($document);

        return true;
    }

    /**
     * Use to find/create a category for a product
     *
     * @param \DOMNode $node
     *
     * @return Category|null
     */
    protected function loadCategory(\DOMNode $node)
    {
        $category = null;

        if (!$node->hasChildNodes()) {
            return $category;
        }

        foreach ($node->childNodes as $child) {
            if (!$child instanceof \DOMText && self::FILE_CATEGORY_NAME === $child->nodeName && !empty($child->nodeValue)) {
                $category = $this->productContainer->findCategoryByName($child->nodeValue);
                if (null === $category) {
                    $category = new Category($child->nodeValue);
                }

                return $category;
            }
        }

        return $category;
    }

    /**
     * Use to create products and save categories
     *
     * @param \DOMNode $node
     *
     * @return int
     */
    protected function loadProducts(\DOMNode $node)
    {
        $count = 0;

        if (!$node->hasChildNodes()) {
            return $count;
        }

        /** @var \DOMNode $child */
        foreach ($node->childNodes as $child){
            if (!$child instanceof \DOMText && self::FILE_PRODUCT_NAME === $child->nodeName) {
                if ($child->hasAttributes() &&
                    null !== ($title = $child->attributes->getNamedItem(self::FILE_PRODUCT_TITLE)) &&
                    null !== ($price = $child->attributes->getNamedItem(self::FILE_PRODUCT_PRICE))
                    ) {
                    $product  = null;
                    $category = $this->loadCategory($child);
                    if ($category instanceof Category) {
                        $product = ProductFactory::create($category, $title->nodeValue, $price->nodeValue);
                        $this->productContainer->addProduct($product);
                        $this->productContainer->addCategory($category);
                        ++$count;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Use to load all products with their categories
     *
     * @param string $filename
     *
     * @return bool
     */
    public function load($filename)
    {
        if (!$this->validFile($filename)) {
            return false;
        }

        $order = $this->crawler->getNode(0);
        if (null !== $order && self::FILE_ORDER_NAME === $order->nodeName) {
            /** @var \DOMNode $node */
            foreach ($order->childNodes as $node) {
                if (!$node instanceof \DOMText) {
                    if (self::FILE_PRODUCTS_NAME === $node->nodeName) {
                        $this->loadProducts($node);
                    } else if (self::FILE_TOTAL_NAME === $node->nodeName) {
                        $this->totalNode = $node;
                    }
                }
            }
        }

        if (!$this->totalNode instanceof \DOMNode) {
            $this->logger->notice('[ProductLoader:load] Total node not found');
            return false;
        }

        return true;
    }

    /**
     * Use to update the total on the XML file
     *
     * @param string $price
     *
     * @return bool
     */
    public function updateTotal($price)
    {
        if (!$this->totalNode instanceof \DOMNode) {
            $this->logger->notice('[ProductLoader:updateTotal] Total node isn\'t a valid DOMNode');
            return false;
        }

        $this->totalNode->nodeValue = number_format($price, 2);
        if (false === ($content = $this->totalNode->ownerDocument->saveXML())) {
            $this->logger->notice('[ProductLoader:updateTotal] unable to get xml content to save the file');
            return false;
        }

        if (false === file_put_contents($this->filename, $content, LOCK_EX)) {
            $this->logger->notice('[ProductLoader:updateTotal] unable to save the file');
            return false;
        }

        return true;
    }

    /**
     * @return \DOMNode|null
     */
    public function getTotalNode()
    {
        return $this->totalNode;
    }
}
