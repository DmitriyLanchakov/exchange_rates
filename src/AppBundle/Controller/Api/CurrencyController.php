<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Currency;
use AppBundle\Form\CurrencyType;

use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Контроллер REST API валют
 *
 * @RouteResource("Currency")
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class CurrencyController extends AbstractController
{
    /** {@inheritdoc} */
    protected function getManager()
    {
        return $this->get('app.manager.currency');
    }

    /** {@inheritdoc} */
    protected function getFormTypeClass()
    {
        return CurrencyType::class;
    }

    /** {@inheritdoc} */
    protected function getNewEntity()
    {
        return new Currency;
    }
}
