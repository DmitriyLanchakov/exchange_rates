<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Currency;

/**
 * Хранилище валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class CurrencyRepository extends EntityRepository
{
    /**
     * Возвращает все валюты
     *
     * @return Currency[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
