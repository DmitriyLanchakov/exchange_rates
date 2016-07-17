<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Manager;

use AppBundle\Entity\ExchangeRate;

/**
 * Менеджер курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRateManager extends AbstractManager
{
    /**
     * Сохраняет курсы валют
     *
     * @param ExchangeRate[] $rates Курсы валют
     *
     * @return void
     */
    public function saveRates($rates)
    {
        foreach ($rates as $rate) {
            $this->getEntityManager()->persist($rate);
            $this->getEntityManager()->flush($rate);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return \AppBundle\Repository\ExchangeRateRepository
     */
    public function getRepository()
    {
        return parent::getRepository();
    }

    /** {@inheritdoc} */
    public function getEntityClass()
    {
        return ExchangeRate::class;
    }
}
