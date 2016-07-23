<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DBAL\Types\ExchangeRatesSourceReceiveHandlerType;
use AppBundle\Entity\ExchangeRatesSource;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Данные источников курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class LoadExchangeRatesSourceData extends AbstractFixture
{
    /** {@inheritdoc} */
    public function load(ObjectManager $manager)
    {
        $source = (new ExchangeRatesSource())
            ->setTitle('Центральный Банк Российской Федерации')
            ->setReceiveHandler(ExchangeRatesSourceReceiveHandlerType::CBR)
            ->setReceiveUrl('http://www.cbr.ru/scripts/XML_daily.asp')
            ->setBaseCurrency($this->getReference('currency-643'));
        $manager->persist($source);
        $manager->flush($source);
        $this->setReference('source-' . ExchangeRatesSourceReceiveHandlerType::CBR, $source);

        $source = (new ExchangeRatesSource())
            ->setTitle('Национальный Банк Казахстана')
            ->setReceiveHandler(ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ)
            ->setReceiveUrl('http://www.nationalbank.kz/rss/rates_all.xml')
            ->setBaseCurrency($this->getReference('currency-398'));
        $manager->persist($source);
        $manager->flush($source);
        $this->setReference('source-' . ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ, $source);
    }
}
