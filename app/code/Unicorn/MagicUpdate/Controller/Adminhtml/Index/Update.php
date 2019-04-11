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
use Unicorn\MagicUpdate\Model\ModuleList;

class Update extends \Magento\Backend\App\AbstractAction implements HttpGetActionInterface
{
    /**
     * @var Magento\Framework\App\CacheInterface
     */
    private $cache;

    /**
     * @var Unicorn\MagicUpdate\Model\ModuleList
     */
    private $moduleList;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Unicorn\MagicUpdate\Model\ModuleList $moduleList
     */
    public function __construct(
        Context $context,
        CacheInterface $cache,
        ModuleList $moduleList
    ) {
        $this->cache = $cache;
        $this->moduleList = $moduleList;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // update and refresh page
        $this->moduleList->doSafeUpdate();
        $this->messageManager->addSuccessMessage('Update Scheduled');
        /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
