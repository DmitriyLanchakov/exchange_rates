<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadCurrencyData;
use AppBundle\DataFixtures\ORM\LoadExchangeRatesSourceData;
use AppBundle\DBAL\Types\ExchangeRatesSourceReceiveHandlerType;
use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Тест REST API контроллера источников курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRatesSourceControllerTest extends WebTestCase
{
    const API_URL = '/api/exchangeratessources';

    /** {@inheritdoc} */
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([LoadCurrencyData::class, LoadExchangeRatesSourceData::class]);
    }

    /**
     * Тестирование получения источника
     *
     * @dataProvider testGetActionProvider
     *
     * @param integer $sourceId ID источника
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     * @param string|null $expectedResponseContent Ожидаемое тело ответа
     */
    public function testGetAction($sourceId, $expectedResponseStatusCode, $expectedResponseContent = null)
    {
        $client = static::createClient();
        $client->request('GET', static::API_URL . '/' . $sourceId);

        $response = $client->getResponse();
        static::assertSame($expectedResponseStatusCode, $response->getStatusCode());
        if (null !== $expectedResponseContent) {
            static::assertSame($expectedResponseContent, $response->getContent());
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
                json_encode(
                    [
                        'id' => 1,
                        'title' => 'Центральный Банк Российской Федерации',
                        'receive_url' => 'http://test.local/api',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                        'base_currency' => [
                            'id' => 643,
                            'tag' => 'RUB',
                        ],
                    ]
                )
            ],
            [404, Codes::HTTP_NOT_FOUND, null],
        ];
    }

    /**
     * Тестирование получения списка источников
     */
    public function testCgetAction()
    {
        $client = static::createClient();
        $client->request('GET', static::API_URL);

        $response = $client->getResponse();
        static::assertSame(Codes::HTTP_OK, $response->getStatusCode());
        static::assertSame(
            json_encode(
                [
                    [
                        'id' => 1,
                        'title' => 'Центральный Банк Российской Федерации',
                        'receive_url' => 'http://test.local/api',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                        'base_currency' => [
                            'id' => 643,
                            'tag' => 'RUB',
                        ],
                    ],
                    [
                        'id' => 2,
                        'title' => 'Национальный Банк Казахстана',
                        'receive_url' => 'http://test.local/api',
                        'receive_handler' => ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ,
                        'base_currency' => [
                            'id' => 398,
                            'tag' => 'KZT',
                        ],
                    ]
                ]
            ),
            $response->getContent()
        );
    }

    /**
     * Тестирование добавления источника
     *
     * @dataProvider testPostActionProvider
     *
     * @param array $content Тело запроса
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     * @param array $expectedResponseContent Ожидаемое тело ответа
     */
    public function testPostAction(array $content, $expectedResponseStatusCode, array $expectedResponseContent)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            static::API_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );

        $response = $client->getResponse();
        static::assertSame($expectedResponseStatusCode, $response->getStatusCode());
        static::assertSame(
            json_encode($expectedResponseContent),
            $response->getContent()
        );

        if (Codes::HTTP_CREATED === $expectedResponseStatusCode) {
            $client = static::createClient();
            $client->request('GET', static::API_URL . '/3');

            $response = $client->getResponse();
            static::assertSame(
                Codes::HTTP_CREATED === $expectedResponseStatusCode ? Codes::HTTP_OK : Codes::HTTP_NOT_FOUND,
                $response->getStatusCode()
            );
        }
    }

    /**
     * DataProvider для testPostAction
     *
     * @return array
     */
    public function testPostActionProvider()
    {
        return [
            [
                [
                    'title' => 'Тестовый Банк',
                    'receive_url' => 'http://test.local/api',
                    'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                    'base_currency' => 643,
                ],
                Codes::HTTP_CREATED,
                [
                    'id' => 3,
                    'title' => 'Тестовый Банк',
                    'receive_url' => 'http://test.local/api',
                    'receive_handler' => ExchangeRatesSourceReceiveHandlerType::CBR,
                    'base_currency' => [
                        'id' => 643,
                        'tag' => 'RUB',
                    ],
                ],
            ],
            [
                [
                    'title' => 'Тестовый Банк',
                    'receive_url' => 'http://test.local/api',
                    'receive_handler' => 'wrong',
                    'base_currency' => 'wrong',
                ],
                Codes::HTTP_BAD_REQUEST,
                [
                    'errors' => [
                        'form' => [
                            'children' => [
                                'title' => new \stdClass(),
                                'receiveUrl' => new \stdClass(),
                                'receiveHandler' => [
                                    'errors' => [
                                        'This value is not valid.',
                                    ],
                                ],
                                'baseCurrency' => [
                                    'errors' => [
                                        'This value is not valid.',
                                    ],
                                ],
                            ],
                        ],
                        'errors' => [],
                    ]
                ]
            ]
        ];
    }

    /**
     * Тестирование изменения источника
     *
     * @dataProvider testPutActionProvider
     *
     * @param array $content Тело запроса
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     * @param array $expectedResponseContent Ожидаемое тело ответа
     */
    public function testPutAction(array $content, $expectedResponseStatusCode, $expectedResponseContent = null)
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            static::API_URL . '/' . $content['id'],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );

        $response = $client->getResponse();
        static::assertSame($expectedResponseStatusCode, $response->getStatusCode());
        if (Codes::HTTP_OK === $expectedResponseStatusCode) {
            static::assertSame(json_encode($expectedResponseContent), $response->getContent());
        }

        if (Codes::HTTP_OK === $expectedResponseStatusCode) {
            $client = static::createClient();
            $client->request('GET', static::API_URL . '/' . $content['id']);

            $response = $client->getResponse();
            static::assertSame(Codes::HTTP_OK, $response->getStatusCode());
        }
    }

    /**
     * DataProvider для testPutAction
     *
     * @return array
     */
    public function testPutActionProvider()
    {
        return [
            [
                [
                    'id' => 1,
                    'title' => 'Updated Банк Российской Федерации',
                    'receive_url' => 'http://updated.local/api',
                    'receive_handler' => ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ,
                    'base_currency' => 398,
                ],
                Codes::HTTP_OK,
                [
                    'id' => 1,
                    'title' => 'Updated Банк Российской Федерации',
                    'receive_url' => 'http://updated.local/api',
                    'receive_handler' => ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ,
                    'base_currency' => [
                        'id' => 398,
                        'tag' => 'KZT',
                    ],
                ],
            ],
            [
                [
                    'id' => 404,
                    'title' => 'Updated Банк Российской Федерации',
                    'receive_url' => 'http://updated.local/api',
                    'receive_handler' => ExchangeRatesSourceReceiveHandlerType::NATIONAL_BANK_KZ,
                    'base_currency' => 398,
                ],
                Codes::HTTP_NOT_FOUND,
            ],
        ];
    }

    /**
     * Тестирование удаления источника
     *
     * @dataProvider testDeleteActionProvider
     *
     * @param integer $sourceId ID источника
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     */
    public function testDeleteAction($sourceId, $expectedResponseStatusCode)
    {
        $client = static::createClient();
        $client->request('DELETE', static::API_URL . '/' . $sourceId);

        $response = $client->getResponse();
        static::assertSame($expectedResponseStatusCode, $response->getStatusCode());

        if (Codes::HTTP_NO_CONTENT === $expectedResponseStatusCode) {
            static::assertSame('', $response->getContent());

            $client = static::createClient();
            $client->request('GET', static::API_URL . '/' . $sourceId);

            $response = $client->getResponse();
            static::assertSame(Codes::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

    /**
     * DataProvider для testDeleteAction
     *
     * @return array
     */
    public function testDeleteActionProvider()
    {
        return [
            [
                1,
                Codes::HTTP_NO_CONTENT,
            ],
            [
                404,
                Codes::HTTP_NOT_FOUND,
            ],
        ];
    }
}
