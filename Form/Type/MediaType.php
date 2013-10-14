<?php

namespace CanalTP\MediaManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Navitia\Component\Service\ServiceFacade;

class MediaType extends AbstractType
{
    private $navitia;

    public function __construct(array $navitia, $require)
    {
        $this->navitia = $navitia;
        $this->require = $require;
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
            'logo:logo',
            'file',
            array('label' => 'logo', 'required' => $this->require)
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
                $network->id,
                'file',
                array('label' => $network->name, 'required' => $this->require)
            );
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $service = ServiceFacade::getInstance();
        $service->setConfiguration($this->navitia);
        $result = $service->call($this->exampleNavitiaQuery());

        $this->initLogoField($builder);
        if ($result->pagination->total_result > 0) {
            $this->initNetworkFields($builder, $result->networks);
        }
        $this->initButtonSubmit($builder);
    }

    public function getName()
    {
        return 'media';
    }
}
