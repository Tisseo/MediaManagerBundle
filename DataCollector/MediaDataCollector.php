<?php

namespace CanalTP\MediaManagerBundle\DataCollector;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use CanalTP\MediaManager\Company\Company;
use CanalTP\MediaManager\Company\Configuration\Builder\ConfigurationBuilder;
use CanalTP\MediaManager\Media\Builder\MediaBuilder;
use CanalTP\MediaManager\Category\Factory\CategoryFactory;
use CanalTP\MediaManager\Category\CategoryType;
use CanalTP\MediaManagerBundle\Entity\Category;
use CanalTP\MediaManagerBundle\Entity\Media;

class MediaDataCollector
{
    const FILE_CLASS = "Symfony\Component\HttpFoundation\File\File";

    private $company = null;
    private $categoryFactory = null;
    private $mediaBuilder = null;
    private $configurations;

    public function __construct(Array $configurations)
    {
        $this->mediaBuilder = new MediaBuilder();
        $this->categoryFactory = new CategoryFactory();
        $this->configurations= $configurations;
        $this->init();
    }

    private function initCategories($category)
    {
        $current = $this->categoryFactory->create($category->getType());

        while ($category) {
            $current->setId($category->getId());
            $current->setName($category->getName());
            $current->setRessourceId($category->getRessourceId());
            $currentParent = $current;

            if (!($category = $category->getParent())) {
                break;
            }
            $current = $this->categoryFactory->create($category->getType());
            $current->setParent($currentParent);
        }

        return ($current);
    }

    private function saveMedia(Media $file, $path)
    {
        $category = $this->initCategories($file->getCategory());
        $media = $this->mediaBuilder->buildMedia(
            $path,
            $this->company,
            $category
        );
        $media->setFileName($file->getFileName());

        return ($this->company->addMedia($media));
    }

    public function save(Media $file)
    {
        $mediaManagerConfigs = $this->configurations;
        if (get_class($file->getFile()) == MediaDataCollector::FILE_CLASS) {
            $fileName = $file->getFile()->getFilename();
        } else {
            $fileName = $file->getFile()->getClientOriginalName();
        }
        $path = $mediaManagerConfigs['storage']['path'] . $fileName;

        $file->getFile()->move(
            $mediaManagerConfigs['storage']['path'],
            $fileName
        );
        if (!$this->saveMedia($file, $path)) {
            throw new \Exception($path . ': Saving file fail.');
        }
    }

    private function init()
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
        $category = $this->initCategories($media->getCategory());
        $media = $this->company->findMedia($category, $media->getFileName());

        return (empty($media) ? '' : $media->getPath());
    }

    public function getUrlByMedia(Media $media)
    {
        $mediaPath = $this->getPathByMedia($media);
        if (empty($mediaPath)) {
            $path = null;
        } else {
            $path = $this->configurations['storage']['url'];
            $path .= substr($mediaPath, strlen($this->configurations['storage']['path']));
        }

        return ($path);
    }

    /**
     * Return configurations of MediaManager
     * @return $configurations
     */
    public function getConfigurations()
    {
        return ($this->configurations);
    }

    /**
     * Return company of MediaManager
     * @return $configurations
     */
    public function getCompany()
    {
        return ($this->company);
    }
}
