<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Хранилище источников курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRatesSourceRepository extends EntityRepository
{
    /**
     * Возвращает источник по ID
     *
     * @param integer $sourceId ID источника
     *
     * @return \AppBundle\Entity\ExchangeRatesSource
     */
    public function findOneById($sourceId)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.id = ?0')
            ->setParameters([(int) $sourceId])
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getOneOrNullResult();
    }
}
