<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Service\LoadExchangeRates\Handlers;

use AppBundle\Service\LoadExchangeRates\Handler as HandlerInterface;
use AppBundle\Service\LoadExchangeRates\AbstractHandler;

/**
 * Хэндлер для получения курсов Центрального Банка Российской Федерации
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class Cbr extends AbstractHandler implements HandlerInterface
{
    /** {@inheritdoc} */
    public function getRates()
    {
        return [];
    }
}
