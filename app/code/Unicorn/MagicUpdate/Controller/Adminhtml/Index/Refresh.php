<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);


namespace Unicorn\MagicUpdate\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\CacheInterface;

class Refresh extends \Magento\Backend\App\AbstractAction implements HttpGetActionInterface
{
    /**
     * @var Magento\Framework\App\CacheInterface
     */
    private $cache;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\CacheInterface $cache
     */
    public function __construct(
        Context $context,
        CacheInterface $cache
    ) {
        $this->cache = $cache;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // clean cache and refresh page
        $this->cache->remove('module_cache');
        /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
