<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;

/**
 * Абрактный контроллер REST API
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
abstract class AbstractController extends VoryxController
{
    /**
     * Возвращает список ресурсов
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @QueryParam(
     *  name="offset",
     *  requirements="\d+",
     *  nullable=true,
     *  description="Offset from which to start listing notes."
     * )
     * @QueryParam(
     *  name="limit",
     *  requirements="\d+",
     *  default="20",
     *  description="How many notes to return."
     * )
     * @QueryParam(
     *  name="order_by",
     *  nullable=true,
     *  array=true,
     *  description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC"
     * )
     * @QueryParam(
     *  name="filters",
     *  nullable=true,
     *  array=true,
     *  description="Filter by fields. Must be an array ie. &filters[id]=3"
     * )
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return object|\FOS\RestBundle\View\View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $entities = $this->getManager()->findBy(
                null === $paramFetcher->get('filters') ? [] : $paramFetcher->get('filters'),
                $paramFetcher->get('order_by'),
                $paramFetcher->get('limit'),
                $paramFetcher->get('offset')
            );

            if (0 !== count($entities)) {
                return $entities;
            } else {
                return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
            }
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Создаёт сущность
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     *
     * @return object|\FOS\RestBundle\View\View
     */
    protected function createEntity(Request $request)
    {
        $entity = $this->getNewEntity();

        $form = $this->createForm($this->getFormTypeClass(), $entity, ['method' => $request->getMethod()]);
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush($entity);

            return $entity;
        } else {
            return FOSView::create(['errors' => $form->getErrors()], Codes::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Обновляет сущность
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request Запрос
     * @param object $entity Сущность
     *
     * @return object|\FOS\RestBundle\View\View
     */
    protected function updateEntity(Request $request, $entity)
    {
        try {
            $request->setMethod('PATCH');

            $form = $this->createForm($this->getFormTypeClass(), $entity, ['method' => $request->getMethod()]);
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush($entity);

                return $entity;
            } else {
                return FOSView::create(['errors' => $form->getErrors()], Codes::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Удаляет сущность
     *
     * @View(statusCode=204)
     *
     * @param Request $request Запрос
     * @param object $entity Сущность
     *
     * @return null|\FOS\RestBundle\View\View
     */
    protected function deleteEntity(Request $request, $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush($entity);

            return null;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Возвращает менеджера сущности
     *
     * @return \AppBundle\Manager\AbstractManager
     */
    abstract protected function getManager();

    /**
     * Возвращает класс формы для валидации
     *
     * @return string
     */
    abstract protected function getFormTypeClass();

    /**
     * Возвращает новую сущность
     *
     * @return object
     */
    abstract protected function getNewEntity();
}
