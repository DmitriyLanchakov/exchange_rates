<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ExchangeRate;
use AppBundle\DBAL\Types\ExchangeRatesSourceReceiveHandlerType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Данные курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class LoadExchangeRateData extends AbstractFixture
{
    /** {@inheritdoc} */
    public function load(ObjectManager $manager)
    {
        $rate = (new ExchangeRate())
            ->setExchangeRatesSource($this->getReference('source-' . ExchangeRatesSourceReceiveHandlerType::CBR))
            ->setToCurrency($this->getReference('currency-398'))
            ->setValue(5.3);
        $manager->persist($rate);
        $manager->flush($rate);

        $rate = (new ExchangeRate())
            ->setExchangeRatesSource($this->getReference('source-' . ExchangeRatesSourceReceiveHandlerType::CBR))
            ->setToCurrency($this->getReference('currency-840'))
            ->setValue(0.015);
        $manager->persist($rate);
        $manager->flush($rate);

        $rate = (new ExchangeRate())
            ->setExchangeRatesSource(
                $this->getReference('source-' . ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ)
            )
            ->setToCurrency($this->getReference('currency-643'))
            ->setValue(0.19);
        $manager->persist($rate);
        $manager->flush($rate);

        $rate = (new ExchangeRate())
            ->setExchangeRatesSource(
                $this->getReference('source-' . ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ)
            )
            ->setToCurrency($this->getReference('currency-840'))
            ->setValue(0.0029);
        $manager->persist($rate);
        $manager->flush($rate);
    }
}
