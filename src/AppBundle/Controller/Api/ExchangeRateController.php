<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\ExchangeRate;
use AppBundle\Form\ExchangeRateType;

use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Контроллер REST API курсов валют
 *
 * @RouteResource("ExchangeRate")
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRateController extends AbstractController
{
    /**
     * Возвращает курс
     *
     * @param ExchangeRate $rate Курс
     *
     * @return ExchangeRate
     */
    public function getAction(ExchangeRate $rate)
    {
        return $rate;
    }
    
    /** {@inheritdoc} */
    protected function getManager()
    {
        return $this->get('app.manager.exchange_rate');
    }

    /** {@inheritdoc} */
    protected function getFormTypeClass()
    {
        return ExchangeRateType::class;
    }

    /** {@inheritdoc} */
    protected function getNewEntity()
    {
        return new ExchangeRate();
    }
}
