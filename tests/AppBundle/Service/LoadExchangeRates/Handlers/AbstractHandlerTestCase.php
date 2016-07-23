<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace Tests\AppBundle\Service\LoadExchangeRates\Handlers;

use AppBundle\Service\LoadExchangeRates\Handlers\Cbr;
use AppBundle\DataFixtures\ORM\LoadCurrencyData;
use AppBundle\DataFixtures\ORM\LoadExchangeRateData;
use AppBundle\DataFixtures\ORM\LoadExchangeRatesSourceData;
use AppBundle\Entity\ExchangeRatesSource;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Абстрактный класс тестов хэндлеров получения курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
abstract class AbstractHandlerTestCase extends WebTestCase
{
    /** {@inheritdoc} */
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([LoadCurrencyData::class, LoadExchangeRatesSourceData::class, LoadExchangeRateData::class]);
    }

    /**
     * Возвращает класс тестируемого хэндлера
     *
     * @return string
     */
    abstract protected function getHandlerClass();

    /**
     * Возвращает мок хэндлера
     *
     * @param ExchangeRatesSource $source Источник курсов валют
     * @param integer $responseStatusCode Код ответа
     * @param mixed $responseContent Тело ответа
     *
     * @return Cbr|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getHandler($source, $responseStatusCode, $responseContent)
    {
        $httpClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['send'])
            ->getMock();

        $response = new Response($responseStatusCode, [], $responseContent);

        $httpClient->expects(static::once())
            ->method('send')
            ->will(static::returnValue($response));

        $handler = $this->getMockBuilder($this->getHandlerClass())
            ->setConstructorArgs(
                [
                    $source,
                    $this->getContainer()->get('app.manager.currency'),
                    $this->getContainer()->get('logger')
                ]
            )
            ->setMethods(['getHttpClient'])
            ->getMock();

        $handler->expects(static::any())
            ->method('getHttpClient')
            ->will(static::returnValue($httpClient));

        return $handler;
    }
}
