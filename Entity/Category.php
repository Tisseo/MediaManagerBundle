<?php

namespace CanalTP\MediaManagerBundle\Entity;

use CanalTP\MediaManager\Category\AbstractCategory;

class Category extends AbstractCategory
{
    private $type = null;

    public function __construct($id = 'Unknown', $type)
    {
        $this->id = $id;
        $this->name = 'Unknown';
        $this->ressourceId = 'Unknown';
        $this->type = $type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
}
