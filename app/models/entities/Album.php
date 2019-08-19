<?php

namespace JSYF\App\Models\Entities;

/**
 * Сущность "Альбом"
 */
class Album
{
    private $id;

    private $name;

    private $user;

    public function __construct($id = null, $name = null, $user = null)
    {
        $this->id = $id;
        $this->name = $name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}