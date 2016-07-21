<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Service\LoadExchangeRates;

use AppBundle\DBAL\Types\ExchangeRatesSourceReceiveHandlerType;
use AppBundle\Service\LoadExchangeRates\Handlers\Cbr;
use AppBundle\Service\LoadExchangeRates\Handlers\NationalBankKz;
use AppBundle\Entity\ExchangeRatesSource;
use AppBundle\Manager\CurrencyManager;
use Psr\Log\LoggerInterface;

/**
 * Фабрика хэндлеров для получения курсов
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class Factory
{
    /**
     * Массив соответствий названий хэндлеров и их классов
     *
     * @var array
     */
    protected static $handlers = [
        ExchangeRatesSourceReceiveHandlerType::CBR => Cbr::class,
        ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ => NationalBankKz::class,
    ];

    /**
     * Возвращает хэндлер для получения курсов
     *
     * @param ExchangeRatesSource $source Источник курсов валют
     * @param CurrencyManager $currencyManager Менеджер валют
     * @param LoggerInterface $logger Логгер
     *
     * @return Handler
     *
     * @throws \InvalidArgumentException
     */
    public static function getHandler(
        ExchangeRatesSource $source,
        CurrencyManager $currencyManager,
        LoggerInterface $logger
    ) {
        if (array_key_exists($source->getReceiveHandler(), static::$handlers)) {
            return new static::$handlers[$source->getReceiveHandler()]($source, $currencyManager, $logger);
        }

        throw new \InvalidArgumentException;
    }
}
