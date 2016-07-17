<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Курсы валют
 *
 * @ORM\Table(
 *  name="exchange_rates",
 *  options={
 *      "comment"="Курсы валют"
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExchangeRateRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class ExchangeRate
{
    /**
     * ID
     *
     * @ORM\Column(
     *  name="id",
     *  type="integer",
     *  nullable=false,
     *  options={
     *      "comment"="ID"
     *  }
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     *
     * @var int
     */
    protected $id;

    /**
     * Источник курса
     *
     * @ORM\ManyToOne(
     *  targetEntity="ExchangeRatesSource"
     * )
     * @ORM\JoinColumns(
     *  {
     *      @ORM\JoinColumn(
     *          name="exchange_rates_source_id",
     *          nullable=false,
     *          referencedColumnName="id"
     *      )
     *  }
     * )
     *
     * @var ExchangeRatesSource
     */
    protected $exchangeRatesSource;

    /**
     * Валюта
     *
     * @ORM\ManyToOne(
     *  targetEntity="Currency"
     * )
     * @ORM\JoinColumns(
     *  {
     *      @ORM\JoinColumn(
     *          name="to_currency_id",
     *          nullable=false,
     *          referencedColumnName="id"
     *      )
     *  }
     * )
     *
     * @var Currency
     */
    protected $toCurrency;

    /**
     * Возвращает время создания
     *
     * @ORM\Column(
     *  name="created_at",
     *  type="datetime",
     *  nullable=false
     * )
     *
     * @var \DateTime
     */
    protected $createdAt;

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
     * Возвращает источник курса
     *
     * @return ExchangeRatesSource
     */
    public function getExchangeRatesSource()
    {
        return $this->exchangeRatesSource;
    }

    /**
     * Устанавливает источник курса
     *
     * @param ExchangeRatesSource $exchangeRatesSource Источник курса
     *
     * @return $this
     */
    public function setExchangeRatesSource(ExchangeRatesSource $exchangeRatesSource)
    {
        $this->exchangeRatesSource = $exchangeRatesSource;

        return $this;
    }

    /**
     * Возвращает валюту
     *
     * @return Currency
     */
    public function getToCurrency()
    {
        return $this->toCurrency;
    }

    /**
     * Устанавливает валюту
     *
     * @param Currency $toCurrency Валюта
     *
     * @return $this
     */
    public function setToCurrency(Currency $toCurrency)
    {
        $this->toCurrency = $toCurrency;

        return $this;
    }

    /**
     * Возвращает время создания
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Устанавливает время создания
     *
     * @ORM\PrePersist()
     *
     * @return $this
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }
}
