<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShortUrlRepository")
 */
class ShortUrl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $shortUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @param $shortUrl
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param $date
     */
    public function setCreateDate($date)
    {
        $this->createDate = $date;
    }
}
