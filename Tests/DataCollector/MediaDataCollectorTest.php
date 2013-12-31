<?php

namespace CanalTP\MediaManagerBundle\Tests\MediaDataCollector;

use CanalTP\MediaManager\Registry;
use CanalTP\MediaManagerBundle\DataCollector\MediaDataCollector;
use CanalTP\IussaadCoreBundle\Entity\Media;

class MediaControllerTest extends \PHPUnit_Framework_TestCase
{
    private $mediaManager = null;
    private $configuration = null;
    private $file_path = null;

    protected function setUp()
    {
        $this->configuration = array(
            'name' => 'CanalTPTest',
            'storage' => array(
                'type' => 'filesystem',
                'path' => '/tmp/MediaManagerBundleTest/',
                'url' => 'http://localhost/uploads/MediaManagerTest/'
            ),
            'strategy' => 'navitia'
        );
        $this->mediaManager = new MediaDataCollector($this->configuration);
    }

    public function testSave()
    {
        $path = Registry::get('/') . Registry::get('MEDIA_PATH_SRC');

        $this->mediaManager->save($path, Registry::get('MEDIA_KEY'));
        $this->assertFileExists(Registry::get('MEDIA_PATH_DEST'));

        rename(Registry::get('MEDIA_PATH_DEST'), $path);
    }

    public function testGetConfiguration()
    {
        $this->assertEquals(
            $this->configuration,
            $this->mediaManager->getConfigurations()
        );
    }

    public function testGetCompany()
    {
        $this->assertNotNull($this->mediaManager->getCompany());
    }

    public function testGetPathByMedia()
    {
        $path = Registry::get('/') . Registry::get('MEDIA_PATH_SRC');
        $media = new Media();

        rename($path, Registry::get('MEDIA_PATH_DEST'));
        $media->setId(Registry::get('MEDIA_KEY'));
        $this->assertEquals(
            Registry::get('MEDIA_PATH_DEST'),
            $this->mediaManager->getPathByMedia($media)
        );
        rename(Registry::get('MEDIA_PATH_DEST'), $path);
    }
}
