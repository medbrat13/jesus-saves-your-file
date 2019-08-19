<?php

namespace JSYF\App\Models\Entities;

/**
 * Сущность "Пользователь"
 */
class User
{
    private $id;

    private $user;

    public function __construct($id = null, $user = null)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

}