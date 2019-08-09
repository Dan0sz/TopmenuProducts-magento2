<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopMenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopMenuProducts\Observer;

use Dan0sz\TopMenuProducts\Model\Config;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class GetHtmlBefore implements ObserverInterface
{
    private $productCollection;

    private $storeManager;

    private $request;

    public function __construct(
        ProductCollectionFactory $productCollection,
        StoreManagerInterface $storeManager
    ) {
        $this->productCollection = $productCollection;
        $this->storeManager = $storeManager;
    }

    public function execute(
        Observer $observer
    ) {
        $menu = $observer->getMenu();
        $this->request = $observer->getRequest();
        $products = $this->getTopMenuProducts();
        $this->addProductsToMenu($products, $menu);

        return $this;
    }

    private function getTopMenuProducts()
    {
        /** @var ProductCollection $productCollection */
        $productCollection = $this->productCollection->create();
        $productCollection->addAttributeToSelect(
            [
                'status',
                'entity_id',
                'name',
                'url_key',
                Config::ATTR_TOP_MENU_PRODUCT_ENABLED,
                Config::ATTR_TOP_MENU_PRODUCT_LABEL,
                Config::ATTR_TOP_MENU_PRODUCT_SORT_ORDER,
                Config::ATTR_TOP_MENU_PRODUCT_IS_HOME
            ]
        );
        $productCollection->addAttributeToFilter(Config::ATTR_TOP_MENU_PRODUCT_ENABLED, ['eq' => 1]);
        $productCollection->load();

        return $productCollection;
    }

    /**
     * @param ProductCollection $products
     * @param \Magento\Framework\Data\Tree\Node $menu
     */
    private function addProductsToMenu($products, $menu)
    {
        foreach ($products as $product) {
            $nodeId = 'product-node-' . $product->getId();

            $node = new Node(
                $this->getProductAsArray($product),
                'id',
                $menu->getTree(),
                $menu
            );

            $menu->addChild($node);
        }
    }

    private function getProductAsArray($product)
    {
        return [
            'name' => $product->getTopMenuProductLabel() ?: $product->getName(),
            'id' => 'product-node-' . $product->getId(),
            'url' => $this->getTopMenuProductUrl($product),
            'has_active' => false,
            'is_active' => $this->checkIsActive($product),
            'is_category' => false,
            'is_parent_active' => true
        ];
    }

    private function getTopMenuProductUrl($product)
    {
        $store = $this->storeManager->getStore();

        return $product->getTopMenuProductIsHome() ? $store->getBaseUrl() : $product->getProductUrl();
    }

    private function checkIsActive($product)
    {
        return $product->getId() == $this->request->getParam('id');
    }
}