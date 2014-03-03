<?php

namespace CanalTP\MediaManagerBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use CanalTP\MediaManagerBundle\DataCollector\MediaDataCollector;

class Media
{
    private $id;
    private $path;
    private $url;
    private $category;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    private $label;
    private $fileName;

    public function __construct()
    {
        $this->id = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }
    
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }
}
