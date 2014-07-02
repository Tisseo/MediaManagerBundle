<?php

namespace CanalTP\MediaManagerBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use CanalTP\MediaManagerBundle\DataCollector\MediaDataCollector;
use CanalTP\MediaManager\Media\AbstractMedia;

class Media extends AbstractMedia
{
    private $id;
    private $url;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    private $label;

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
}
