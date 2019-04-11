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
     * @param CacheInterface $cache
     */
    public function __construct(
        MagentoComposerApplicationFactory $composerAppFactory,
        ComposerInformation $composerInformation,
        Logger $logger,
        CacheInterface $cache
    )
    {
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
        $cacheResult = $this->cache->load($this->cache_id);
        if (!$cacheResult) {
            $installedMagentoModules = $this->composerInformation->getInstalledMagentoPackages();
            $commandParameters = [
                'command' => 'show',
                '--format' => 'json',
                '--latest' => 'true',
            ];
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
            $res = [
                'totalRecords' => count($installedDependencies),
                'items' => $moduleList
            ];

            $this->cache->save(json_encode($res), $this->cache_id, [], 300);
            return $res;
        }

        return json_decode($cacheResult, true);

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
        $this->cache->clean($this->cache_id);
        $this->logger->info('Update finished.');
    }
}
