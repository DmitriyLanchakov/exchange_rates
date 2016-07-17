<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\DBAL\Types\ExchangeRatesSourceReceiveHandler;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Сущность источника курсов валют
 *
 * @ORM\Table(
 *  name="exchange_rates_sources",
 *  options={
 *      "comment"="Источники курсов валют"
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExchangeRatesSource")
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRatesSource
{
    /**
     * ID
     *
     * @ORM\Column(
     *  name="id",
     *  type="integer",
     *  nullable=false,
     *  options={
     *      "comment"="ID записи"
     *  }
     * )
     * @ORM\Id
     *
     * @var int
     */
    protected $id;

    /**
     * Название
     *
     * @ORM\Column(
     *  name="title",
     *  type="string",
     *  options={
     *      "comment"="Название"
     *  }
     * )
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(
     *  name="receive_url",
     *  type="string",
     *  options={
     *      "comment"="URL для получения курсов"
     *  }
     * )
     *
     * @var string
     */
    protected $receiveUrl;

    /**
     * Хэндлер получения курсов
     *
     * @ORM\Column(
     *  name="receive_handler",
     *  type="ExchangeRatesSourceReceiveHandler",
     *  nullable=false,
     *  options={
     *      "comment"="Хэндлер получения курсов"
     *  }
     * )
     * @DoctrineAssert\Enum(entity="ExchangeRatesSourceReceiveHandler")
     *
     * @var string
     */
    protected $receiveHandler;

    /**
     * Базовая валюта
     *
     * @ORM\ManyToOne(
     *  targetEntity="Currency"
     * )
     * @ORM\JoinColumns(
     *  {
     *      @ORM\JoinColumn(
     *          name="base_currency_id",
     *          referencedColumnName="id"
     *      )
     *  }
     * )
     *
     * @var Currency
     */
    protected $baseCurrency;

    /**
     * Возвращает ID
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Возвращает название
     *
     * @return string
     */
    public function getTitle()
    {
        return (string) $this->title;
    }

    /**
     * Устанавливает название
     *
     * @param string $title Название
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Возвращает URL для получения курсов
     *
     * @return string
     */
    public function getReceiveUrl()
    {
        return $this->receiveUrl;
    }

    /**
     * Устанавливает URL для получения курсов
     *
     * @param string $receiveUrl URL для получения курсов
     *
     * @return $this
     */
    public function setReceiveUrl($receiveUrl)
    {
        $this->receiveUrl = (string) $receiveUrl;

        return $this;
    }

    /**
     * Возвращает хэндлер получения курсов
     *
     * @return string
     */
    public function getReceiveHandler()
    {
        return $this->receiveHandler;
    }

    /**
     * Устанавливает хэндлер получения курсов
     *
     * @param string $receiveHandler Хэндлер получения курсов
     *
     * @return $this
     */
    public function setReceiveHandler($receiveHandler)
    {
        $this->receiveHandler = (string) $receiveHandler;

        return $this;
    }

    /**
     * Возвращает базовую валюту
     *
     * @return Currency
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }

    /**
     * Устанавливает базовую валюту
     *
     * @param Currency $baseCurrency Базовая валюта
     *
     * @return $this
     */
    public function setBaseCurrency(Currency $baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;

        return $this;
    }
}
