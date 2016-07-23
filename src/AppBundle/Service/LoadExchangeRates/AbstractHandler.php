<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Service\LoadExchangeRates;

use AppBundle\Entity\ExchangeRatesSource;
use AppBundle\Manager\CurrencyManager;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Абстрактный класс хэндлеров для получения курсов валют
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
abstract class AbstractHandler
{
    /**
     * Источник курсов валют
     *
     * @var ExchangeRatesSource
     */
    protected $exchangeRatesSource;

    /**
     * Менеджер валют
     *
     * @var CurrencyManager
     */
    protected $currencyManager;

    /**
     * HTTP-клиент
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * @param ExchangeRatesSource $exchangeRatesSource Источник курсов валют
     * @param CurrencyManager $currencyManager Менеджер валют
     * @param LoggerInterface $logger Логгер
     */
    public function __construct(
        ExchangeRatesSource $exchangeRatesSource,
        CurrencyManager $currencyManager,
        LoggerInterface $logger
    ) {
        $this->exchangeRatesSource = $exchangeRatesSource;
        $this->currencyManager = $currencyManager;
        $this->logger = $logger;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request)
    {
        $this->logger->info(
            sprintf('Request %s', static::class),
            [
                'url' => $request->getUri()->getPath(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
            ]
        );

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->send($request);

        $this->logger->info(
            sprintf('Response %s', static::class),
            [
                'status_code' => $response->getStatusCode(),
                'body' => (string) $response->getBody(),
            ]
        );

        return $response;
    }

    /**
     * Возвращает HTTP-клиент
     *
     * @return Client
     */
    protected function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }
}
