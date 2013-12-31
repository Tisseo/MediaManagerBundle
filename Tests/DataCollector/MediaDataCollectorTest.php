<?php

namespace CanalTP\MediaManagerBundle\Tests\MediaDataCollector;

use CanalTP\MediaManagerBundle\DataCollector\MediaDataCollector;
use CanalTP\IussaadCoreBundle\Entity\Media;

class MediaControllerTest extends \PHPUnit_Framework_TestCase
{
    private $mediaDataCollector = null;
    private $configuration = null;

    protected function setUp()
    {
        $this->configuration = array(
            'name' => 'CanalTP',
            'storage' => array(
                'type' => 'filesystem',
                'path' => '/tmp/MediaManagerBundleTest/',
                'url' => 'http://localhost/uploads/MediaManagerTest/'
            ),
            'strategy' => 'navitia'
        );
        $this->mediaDataCollector = new MediaDataCollector($this->configuration);
    }

    public function testSave()
    {
        $path = __DIR__.'/../data/canaltp_logo.jpg';

        $this->mediaDataCollector->save($path, 'network___network:CHO____line___line:CHI:10');
        $this->assertFileExists('/tmp/MediaManagerBundleTest/sims/0/lines/line:CHI:10.jpg');
    }

    public function testGetConfiguration()
    {
        $this->assertEquals($this->configuration, $this->mediaDataCollector->getConfigurations());
    }

    public function testGetCompany()
    {
        $this->assertNotNull($this->mediaDataCollector->getCompany());
    }

    public function testGetPathByMedia()
    {
        $media = new Media();

        $media->setId('network___network:CHO____line___line:CHI:10');
        $this->assertEquals('/tmp/MediaManagerBundleTest/sims/0/lines/line:CHI:10.jpg', $this->mediaDataCollector->getPathByMedia($media));
    }

    protected function tearDown()
    {
    }
}
