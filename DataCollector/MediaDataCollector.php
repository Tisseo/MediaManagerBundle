<?php

namespace CanalTP\MediaManagerBundle\DataCollector;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
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
    private $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->mediaBuilder = new MediaBuilder();
        $this->categoryFactory = new CategoryFactory();
    }

    private function initCategories($key)
    {

        list($parent, $current) = split('::', $key);
        list($id, $name) = split(':', $current);

        $category = $this->categoryFactory->create($id);

        $category->setName($name);


        if ($parent != "")
        {
            list($id, $name) = split(':', $parent);
            $parentCategory = $this->categoryFactory->create($id);

            $parentCategory->setName($name);
            $category->setParent($parentCategory);
        }

        // if ($id == $this->parentCategory->getId()) {
        //     $category->setParent($this->parentCategory);
        // } else {
        //     $category->setParent($this->currentCategory);
        // }

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
        $company = $this->container->getParameter('config.company');

        $this->company->setName($company['name']);
        $this->company->setConfiguration(
            $configurationBuilder->buildConfiguration($company)
        );
    }

    public function saveFiles(
        $files
    )
    {
        foreach ($files as $file) {
            if ($file == null) {
                continue;
            }
            echo "<pre>" ; var_dump($file); echo "</pre>";

            $fileName = $file->getPath()->getClientOriginalName();
            $path = $this->container->getParameter('path.tmp') . $fileName;

            $file->getPath()->move($this->container->getParameter('path.tmp'), $fileName);
            if (!$this->save($path, $file->getId())) {
                throw new \Exception($path . ': Saving file fail.');
            }
        }
    }
}
