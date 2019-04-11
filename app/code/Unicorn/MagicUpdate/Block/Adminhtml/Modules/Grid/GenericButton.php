<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Block\Adminhtml\Modules\Grid;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    private $context;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * UpdateButton constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context;
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(Context $context, ScopeConfigInterface $scopeConfig) {
        $this->context = $context;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get enable_automatic_update config value
     *
     * @return mixed
     */
    public function getEnabledAutoUpdateConfig ()
    {
        return $this->scopeConfig->getValue(
            'system/magic_update/enable_automatic_update',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
