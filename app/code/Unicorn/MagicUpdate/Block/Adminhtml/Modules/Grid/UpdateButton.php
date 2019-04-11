<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Block\Adminhtml\Modules\Grid;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class UpdateButton
 */
class UpdateButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getButtonData(): array
    {
        $data = [];

        if (!$this->getEnabledAutoUpdateConfig()) {
            $data = [
                'label' => __('Start Update'),
                'class' => 'update primary',
                'on_click' => 'location.href=\'' . $this->getUrl('*/*/update') . '\'',
                'sort_order' => 90
            ];
        }

        return $data;
    }
}
