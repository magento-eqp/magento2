<?php

namespace Unicorn\MagicUpdate\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Magento\Framework\Composer\MagentoComposerApplicationFactory;


class MagicUpdateCommand extends Command
{
    /**
     * @var MagentoComposerApplication
     */
    private $magentoComposerApplication;

    /**
     * MagicUpdateCommand constructor.
     * @param MagentoComposerApplicationFactory $composerAppFactory
     */
    public function __construct(
        MagentoComposerApplicationFactory $composerAppFactory
    )
    {
        $this->magentoComposerApplication = $composerAppFactory->create();
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
        $outdatedDependencies = $this->magentoComposerApplication->runComposerCommand('show -l -o -f json');
        $output->writeln($outdatedDependencies);
    }
}
