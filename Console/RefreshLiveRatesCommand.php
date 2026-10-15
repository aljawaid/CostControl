<?php

namespace Kanboard\Plugin\CostControl\Console;

use Kanboard\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Refresh CostControl live currency rates outside the web request lifecycle.
 *
 * Related tests: tests/live_rate_reliability.php and tests/live_rate_integration.php.
 */
class RefreshLiveRatesCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('cost-control:refresh-rates')
            ->setDescription('Refresh CostControl live currency rates when due')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Refresh even when the current rates are not due');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $force = $input->getOption('force');

        if (!$force && !$this->currencyModel->isLiveRateRefreshDue()) {
            $output->writeln('CostControl live currency rates are already current.');
            return 0;
        }

        if (!$this->currencyModel->getLiveRates()) {
            $output->writeln('<error>CostControl could not refresh live currency rates; the last good rates were retained.</error>');
            return 1;
        }

        $output->writeln('<info>CostControl live currency rates were refreshed successfully.</info>');
        return 0;
    }
}
