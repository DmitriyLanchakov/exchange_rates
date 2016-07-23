<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadCurrencyData;
use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Тест REST API контроллера валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class CurrencyControllerTest extends WebTestCase
{
    const API_URL = '/api/currencies';

    /** {@inheritdoc} */
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([LoadCurrencyData::class]);
    }

    /**
     * Тестирование получения валюты
     *
     * @dataProvider testGetActionProvider
     *
     * @param integer $currencyId ID валюты
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     * @param string|null $expectedResponseContent Ожидаемое тело ответа
     */
    public function testGetAction($currencyId, $expectedResponseStatusCode, $expectedResponseContent = null)
    {
        $client = static::createClient();
        $client->request('GET', static::API_URL . '/' . $currencyId);

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
            [643, Codes::HTTP_OK, json_encode(['id' => 643, 'tag' => 'RUB'])],
            [404, Codes::HTTP_NOT_FOUND, null],
        ];
    }

    /**
     * Тестирование получения списка валют
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
                    ['id' => 643, 'tag' => 'RUB'],
                    ['id' => 398, 'tag' => 'KZT'],
                    ['id' => 840, 'tag' => 'USD'],
                ]
            ),
            $response->getContent()
        );
    }

    /**
     * Тестирование добавления валюты
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

        $client = static::createClient();
        $client->request('GET', static::API_URL . '/' . $content['id']);

        $response = $client->getResponse();
        static::assertSame(
            Codes::HTTP_CREATED === $expectedResponseStatusCode ? Codes::HTTP_OK : Codes::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
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
                ['id' => 999, 'tag' => 'TST'],
                Codes::HTTP_CREATED,
                ['id' => 999, 'tag' => 'TST']
            ],
            [
                ['id' => 999, 'tag' => 'wrong'],
                Codes::HTTP_BAD_REQUEST,
                [
                    'errors' => [
                        'form' => [
                            'children' => [
                                'id' => new \stdClass(),
                                'tag' => [
                                    'errors' => [
                                        'This value is too long. It should have 3 characters or less.',
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
     * Тестирование изменения валюты
     *
     * @dataProvider testPutActionProvider
     *
     * @param array $content Тело запроса
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     */
    public function testPutAction(array $content, $expectedResponseStatusCode)
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
            static::assertSame(json_encode($content), $response->getContent());
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
                ['id' => 643, 'tag' => 'UPD'],
                Codes::HTTP_OK,
            ],
            [
                ['id' => 404, 'tag' => 'UPD'],
                Codes::HTTP_NOT_FOUND,
            ],
        ];
    }

    /**
     * Тестирование удаления валюты
     *
     * @dataProvider testDeleteActionProvider
     *
     * @param integer $currencyId ID валюты
     * @param integer $expectedResponseStatusCode Ожидаемый код ответа
     */
    public function testDeleteAction($currencyId, $expectedResponseStatusCode)
    {
        $client = static::createClient();
        $client->request('DELETE', static::API_URL . '/' . $currencyId);

        $response = $client->getResponse();
        static::assertSame($expectedResponseStatusCode, $response->getStatusCode());

        if (Codes::HTTP_NO_CONTENT === $expectedResponseStatusCode) {
            static::assertSame('', $response->getContent());

            $client = static::createClient();
            $client->request('GET', static::API_URL . '/' . $currencyId);

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
                643,
                Codes::HTTP_NO_CONTENT,
            ],
            [
                404,
                Codes::HTTP_NOT_FOUND,
            ],
        ];
    }
}
