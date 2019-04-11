<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Block\Adminhtml\Modules\Grid;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ListButton
 */
class RefreshButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getButtonData(): array
    {
        $data = [];

        if (!$this->getEnabledAutoUpdateConfig()) {
            $data = [
                'label' => __('Check For Updates'),
                'class' => 'refresh',
                'on_click' => 'location.href=\'' . $this->getUrl('*/*/refresh') . '\'',
                'sort_order' => 10
            ];
        }

        return $data;
    }
}
