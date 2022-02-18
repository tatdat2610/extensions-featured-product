<?php
declare(strict_types=1);

namespace Extensions\FeaturedProduct\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Extensions\FeaturedProduct\Helper
 */
class Config extends AbstractHelper
{
    /**
     * Enable module configure path
     */
    const XML_PATH_FEATURED_PRODUCT_ENABLE = 'featured_product/general/enable';

    /**
     * Maximum number of product on featured product block
     */
    const XML_PATH_FEATURED_PRODUCT_MAXIMUM_ITEM = 'featured_product/general/limit_number_of_product';

    /**
     * Is enable module
     *
     * @param null $store
     *
     * @return bool
     */
    public function isEnable($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FEATURED_PRODUCT_ENABLE,
            ScopeInterface::SCOPE_WEBSITE,
            $store
        );
    }

    /**
     * @param null $store
     *
     * @return int
     */
    public function limitNumberOfItems($store = null): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_FEATURED_PRODUCT_MAXIMUM_ITEM,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
