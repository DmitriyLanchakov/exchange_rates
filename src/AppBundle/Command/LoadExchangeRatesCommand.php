<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Service\LoadExchangeRates\Factory as HandlersFactory;
use AppBundle\Manager\ExchangeRatesSourceManager;
use AppBundle\Manager\ExchangeRateManager;

/**
 * Задача загрузки курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class LoadExchangeRatesCommand extends ContainerAwareCommand
{
    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('rates:load')
            ->setDescription('Load exchange rates')
            ->addArgument('source', InputArgument::OPTIONAL, 'Specific source');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceId = $input->getArgument('source');

        /** @var ExchangeRatesSourceManager $exchangeRatesSourceManager */
        $exchangeRatesSourceManager = $this->getContainer()->get('app.manager.exchange_rates_source');
        /** @var ExchangeRateManager $exchangeRateManager */
        $exchangeRateManager = $this->getContainer()->get('app.manager.exchange_rate');

        $sources = null === $sourceId
            ? $exchangeRatesSourceManager->findAll()
            : [$exchangeRatesSourceManager->findOneById($sourceId)];

        foreach ($sources as $source) {
            try {
                $handler = HandlersFactory::getHandler($source);

                $rates = $handler->getRates();

                if (0 === count($rates)) {
                    throw new \RuntimeException('0 rates was received');
                }
            } catch (\Exception $e) {
                $output->writeln(sprintf('Cannot load %s rates: ' . $e->getMessage(), $source->getReceiveHandler()));

                continue;
            }

            $exchangeRateManager->saveRates($rates);

            $output->writeln(sprintf('%s rates was successfully load', $source->getReceiveHandler()));
        }
    }
}
