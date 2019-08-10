<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopmenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopmenuProducts\Observer;

use Dan0sz\TopmenuProducts\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class TopmenuGetHtmlBefore implements ObserverInterface
{
    /** @var Config $config */
    private $config;

    /** @var ProductCollection $productCollection */
    private $productCollection;

    /** @var Node $node */
    private $node;

    /** @var StoreManagerInterface $storeManager */
    private $storeManager;

    /** @var Request $request */
    private $request;

    /**
     * TopmenuGetHtmlBefore constructor.
     *
     * @param ProductCollectionFactory $productCollection
     * @param NodeFactory              $node
     * @param StoreManagerInterface    $storeManager
     */
    public function __construct(
        Config $config,
        ProductCollectionFactory $productCollection,
        NodeFactory $node,
        StoreManagerInterface $storeManager
    ) {
        $this->config            = $config;
        $this->productCollection = $productCollection;
        $this->node              = $node;
        $this->storeManager      = $storeManager;
    }

    /**
     * @param Observer $observer
     *
     * @return $this|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var Node $menu */
        $menu = $observer->getData('menu');
        /** @var Request request */
        $this->request = $observer->getData('request');
        $products      = $this->getTopmenuProducts();
        $this->addProductsToMenu($products, $menu);

        return $this;
    }

    /**
     * @return ProductCollection
     */
    private function getTopmenuProducts()
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->productCollection->create();
        $productCollection->addAttributeToSelect(
            [
                'status',
                'entity_id',
                'name',
                'url_key',
                Config::ATTR_TOPMENU_PRODUCT_ENABLED,
                Config::ATTR_TOPMENU_PRODUCT_LABEL,
                Config::ATTR_TOPMENU_PRODUCT_SORT_ORDER,
                Config::ATTR_TOPMENU_PRODUCT_IS_HOME
            ]
        );
        $productCollection
            ->addAttributeToFilter(Config::ATTR_TOPMENU_PRODUCT_ENABLED, ['eq' => 1]);
        $productCollection->addAttributeToSort(Config::ATTR_TOPMENU_PRODUCT_SORT_ORDER);

        return $productCollection;
    }

    /**
     * @param ProductCollection $products
     * @param Node              $menu
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function addProductsToMenu(ProductCollection $products, Node $menu)
    {
        $addItems = $this->config->getConfigValue(Config::XPATH_TOPMENU_PRODUCTS_GENERAL_ADD_ITEMS);

        if ($addItems == 'before') {
            $categories = $menu->getChildren()->getNodes();

            foreach ($categories as $category) {
                $menu->removeChild($category);
            }
        }

        foreach ($products as $product) {
            $node = $this->node->create(
                [
                    'data'    => $this->getProductAsArray($product),
                    'idField' => 'id',
                    'tree'    => $menu->getTree()
                ]
            );
            $menu->addChild($node);
        }

        if ($addItems == 'before') {
            foreach ($categories as $category) {
                $menu->addChild($category);
            }
        }
    }

    /**
     * @param Product $product
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getProductAsArray(Product $product)
    {
        return [
            'name'             => $product->getData(Config::ATTR_TOPMENU_PRODUCT_LABEL) ?: $product->getName(),
            'id'               => 'product-node-' . $product->getId(),
            'url'              => $this->getTopmenuProductUrl($product),
            'has_active'       => false,
            'is_active'        => $this->checkIsActive($product),
            'is_category'      => false,
            'is_parent_active' => true
        ];
    }

    /**
     * @param Product $product
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getTopmenuProductUrl(Product $product)
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();

        return $product->getData(Config::ATTR_TOPMENU_PRODUCT_IS_HOME) ?
            $store->getBaseUrl() : $product->getProductUrl();
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    private function checkIsActive(Product $product)
    {
        return $product->getId() == $this->request->getParam('id');
    }
}