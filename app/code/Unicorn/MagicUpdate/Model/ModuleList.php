<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Model;

use Magento\Framework\Composer\MagentoComposerApplicationFactory;
use Magento\Framework\Composer\ComposerInformation;

class ModuleList
{
    /**
     * @var MagentoComposerApplication
     */
    private $magentoComposerApplication;

    /**
     * @var ComposerInformation
     */
    private $composerInformation;

    /**
     * ModuleList constructor.
     * @param MagentoComposerApplicationFactory $composerAppFactory
     * @param ComposerInformation $composerInformation
     */
    public function __construct(
        MagentoComposerApplicationFactory $composerAppFactory,
        ComposerInformation $composerInformation
    )
    {
        $this->magentoComposerApplication = $composerAppFactory->create();
        $this->composerInformation = $composerInformation;

    }

    /**
     * @return mixed
     */
    public function getModuleStatusList()
    {

        $commandParameters = [
            'command' => 'show',
            '--format' => 'json',
            '--latest' => 'true',
        ];

        $output = $this->magentoComposerApplication->runComposerCommand($commandParameters);
        $jsonOutput = substr($output, strpos ($output, '{'));
        $installedDependencies = json_decode($jsonOutput, true)['installed'];
        return  [
            'totalRecords' => count($installedDependencies),
            'items'  => $installedDependencies
            ];
    }

    /**
     * @return mixed
     */
    public function getModuleList()
    {
        //$installedMagentoModules = $this->composerInformation->getInstalledMagentoPackages();

        $installedMagentoModules = [
            'vendor/ext' => [
                'name' => 'allure-framework/allure-codeception',
                'type' => 'magento2-module',
                'version' => '1.0.0'
            ],
            'vendor/ext2' => [
                'name' => 'symfony/polyfill-php72',
                'type' => 'magento2-theme',
                'version' => '1.0.1'
            ]
        ];
        $commandParameters = [
            'command' => 'show',
            '--format' => 'json',
            '--latest' => 'true',
        ];

        $output = $this->magentoComposerApplication->runComposerCommand($commandParameters);
        $jsonOutput = substr($output, strpos ($output, '{'));
        $installedDependencies = json_decode($jsonOutput, true)['installed'];
        $installedMagentoModulesNames = array_column(array_values($installedMagentoModules), 'name');
        $moduleList = [];
        foreach ($installedDependencies as $dependency) {
            if (in_array($dependency['name'], $installedMagentoModulesNames, true)) {
                $moduleList[] = $dependency;
            }
        }

        //$moduleList example

        /*array(2) {
          [0] =>
          array(5) {
            'name' =>
            string(35) "allure-framework/allure-codeception"
            'version' =>
            string(5) "1.3.0"
            'latest' =>
            string(5) "1.3.0"
            'latest-status' =>
            string(10) "up-to-date"
            'description' =>
            string(40) "A Codeception adapter for Allure report."
          }
          [1] =>
          array(5) {
            'name' =>
            string(22) "symfony/polyfill-php72"
            'version' =>
            string(7) "v1.11.0"
            'latest' =>
            string(7) "v1.11.0"
            'latest-status' =>
            string(10) "up-to-date"
            'description' =>
            string(73) "Symfony polyfill backporting some PHP 7.2+ features to lower PHP versions"
          }
        }*/
        return $moduleList;
    }
}
