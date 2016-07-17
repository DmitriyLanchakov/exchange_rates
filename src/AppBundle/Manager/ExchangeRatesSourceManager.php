<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Manager;

use AppBundle\Entity\ExchangeRatesSource;

/**
 * Менеджер источников курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRatesSourceManager extends AbstractManager
{
    /**
     * Возвращает источник по ID
     *
     * @param integer $sourceId ID источника
     *
     * @return ExchangeRatesSource
     */
    public function findOneById($sourceId)
    {
        return $this->getRepository()->findOneById($sourceId);
    }

    /**
     * {@inheritdoc}
     *
     * @return ExchangeRatesSource[]
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * {@inheritdoc}
     *
     * @return \AppBundle\Repository\ExchangeRatesSourceRepository
     */
    public function getRepository()
    {
        return parent::getRepository();
    }

    /** {@inheritdoc} */
    public function getEntityClass()
    {
        return ExchangeRatesSource::class;
    }
}
