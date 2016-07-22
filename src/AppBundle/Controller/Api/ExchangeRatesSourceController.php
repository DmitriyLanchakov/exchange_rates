<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\ExchangeRatesSource;
use AppBundle\Form\ExchangeRatesSourceType;

use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Контроллер REST API источников курсов валют
 *
 * @RouteResource("ExchangeRatesSource")
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRatesSourceController extends AbstractController
{
    /** {@inheritdoc} */
    protected function getManager()
    {
        return $this->get('app.manager.exchange_rates_source');
    }

    /** {@inheritdoc} */
    protected function getFormTypeClass()
    {
        return ExchangeRatesSourceType::class;
    }

    /** {@inheritdoc} */
    protected function getNewEntity()
    {
        return new ExchangeRatesSource();
    }
}
