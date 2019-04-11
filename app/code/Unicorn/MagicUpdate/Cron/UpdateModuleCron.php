<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Cron;

use Unicorn\MagicUpdate\Model\ModuleList;

class UpdateModuleCron
{
    /**
     * @var ModuleList
     */
    private $moduleList;

    /**
     * UpdateModuleCron constructor.
     * @param ModuleList $moduleList
     */
    public function __construct(
        ModuleList $moduleList
    ) {
        $this->moduleList = $moduleList;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $this->moduleList->doSafeUpdate();
        return $this;
    }
}
