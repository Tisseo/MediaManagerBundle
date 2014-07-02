<?php

namespace CanalTP\MediaManagerBundle\DataCollector;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use CanalTP\MediaManager\Media\MediaInterface;
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
    const TMP_DIR = '/tmp/';

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
        $categories = array();

        array_unshift($categories, $category);
        while ($category->getParent()) {
            $category = $category->getParent();
            array_unshift($categories, $category);
        }

        $parentCategory = false;
        foreach ($categories as $category) {
            $current = $this->categoryFactory->create($category->getType());
            $current->setId($category->getId());
            $current->setName($category->getName());
            $current->setRessourceId($category->getRessourceId());
            if ($parentCategory) {
                $current->setParent($parentCategory);
            }
            $parentCategory = $current;
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

        if (!$this->company->addMedia($media)) {
            throw new \Exception($path . ': Saving file fail.');
        }
        return ($media);
    }

    public function save(Media $file)
    {
        $mediaManagerConfigs = $this->configurations;
        if (get_class($file->getFile()) == MediaDataCollector::FILE_CLASS) {
            $fileName = $file->getFile()->getFilename();
        } else {
            $fileName = $file->getFile()->getClientOriginalName();
        }
        $path = MediaDataCollector::TMP_DIR . $fileName;

        $file->getFile()->move(
            MediaDataCollector::TMP_DIR,
            $fileName
        );
        return ($this->saveMedia($file, $path));
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

    public function find(Category $category, $fileName)
    {
        $category = $this->initCategories($category);
        $media = $this->company->findMedia($category, $fileName);

        return ($media);
    }

    public function getPathByMedia(MediaInterface $media)
    {
        $category = $this->initCategories($media->getCategory());
        $media = $this->company->findMedia($category, $media->getFileName());

        return (empty($media) ? '' : $media->getPath());
    }

    public function getUrlByMedia(MediaInterface $media)
    {
        if (empty($media->getPath())) {
            $path = null;
        } else {
            $path = $this->configurations['storage']['url'];
            $path .= substr($media->getPath(), strlen($this->configurations['storage']['path']));
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
