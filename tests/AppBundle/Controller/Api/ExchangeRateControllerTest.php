<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace Tests\AppBundle\Controller\Api;

use AppBundle\DataFixtures\ORM\LoadCurrencyData;
use AppBundle\DataFixtures\ORM\LoadExchangeRateData;
use AppBundle\DataFixtures\ORM\LoadExchangeRatesSourceData;
use AppBundle\DBAL\Types\ExchangeRatesSourceReceiveHandlerType;
use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Тест REST API контроллера курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRateControllerTest extends WebTestCase
{
    const API_URL = '/api/exchangerates';

    /** {@inheritdoc} */
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([LoadCurrencyData::class, LoadExchangeRatesSourceData::class, LoadExchangeRateData::class]);
    }

    /**
     * Тестирование получения курса
     *
     * @dataProvider testGetActionProvider
     *
     * @param integer $rateId ID курса
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     * @param array|null $expectedResponseContent Ожидаемое тело ответа
     */
    public function testGetAction($rateId, $expectedResponseStatusCode, array $expectedResponseContent = null)
    {
        $client = static::createClient();
        $client->request('GET', static::API_URL . '/' . $rateId);

        $response = $client->getResponse();
        static::assertSame($expectedResponseStatusCode, $response->getStatusCode());
        if (null !== $expectedResponseContent) {
            $responseContent = json_decode($response->getContent(), true);
            static::assertNotFalse(strtotime($responseContent['created_at']));
            unset($responseContent['created_at']);
            static::assertSame($expectedResponseContent, $responseContent);
        }
    }

    /**
     * DataProvider для testGetAction
     *
     * @return array
     */
    public function testGetActionProvider()
    {
        return [
            [
                1,
                Codes::HTTP_OK,
                [
                    'id' => 1,
                    'exchange_rates_source' => [
                        'id' => 1,
                        'title' => 'Центральный Банк Российской Федерации',
                        'receive_url' => 'http://www.cbr.ru/scripts/XML_daily.asp',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                        'base_currency' => [
                            'id' => 643,
                            'tag' => 'RUB',
                        ],
                    ],
                    'to_currency' => [
                        'id' => 398,
                        'tag' => 'KZT',
                    ],
                    'value' => 5.3,
                ]
            ],
            [404, Codes::HTTP_NOT_FOUND, null],
        ];
    }

    /**
     * Тестирование получения списка курсов
     */
    public function testCgetAction()
    {
        $client = static::createClient();
        $client->request('GET', static::API_URL);

        $response = $client->getResponse();
        static::assertSame(Codes::HTTP_OK, $response->getStatusCode());
        $responseContent = json_decode($response->getContent(), true);
        foreach ($responseContent as $key => $rate) {
            static::assertNotFalse(strtotime($rate['created_at']));
            unset($responseContent[$key]['created_at']);
        }

        static::assertEquals(
            [
                [
                    'id' => 1,
                    'exchange_rates_source' => [
                        'id' => 1,
                        'title' => 'Центральный Банк Российской Федерации',
                        'receive_url' => 'http://www.cbr.ru/scripts/XML_daily.asp',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                        'base_currency' => [
                            'id' => 643,
                            'tag' => 'RUB',
                        ],
                    ],
                    'to_currency' => [
                        'id' => 398,
                        'tag' => 'KZT',
                    ],
                    'value' => 5.3,
                ],
                [
                    'id' => 2,
                    'exchange_rates_source' => [
                        'id' => 1,
                        'title' => 'Центральный Банк Российской Федерации',
                        'receive_url' => 'http://www.cbr.ru/scripts/XML_daily.asp',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                        'base_currency' => [
                            'id' => 643,
                            'tag' => 'RUB',
                        ],
                    ],
                    'to_currency' => [
                        'id' => 840,
                        'tag' => 'USD',
                    ],
                    'value' => 0.015,
                ],
                [
                    'id' => 3,
                    'exchange_rates_source' => [
                        'id' => 2,
                        'title' => 'Национальный Банк Казахстана',
                        'receive_url' => 'http://www.nationalbank.kz/rss/rates_all.xml',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ,
                        'base_currency' => [
                            'id' => 398,
                            'tag' => 'KZT',
                        ],
                    ],
                    'to_currency' => [
                        'id' => 643,
                        'tag' => 'RUB',
                    ],
                    'value' => 0.19,
                ],
                [
                    'id' => 4,
                    'exchange_rates_source' => [
                        'id' => 2,
                        'title' => 'Национальный Банк Казахстана',
                        'receive_url' => 'http://www.nationalbank.kz/rss/rates_all.xml',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ,
                        'base_currency' => [
                            'id' => 398,
                            'tag' => 'KZT',
                        ],
                    ],
                    'to_currency' => [
                        'id' => 840,
                        'tag' => 'USD',
                    ],
                    'value' => 0.0029,
                ],
            ],
            $responseContent
        );
    }

    /**
     * Тестирование добавления курса
     */
    public function testPostAction()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            static::API_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $response = $client->getResponse();
        static::assertSame(Codes::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    /**
     * Тестирование изменения курса
     */
    public function testPutAction()
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            static::API_URL . '/' . 1,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $response = $client->getResponse();
        static::assertSame(Codes::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    /**
     * Тестирование удаления курса
     */
    public function testDeleteAction()
    {
        $client = static::createClient();
        $client->request('DELETE', static::API_URL . '/' . 1);

        $response = $client->getResponse();
        static::assertSame(Codes::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }
}
