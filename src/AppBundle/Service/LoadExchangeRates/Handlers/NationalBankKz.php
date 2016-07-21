<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Service\LoadExchangeRates\Handlers;

use AppBundle\Entity\ExchangeRate;
use AppBundle\Service\LoadExchangeRates\Handler as HandlerInterface;
use AppBundle\Service\LoadExchangeRates\AbstractHandler;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Хэндлер для получения курсов Национального Банка Казахстана
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class NationalBankKz extends AbstractHandler implements HandlerInterface
{
    /** {@inheritdoc} */
    public function getRates()
    {
        $request = new Request('GET', $this->exchangeRatesSource->getReceiveUrl());

        $response = $this->sendRequest($request);

        return $this->parseResponse($response);
    }

    /**
     * Парсит ответ сервера и возвращает курсы
     *
     * @param ResponseInterface $response Ответ
     *
     * @return ExchangeRate[]
     *
     * @throws \RuntimeException
     */
    public function parseResponse(ResponseInterface $response)
    {
        if (200 === $response->getStatusCode()) {
            $entityLoaderState = libxml_disable_entity_loader(true);
            $responseXml = simplexml_load_string((string) $response->getBody());
            libxml_disable_entity_loader($entityLoaderState);

            $currencies = $this->currencyManager->findAllIndexedByTag();

            if (!in_array($responseXml, [false, ''], true)) {
                $rates = [];

                foreach ($responseXml->children()->channel->children()->item as $rateInfo) {
                    if (isset($rateInfo->title, $rateInfo->description, $rateInfo->quant)
                        && array_key_exists((string) $rateInfo->title, $currencies)
                        && false !== ($value = filter_var((string) $rateInfo->description, FILTER_VALIDATE_FLOAT))
                        && false !== ($scale = filter_var((string) $rateInfo->quant, FILTER_VALIDATE_FLOAT))
                    ) {
                        $rates[] = (new ExchangeRate())
                            ->setExchangeRatesSource($this->exchangeRatesSource)
                            ->setToCurrency($currencies[(string) $rateInfo->title])
                            ->setValue($scale / $value);
                    }
                }

                return $rates;
            }
        }

        throw new \RuntimeException('Wrong response');
    }
}
