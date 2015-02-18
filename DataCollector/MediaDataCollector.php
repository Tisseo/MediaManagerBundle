<?php

namespace CanalTP\MediaManagerBundle\DataCollector;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use CanalTP\MediaManager\Media\MediaInterface;
use CanalTP\MediaManager\Company\Company;
use CanalTP\MediaManager\Company\Configuration\Builder\ConfigurationBuilder;
use CanalTP\MediaManager\Media\Builder\MediaBuilder;
use CanalTP\MediaManager\Category\CategoryType;
use CanalTP\MediaManager\Category\CategoryInterface;
use CanalTP\MediaManagerBundle\Entity\Media;

class MediaDataCollector
{
    const FILE_CLASS = "Symfony\Component\HttpFoundation\File\File";
    const TMP_DIR = '/tmp/';

    private $company = null;
    private $mediaBuilder = null;
    private $configurations;

    public function __construct(Array $configurations)
    {
        $this->mediaBuilder = new MediaBuilder();
        $this->configurations= $configurations;
        $this->init();
    }

    private function saveMedia(Media $file, $path)
    {
        $media = $this->mediaBuilder->buildMedia(
            $path,
            $this->company,
            $file->getCategory()
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

    public function find(CategoryInterface $category, $fileName)
    {
        $media = $this->company->findMedia($category, $fileName);

        return ($media);
    }

    public function getPathByMedia(MediaInterface $media)
    {
        $media = $this->company->findMedia($media->getCategory(), $media->getFileName());

        return (empty($media) ? '' : $media->getPath());
    }

    public function getUrlByMedia(MediaInterface $media)
    {
        $media = $this->company->findMedia($media->getCategory(), $media->getFileName());

        if ($media == null) {
            throw new \Exception('File not found', 404);
        }
        if (!$media->getPath()) {
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
