<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Тип хэндлера для получения курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRatesSourceReceiveHandlerType extends AbstractEnumType
{
    const CBR = 'cbr';

    /** {@inheritdoc} */
    protected $name = 'exchange_rates_source_receive_handler_type';

    /** {@inheritdoc} */
    protected static $choices = [
        self::CBR => 'Центральный Банк Российской Федерации',
    ];
}
