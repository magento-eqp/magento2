<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Unicorn\MagicUpdate\Cron;

use Unicorn\MagicUpdate\Model\ModuleList;

class UpdateModuleCron
{
    public function __construct(
        ModuleList $moduleList
    ) {
        $this->moduleList = $moduleList;
        parent::__construct();
    }

    public function execute()
    {
        $this->moduleList->doSafeUpdate();
    }

}