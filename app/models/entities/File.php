<?php

namespace JSYF\App\Models\Entities;

/**
 * Сущность "Файл"
 */
class File
{
    private $id;

    private $name;

    private $date;

    private $size;

    private $resolution;

    private $duration;

    private $comment;

    private $path;

    private $previewPath;

    private $ext;

    private $user;

    public function __construct($id = null, $name = null, $date = null, $size = null, $resolution = null, $duration = null, $path = null, $previewPath = null, $ext = null, $user = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->size = $size;
        $this->resolution = $resolution;
        $this->duration = $duration;
        $this->path = $path;
        $this->previewPath = $previewPath;
        $this->ext = $ext;
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
     * @param mixed $id
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
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getPreviewPath()
    {
        return $this->previewPath;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param null $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}