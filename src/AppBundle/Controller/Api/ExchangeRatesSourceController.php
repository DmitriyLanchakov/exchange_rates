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
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;

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
    /**
     * Возвращает источник курсов
     *
     * @param ExchangeRatesSource $source Источник курсов
     *
     * @return ExchangeRatesSource
     */
    public function getAction(ExchangeRatesSource $source)
    {
        return $source;
    }

    /**
     * Создаёт источник курсов
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     *
     * @return ExchangeRatesSource|\FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        return $this->createEntity($request);
    }

    /**
     * Обновляет источник курсов
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     * @param ExchangeRatesSource $source Источник курсов
     *
     * @return ExchangeRatesSource|\FOS\RestBundle\View\View
     */
    public function putAction(Request $request, ExchangeRatesSource $source)
    {
        return $this->updateEntity($request, $source);
    }

    /**
     * Обновляет свойства источника курсов
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     * @param ExchangeRatesSource $source Источник курсов
     *
     * @return ExchangeRatesSource|\FOS\RestBundle\View\View
     */
    public function patchAction(Request $request, ExchangeRatesSource $source)
    {
        return $this->putAction($request, $source);
    }

    /**
     * Удаляет источник курсов
     *
     * @View(statusCode=204)
     *
     * @param Request $request Запрос
     * @param ExchangeRatesSource $source Источник курсов
     *
     * @return null|\FOS\RestBundle\View\View
     */
    public function deleteAction(Request $request, ExchangeRatesSource $source)
    {
        return $this->deleteEntity($request, $source);
    }
    
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
