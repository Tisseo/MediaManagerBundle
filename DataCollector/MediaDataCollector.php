<?php

namespace CanalTP\MediaManagerBundle\DataCollector;

use CanalTP\MediaManager\Company\Company;
use CanalTP\MediaManager\Company\Configuration\Builder\ConfigurationBuilder;
use CanalTP\MediaManager\Media\Builder\MediaBuilder;
use CanalTP\MediaManager\Category\Factory\CategoryFactory;
use CanalTP\MediaManager\Category\CategoryType;

class MediaDataCollector
{
    private $company = null;
    private $categoryFactory = null;
    private $mediaBuilder = null;
    // chemin vers dossier temporaire (dossier de stockage des médias)
    private $path;
    // Configuration de la compagnie pour laquelle on stocke les médias.
    private $configCompany;


    public function __construct($path, $configCompany)
    {
        $this->mediaBuilder = new MediaBuilder();
        $this->categoryFactory = new CategoryFactory();
        $this->path = $path;
        $this->configCompany= $configCompany;
        $this->init();
    }

    private function initCategories($key)
    {
        list($parent, $current) = split(':::', $key);
        list($id, $name) = split('::', $current);

        $category = $this->categoryFactory->create($id);

        $category->setName($name);
        if ($parent != "")
        {
            list($id, $name) = split('::', $parent);
            $parentCategory = $this->categoryFactory->create($id);

            $parentCategory->setName($name);
            $category->setParent($parentCategory);
        }
        return ($category);
    }

    private function save($file, $key)
    {

        $category = $this->initCategories($key);

        $media = $this->mediaBuilder->buildMedia(
            $file,
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

        $this->company->setName($this->configCompany['name']);
        $this->company->setConfiguration(
            $configurationBuilder->buildConfiguration($this->configCompany)
        );
    }

    public function saveFiles($files)
    {               
        foreach ($files as $file) {
            if ($file->getPath() == null) {
                continue;
            }
            $fileName = $file->getPath()->getClientOriginalName();
            $path = $this->path . $fileName;

            $file->getPath()->move($this->path, $fileName);
            if (!$this->save($path, $file->getId())) {
                throw new \Exception($path . ': Saving file fail.');
            }
        }
    }
}
