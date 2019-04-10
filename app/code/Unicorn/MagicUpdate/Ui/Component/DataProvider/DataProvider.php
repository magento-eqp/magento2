<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);


namespace Unicorn\MagicUpdate\Ui\Component\DataProvider;


class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [
            'totalRecords' => 1,
            'items' => [
                ['module_name' => 'Module_Name',
                    'version' => '2.1.0',
                    'status' => 'up-to-date'
                ],
                ['module_name' => 'Module_Name2',
                    'version' => '1.2.0',
                    'status' => 'update-possible'
                ],
                ['module_name' => 'Module_Name2',
                    'version' => '2.3.0',
                    'status' => 'semver-safe-update'
                ],
            ]
        ];
    }

}
