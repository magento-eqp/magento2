<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Model;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Composer\MagentoComposerApplicationFactory;
use Magento\Framework\Composer\ComposerInformation;
use Unicorn\MagicUpdate\Logger\Logger;

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
     * @var Logger
     */
    private $logger;

    /**
     * @var  CacheInterface
     */
    private $cache;

    private $cache_id = "module_cache";

    /**
     * ModuleList constructor.
     * @param MagentoComposerApplicationFactory $composerAppFactory
     * @param ComposerInformation $composerInformation
     * @param Logger $logger
     */
    public function __construct(
        MagentoComposerApplicationFactory $composerAppFactory,
        ComposerInformation $composerInformation,
        Logger $logger,
        CacheInterface $cache
    ) {
        $this->magentoComposerApplication = $composerAppFactory->create();
        $this->composerInformation = $composerInformation;
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getModuleList()
    {
        //$installedMagentoModules = $this->composerInformation->getInstalledMagentoPackages();
        $installedMagentoModules = [
            'vendor/ext' => [
                'name' => 'zendframework/zend-http',
                'type' => 'magento2-module',
                'version' => '1.0.0'
            ],
            'vendor/ext2' => [
                'name' => 'symfony/polyfill-php72',
                'type' => 'magento2-theme',
                'version' => '1.0.0'
            ]
        ];
        $commandParameters = [
            'command' => 'show',
            '--format' => 'json',
            '--latest' => 'true',
        ];

        $cacheResult = $this->cache->load($this->cache_id);
        if (!$cacheResult) {
            $output = $this->magentoComposerApplication->runComposerCommand($commandParameters);
            $jsonOutput = substr($output, strpos($output, '{'));
            $installedDependencies = json_decode($jsonOutput, true)['installed'];
            $installedMagentoModulesNames = array_column(array_values($installedMagentoModules), 'name');
            $moduleList = [];
            foreach ($installedDependencies as $dependency) {
                if (in_array($dependency['name'], $installedMagentoModulesNames, true)) {
                    $moduleList[] = $dependency;
                }
            }
            $res =   [
                'totalRecords' => count($installedDependencies),
                'items'  => $moduleList
            ];

            $this->cache->save(json_encode($res), $this->cache_id, [], 300 );
            return $res;
        }

        return json_decode($cacheResult);

    }

    /**
     * Method performs composer update.
     * ToDo: find a better place for this method.
     */
    public function doSafeUpdate()
    {
        $this->logger->info('Update started.');
        $dependencies = $this->getModuleList()['items'];
        $commandParameters = [
            'command' => 'update'
        ];
        foreach ($dependencies as $dependency) {
            if ($dependency['latest-status'] === 'semver-safe-update') {
                $commandParameters['packages'][] = $dependency['name'];
            }
        }
        try {
            $output = $this->magentoComposerApplication->runComposerCommand($commandParameters);
            $this->logger->info(sprintf('Updating %s. Output: %s', implode(', ', $commandParameters['packages']), $output));
        } catch (\RuntimeException $e) {
            $this->logger->addError(sprintf('Update failed: %s', $e->getMessage()));
        }
        $this->logger->info('Update finished.');
    }
}
