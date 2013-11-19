<?php

namespace CanalTP\MediaManagerBundle\DataCollector;

use CanalTP\MediaManager\Company\Company;
use CanalTP\MediaManager\Company\Configuration\Builder\ConfigurationBuilder;
use CanalTP\MediaManager\Media\Builder\MediaBuilder;
use CanalTP\MediaManager\Category\Factory\CategoryFactory;
use CanalTP\IussaadBundle\Entity\Media;

class MediaDataCollector
{
    private $company = null;
    private $categoryFactory = null;
    private $mediaBuilder = null;
    // Configuration de la compagnie pour laquelle on stocke les médias.
    private $configurations;

    public function __construct(Array $configurations)
    {
        $this->mediaBuilder = new MediaBuilder();
        $this->categoryFactory = new CategoryFactory();
        $this->configurations= $configurations;
        $this->init();
    }

    private function initCategories($key)
    {
        list($parent, $current) = split(':::', $key);
        list($id, $name) = split('::', $current);

        $category = $this->categoryFactory->create($id);

        $category->setName($name);
        if ($parent != "") {
            list($id, $name) = split('::', $parent);
            $parentCategory = $this->categoryFactory->create($id);

            $parentCategory->setName($name);
            $category->setParent($parentCategory);
        }

        return ($category);
    }

    public function save($path, $key)
    {
        $category = $this->initCategories($key);

        $media = $this->mediaBuilder->buildMedia(
            $path,
            $this->company,
            $category
        );
        $media->setFileName($category->getName());

        return ($this->company->addMedia($media));
    }

    public function init()
    {
        $this->company = new Company();
        $configurationBuilder = new ConfigurationBuilder();

        $this->company->setName($this->configurations['name']);
        $this->company->setConfiguration(
            $configurationBuilder->buildConfiguration($this->configurations)
        );
    }

    /**
     * Retourne un tableau de chemin de médias
     * @param  $key
     * @return type
     */
    public function getPathByMedia(Media $media)
    {

        $category = $this->initCategories($media->getId());
        $medias = $this->company->getMediasByCategory($category);

        return (empty($medias) ? '' : $medias[0]->getPath());
    }

    /**
     * Return configurations of MediaManager
     * @return $configurations
     */
    public function getConfigurations()
    {
        return ($this->configurations);
    }
}
