<?php

namespace CanalTP\MediaManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Navitia\Component\Service\ServiceFacade;
use CanalTP\MediaManager\Category\CategoryType;

class MediaType extends AbstractType
{
    private function exampleNavitiaConfig()
    {
        $config = array(
            'url' => 'http://navitia2-ws.ctp.dev.canaltp.fr',
            'format' => 'object'
        );

        return ($config);
    }

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

    private function initLogoField(FormBuilderInterface $builder)
    {
        $builder->add(
            'logo-' . CategoryType::LOGO,
            'file',
            array('label' => 'logo', 'required' => true)
        );
    }

    private function initButtonSubmit(FormBuilderInterface $builder)
    {
        $builder->add(
            'button',
            'submit',
            array(
                'label' => 'form.add.submit',
                'translation_domain' => 'CanalTPMediaManagerBundle'
            )
        );
    }

    private function initNetworkFields(
        FormBuilderInterface $builder,
        array $networks
        )
    {
        foreach ($networks as $network) {
            $builder->add(
                $network->id . '-' . CategoryType::NETWORK,
                'file',
                array('label' => $network->name, 'required' => true)
            );
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $service = ServiceFacade::getInstance();
        $service->setConfiguration($this->exampleNavitiaConfig());
        $result = $service->call($this->exampleNavitiaQuery());

        $this->initLogoField($builder);
        if ($result->pagination->total_result > 0)
        {
            $this->initNetworkFields($builder, $result->networks);
        }
        $this->initButtonSubmit($builder);
    }

    public function getName()
    {
        return 'media';
    }
}
