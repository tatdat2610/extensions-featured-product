<?php

namespace Extensions\FeaturedProduct\Controller\FeaturedProduct;

use Extensions\FeaturedProduct\Helper\Config;
use Extensions\FeaturedProduct\Setup\Patch\Data\FeaturedProductAttribute;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Pricing\Helper\Data;

/**
 * Class Load
 *
 * @package Extensions\FeaturedProduct\Controller\FeaturedProduct
 */
class Load extends Action implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var RawFactory
     */
    protected $rawFactory;

    /**
     * @var DataFactory
     */
    protected $dataFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var Data
     */
    protected $pricingHelper;

    /**
     * @var Collection|null
     */
    protected $featureProducts = null;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param CollectionFactory $collectionFactory
     * @param Config $config
     * @param Image $imageHelper
     * @param Data $pricingHelper
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        CollectionFactory $collectionFactory,
        Config $config,
        Image $imageHelper,
        Data $pricingHelper
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
        $this->imageHelper = $imageHelper;
        $this->pricingHelper = $pricingHelper;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $products = $this->getFeaturedProducts();
        $response = [];
        foreach ($products as $product) {
            $response[] = [
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'original_price' => $this->pricingHelper->currency($product->getPrice(), true, false),
                'final_price' => $this->pricingHelper->currency($product->getFinalPrice(), true, false),
                'product_url' => $product->getProductUrl(),
                'short_description' => $product->getShortDescription(),
                'thumbnai_image_url' => $this->imageHelper->init($product, 'product_page_main_image_default')->getUrl(),
            ];
        }

        return $resultJson->setData($response);
    }

    /**
     * @return Collection|\Magento\Catalog\Model\ResourceModel\Product\Collection|null
     */
    private function getFeaturedProducts()
    {
        if (empty($this->featureProducts)) {
            $limit = $this->config->limitNumberOfItems();
            $this->featureProducts = $this->collectionFactory->create()
                ->addAttributeToSelect([
                    FeaturedProductAttribute::FEATURED_PRODUCT_ATTRIBUTE_CODE,
                    'name',
                    'price',
                    'short_description'
                ])
                ->addAttributeToFilter(FeaturedProductAttribute::FEATURED_PRODUCT_ATTRIBUTE_CODE, 1)
                ->setPageSize($limit);
        }

        return $this->featureProducts;
    }
}
