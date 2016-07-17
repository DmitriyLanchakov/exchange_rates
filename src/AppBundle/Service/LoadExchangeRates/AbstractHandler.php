<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Service\LoadExchangeRates;

use AppBundle\Entity\ExchangeRatesSource;

/**
 * Абстрактный класс хэндлеров для получения курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
abstract class AbstractHandler
{
    /**
     * Источник курсов валют
     *
     * @var ExchangeRatesSource
     */
    protected $exchangeRatesSource;

    /**
     * @param ExchangeRatesSource $exchangeRatesSource Источник курсов валют
     */
    public function __construct(ExchangeRatesSource $exchangeRatesSource)
    {
        $this->$exchangeRatesSource = $exchangeRatesSource;
    }
}
