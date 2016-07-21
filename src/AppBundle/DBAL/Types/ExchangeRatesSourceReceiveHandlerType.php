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
    const NATIONAL_BANK_KZ = 'national_bank_kz';

    /** {@inheritdoc} */
    protected static $choices = [
        self::CBR => 'Центральный Банк Российской Федерации',
        self::NATIONAL_BANK_KZ => 'Национальный Банк Казахстана',
    ];
}
