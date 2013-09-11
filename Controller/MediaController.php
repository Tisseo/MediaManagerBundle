<?php

namespace CanalTP\MediaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CanalTP\MediaManagerBundle\Form\Type\Media;
use CanalTP\MediaManager\Company\Company;
use CanalTP\MediaManager\Company\Configuration\Builder\ConfigurationBuilder;
use CanalTP\MediaManager\Media\Builder\MediaBuilder;
use CanalTP\MediaManager\Category\Factory\CategoryFactory;
use CanalTP\MediaManager\Category\CategoryType;

class MediaController extends Controller
{
    private function exampleParams()
    {
        $params = array(
            'company' => array(
            'storage' => array(
                'type' => 'filesystem',
                'path' => '/tmp/my_storage/',
            ),
            'strategy' => 'default'
            )
        );

        return ($params);
    }

    private function getCategory($name)
    {
        $categoryFactory = new CategoryFactory();
        $categoryType = null;

        switch ($name) {
            case 'logo':
                $categoryType = CategoryType::LOGO;
                break;            
            case 'line':
                $categoryType = CategoryType::LINE;
                break;
            case 'network':
                $categoryType = CategoryType::NETWORK;
                break;
        }
        return ($categoryFactory->create($categoryType));
    }

    private function saveFile($file, $category)
    {
        $company = new Company();
        $configurationBuilder = new ConfigurationBuilder();
        $mediaBuilder = new MediaBuilder();

        $category->setName('My_Logo');
        $company->setName("My_Company");

        $company->setConfiguration(
            $configurationBuilder->buildConfiguration($this->exampleParams())
        );

        $media = $mediaBuilder->buildMedia($file, $company, $category);

        $company->addMedia($media);
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);

        if ($form->isValid())
        {

            $files = $form->getData();
            foreach($files as $key => $file)
            {
                $path = "/tmp/TEST/" . $file->getClientOriginalName();
                $file->move("/tmp/TEST/", $file->getClientOriginalName());

                $this->saveFile($path, $this->getCategory('logo'));
            }
        }
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(new Media());
        $this->processForm($request, $form);

        return $this->render(
            'CanalTPMediaManagerBundle:Media:add.html.twig',
            array('form' => $form->createView())
        );
    }
}
