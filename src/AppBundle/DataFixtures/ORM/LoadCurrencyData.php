<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Данные валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class LoadCurrencyData extends AbstractFixture
{
    /** {@inheritdoc} */
    public function load(ObjectManager $manager)
    {
        foreach ([643 => 'RUB', 398 => 'KZT', 840 => 'USD'] as $id => $tag) {
            $currency = (new Currency)
                ->setId($id)
                ->setTag($tag);
            $this->addReference('currency-' . $id, $currency);

            $manager->persist($currency);
            $manager->flush($currency);
        }
    }
}
