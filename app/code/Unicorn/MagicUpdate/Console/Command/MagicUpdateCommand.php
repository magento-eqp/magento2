<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Unicorn\MagicUpdate\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Magento\Framework\Composer\MagentoComposerApplicationFactory;
use Magento\Framework\Composer\ComposerInformation;


class MagicUpdateCommand extends Command
{
    /**
     * @var MagentoComposerApplication
     */
    private $magentoComposerApplication;

    private $composerInformation;

    /**
     * MagicUpdateCommand constructor.
     * @param MagentoComposerApplicationFactory $composerAppFactory
     */
    public function __construct(
        MagentoComposerApplicationFactory $composerAppFactory,
        ComposerInformation $composerInformation
    )
    {
        $this->magentoComposerApplication = $composerAppFactory->create();
        $this->composerInformation = $composerInformation;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('unicorn:do:update')
            ->setDescription('Let unicorns do all update magic for you.');
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandParameters = [
            'command' => 'show',
            '--outdated' => true,
            '--minor-only' => true,
            '--format' => 'json'
        ];
        $outdatedDependencies = $this->magentoComposerApplication->runComposerCommand($commandParameters);
        $output->writeln($outdatedDependencies);
    }
}
