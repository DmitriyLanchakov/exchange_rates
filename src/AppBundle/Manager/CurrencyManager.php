<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Currency;

/**
 * Менеджер валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class CurrencyManager extends AbstractManager
{
    /**
     * Возвращает все валюты
     *
     * @return \AppBundle\Entity\Currency[]
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Возвращает все валюты в индексированном по ID массиве
     *
     * @return \AppBundle\Entity\Currency[]
     */
    public function findAllIndexedById()
    {
        $currencies = $this->findAll();

        $result = [];

        /** @var Currency $currency */
        foreach ($currencies as $currency) {
            $result[$currency->getId()] = $currency;
        }

        return $result;
    }

    /**
     * Возвращает все валюты в индексированном по тэгам массиве
     *
     * @return \AppBundle\Entity\Currency[]
     */
    public function findAllIndexedByTag()
    {
        $currencies = $this->findAll();

        $result = [];

        /** @var Currency $currency */
        foreach ($currencies as $currency) {
            $result[$currency->getTag()] = $currency;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @return \AppBundle\Repository\CurrencyRepository
     */
    public function getRepository()
    {
        return parent::getRepository();
    }

    /** {@inheritdoc} */
    public function getEntityClass()
    {
        return Currency::class;
    }
}
