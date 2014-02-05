<?php

namespace CanalTP\MediaManagerBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use CanalTP\MediaManagerBundle\DataCollector\MediaDataCollector;

class Media
{
    private $id;
    private $path;
    private $url;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    private $label;

    public function __construct(
        $parentType = null,
        $parentId = null,
        $childrenType = '',
        $childrenId = ''
    )
    {
        $this->id = '';

        if ($parentType != null) {
            $this->id = $parentType . MediaDataCollector::CATEGORY_SEP . $parentId;
        }
        $this->id .= MediaDataCollector::PARENT_CATEGORY_SEP . $childrenType . MediaDataCollector::CATEGORY_SEP . $childrenId;
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

}
