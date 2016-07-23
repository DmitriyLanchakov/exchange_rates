<?php

/**
 * Exchange Rates
 *
 * @author rtretyakov
 * @link https://github.com/rtretyakov/exchange_rates
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
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
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=3)
     *
     * @var string
     */
    protected $tag;

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
     * Устанавливает ID
     *
     * @param int $id ID
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
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
