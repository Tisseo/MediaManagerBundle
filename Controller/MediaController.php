<?php

namespace CanalTP\MediaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Navitia\Component\Service\ServiceFacade;
use CanalTP\MediaManagerBundle\Form\Type\MediaType;
use CanalTP\MediaManager\Company\Company;
use CanalTP\MediaManager\Company\Configuration\Builder\ConfigurationBuilder;
use CanalTP\MediaManager\Media\Builder\MediaBuilder;
use CanalTP\MediaManager\Category\Factory\CategoryFactory;
use CanalTP\MediaManager\Category\CategoryType;

class MediaController extends Controller
{
    private $company = null;

    private function exampleNavitiaQuery()
    {
        $query = array(
            'api' => 'coverage',
            'parameters' => array(
                'region' => 'PaysDeLaLoire',
                'action' => 'networks'
            )
        );

        return ($query);
    }

    private function getCategory($type)
    {
        $categoryFactory = new CategoryFactory();

        return ($categoryFactory->create($type));
    }

    private function save($file, $key, $mediaBuilder)
    {
        list($fileName, $categoryType) = split('-', $key);

        $media = $mediaBuilder->buildMedia(
            $file,
            $this->company,
            $this->getCategory($categoryType)
        );
        $media->setFileName($fileName);

        return ($this->company->addMedia($media));
    }

    private function saveFiles($files)
    {
        $mediaBuilder = new MediaBuilder();

        foreach ($files as $key => $file) {
            $fileName = $file->getClientOriginalName();
            $path = $this->container->getParameter('path.tmp') . $fileName;

            $file->move($this->container->getParameter('path.tmp'), $fileName);
            if ($this->save($path, $key, $mediaBuilder)) {
                $this->get('session')->getFlashBag()->add('notice', $fileName);
            } else {
                $this->get('session')->getFlashBag()->add('error', $fileName);
            }
        }
    }

    private function processForm(Request $request, $form)
    {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->saveFiles($form->getData());

            return $this->redirect(
                $this->generateUrl('canal_tp_media_manager_all_media')
            );
        }
        return $this->render(
            'CanalTPMediaManagerBundle:Media:add.html.twig',
            array('form' => $form->createView())
        );
    }

    private function initCompanySettings()
    {
        $this->company = new Company();
        $configurationBuilder = new ConfigurationBuilder();
        $company = $this->container->getParameter('config.company');

        $this->company->setName($company['name']);
        $this->company->setConfiguration(
            $configurationBuilder->buildConfiguration($company)
        );
    }

    public function addAction(Request $request)
    {
        $this->initCompanySettings();
        $form = $this->createForm(
            new MediaType(
                $this->container->getParameter('config.navitia')
            )
        );
        $render = $this->processForm($request, $form);

        return ($render);
    }

    public function listAction(Request $request)
    {
        $service = ServiceFacade::getInstance();
        $service->setConfiguration(
            $this->container->getParameter('config.navitia')
        );
        $result = $service->call($this->exampleNavitiaQuery());
        $medias = array();

        return $this->render(
            'CanalTPMediaManagerBundle:Media:list.html.twig',
            array('medias' => $medias)
        );
    }
}
