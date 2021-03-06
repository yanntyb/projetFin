<?php

namespace App\Entity;

use App\Traits\Entity;

class MessageJoin
{
    use Entity;

    private ?int $idUser1;
    private ?int $idUser2;
    private ?string $text;

     /**
     * MessageJoin constructor.
     * @param int|null $idUser1
     * @param int|null $idUser2
     * @param string|null $text
     */
    public function __construct(int $id= null,int $idUser1 = null, int $idUser2 = null, string $text = null)
    {
        $this->id = $id;
        $this->idUser1 = $idUser1;
        $this->idUser2 = $idUser2;
        $this->text = $text;
    }

    /**
     * @return int|null
     */
    public function getIdUser1(): ?int
    {
        return $this->idUser1;
    }

    /**
     * @param int|null $idUser1
     * @return $this
     */
    public function setIdUser1(?int $idUser1): MessageJoin
    {
        $this->idUser1 = $idUser1;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUser2(): ?int
    {
        return $this->idUser2;
    }

    /**
     * @param int|null $idUser2
     * @return $this
     */
    public function setIdUser2(?int $idUser2): MessageJoin
    {
        $this->idUser2 = $idUser2;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return $this
     */
    public function setText(?string $text): MessageJoin
    {
        $this->text = $text;
        return $this;
    }
}