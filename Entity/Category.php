<?php

namespace CanalTP\MediaManagerBundle\Entity;

class Category
{
    private $id = null;
    private $name = null;
    private $type = null;
    private $parent = null;
    private $ressourceId = null;

    public function __construct($id = 'Unknown', $type)
    {
        $this->id = $id;
        $this->name = 'Unknown';
        $this->ressourceId = 'Unknown';
        $this->type = $type;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
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

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setRessourceId($ressourceId)
    {
        $this->ressourceId = $ressourceId;

        return $this;
    }

    public function getRessourceId()
    {
        return $this->ressourceId;
    }
}
