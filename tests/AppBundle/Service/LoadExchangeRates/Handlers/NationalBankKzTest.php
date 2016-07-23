<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace Tests\AppBundle\Service\LoadExchangeRates\Handlers;

use AppBundle\Service\LoadExchangeRates\Handlers\NationalBankKz;
use AppBundle\Entity\ExchangeRate;

/**
 * Тест хэндлера Национального Банка Казахстана
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class NationalBankKzTest extends AbstractHandlerTestCase
{
    /**
     * Тестирование успешного получения курсов валют
     */
    public function testSuccessGetRates()
    {
        $source = $this->getContainer()->get('app.manager.exchange_rates_source')->findOneById(2);

        $handler = $this->getHandler($source, 200, file_get_contents(__DIR__ . '/national_bank_kz_response.xml'));

        $currencies = $this->getContainer()->get('app.manager.currency')->findAllIndexedById();

        self::assertEquals(
            [
                (new ExchangeRate())
                    ->setExchangeRatesSource($source)
                    ->setToCurrency($currencies[840])
                    ->setValue(0.0029281719422564493),
                (new ExchangeRate())
                    ->setExchangeRatesSource($source)
                    ->setToCurrency($currencies[643])
                    ->setValue(0.18867924528301888)
            ],
            $handler->getRates()
        );
    }

    /**
     * Тестирование неуспешного получения курсов валют
     *
     * @dataProvider testFailGetRatesProvider
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Wrong response
     *
     * @param integer $responseStatusCode Код ответ
     * @param mixed $responseContent Тело ответа
     */
    public function testFailGetRates($responseStatusCode, $responseContent)
    {
        $source = $this->getContainer()->get('app.manager.exchange_rates_source')->findOneById(2);

        $handler = $this->getHandler($source, $responseStatusCode, $responseContent);
        $handler->getRates();
    }

    /**
     * DataProvider для testFailGetRates
     *
     * @return array
     */
    public function testFailGetRatesProvider()
    {
        return [
            [500, null],
            [200, null],
            [200, ''],
        ];
    }

    /** {@inheritdoc} */
    protected function getHandlerClass()
    {
        return NationalBankKz::class;
    }
}
