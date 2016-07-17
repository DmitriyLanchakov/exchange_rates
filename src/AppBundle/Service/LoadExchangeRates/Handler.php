<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Service\LoadExchangeRates;

use AppBundle\Entity\ExchangeRate;

/**
 * Интерфейс хэндлеров для получения курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
interface Handler
{
    /**
     * Возвращает текущие курсы
     *
     * @return ExchangeRate[]
     */
    public function getRates();
}
