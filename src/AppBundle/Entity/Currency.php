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
 * Сущность валюты
 *
 * @ORM\Table(
 *  name="currencies",
 *  options={
 *      "comment"="Валюты"
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */
class Currency
{
    /**
     * ID валюты
     *
     * @ORM\Column(
     *  name="id",
     *  type="smallint",
     *  nullable=false,
     *  options={
     *      "comment"="ID валюты"
     *  }
     * )
     * @ORM\Id()
     *
     * @var int
     */
    protected $id;

    /**
     * Тэг валюты
     *
     * @ORM\Column(
     *  name="tag",
     *  type="string",
     *  length=3,
     *  nullable=false,
     *  options={
     *      "comment"="Тэг валюты",
     *      "fixed"=true
     *  }
     * )
     *
     * @var string
     */
    protected $tag;

    /**
     * Возвращает ID валюты
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Возвращает тэг валюты
     *
     * @return string
     */
    public function getTag()
    {
        return (string) $this->tag;
    }

    /**
     * Устанавливает тэг валюты
     *
     * @param string $tag Тэг валюты
     *
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = (string) $tag;

        return $this;
    }
}
