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
 * Хэндлер для получения курсов Центрального Банка Российской Федерации
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class Cbr extends AbstractHandler implements HandlerInterface
{
    /** {@inheritdoc} */
    public function getRates()
    {
        $request = new Request('GET', $this->exchangeRatesSource->getReceiveUrl());

        $response = $this->sendRequest($request);

        $result = $this->parseResponse($response);

        return $result;
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

            $currencies = $this->currencyManager->findAllIndexedById();

            if (!in_array($responseXml, [false, ''], true)) {
                $rates = [];

                foreach ($responseXml->children() as $rateInfo) {
                    if (isset($rateInfo->NumCode, $rateInfo->Value)
                        && array_key_exists((int) $rateInfo->NumCode, $currencies)
                        && false !== (
                            $value = numfmt_parse(
                                new \NumberFormatter('ru', \NumberFormatter::DECIMAL),
                                (string) $rateInfo->Value
                            )
                        )
                        && false !== ($scale = filter_var((string) $rateInfo->Nominal, FILTER_VALIDATE_FLOAT))
                    ) {
                        $rates[] = (new ExchangeRate())
                            ->setExchangeRatesSource($this->exchangeRatesSource)
                            ->setToCurrency($currencies[(int) $rateInfo->NumCode])
                            ->setValue($scale / $value);
                    }
                }

                return $rates;
            }
        }

        throw new \RuntimeException('Wrong response');
    }
}
