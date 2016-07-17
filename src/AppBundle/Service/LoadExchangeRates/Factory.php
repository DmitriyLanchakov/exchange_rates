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
use AppBundle\Entity\ExchangeRatesSource as ExchangeRatesSourceEntity;

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
    ];

    /**
     * Возвращает хэндлер для получения курсов
     *
     * @param ExchangeRatesSourceEntity $source Источник курсов валют
     *
     * @return Handler
     *
     * @throws \InvalidArgumentException
     */
    public static function getHandler(ExchangeRatesSourceEntity $source)
    {
        if (array_key_exists($source->getReceiveHandler(), static::$handlers)) {
            return new static::$handlers[$source->getReceiveHandler()]($source);
        }

        throw new \InvalidArgumentException;
    }
}
