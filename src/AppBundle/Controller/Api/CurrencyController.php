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
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;

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
    /**
     * Возвращает валюту
     *
     * @param Currency $currency Валюта
     *
     * @return Currency
     */
    public function getAction(Currency $currency)
    {
        return $currency;
    }

    /**
     * Создаёт валюту
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     *
     * @return Currency|\FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        return $this->createEntity($request);
    }

    /**
     * Обновляет валюту
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     * @param Currency $currency Валюта
     *
     * @return Currency|\FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Currency $currency)
    {
        return $this->updateEntity($request, $currency);
    }

    /**
     * Обновляет свойства валюты
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     * @param Currency $currency Валюта
     *
     * @return object|\FOS\RestBundle\View\View
     */
    public function patchAction(Request $request, Currency $currency)
    {
        return $this->putAction($request, $currency);
    }

    /**
     * Удаляет валюту
     *
     * @View(statusCode=204)
     *
     * @param Request $request Запрос
     * @param Currency $currency Валюта
     *
     * @return null|\FOS\RestBundle\View\View
     */
    public function deleteAction(Request $request, Currency $currency)
    {
        return $this->deleteEntity($request, $currency);
    }

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
