<?php

namespace CanalTP\MediaManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Navitia\Component\Service\ServiceFacade;
use CanalTP\MediaManager\Category\CategoryType;

class MediaType extends AbstractType
{
    private $navitia;

    public function __construct(array $navitia)
    {
        $this->navitia = $navitia;
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
        foreach ($networks as $id => $name) {
            $builder->add(
                $id,
                'file',
                array('label' => $name, 'required' => true)
            );
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $service = ServiceFacade::getInstance();
        // $service->setConfiguration($this->navitia);
        // $result = $service->call($this->exampleNavitiaQuery());

        $networks = array(
                        'network:AAA' => 'AAA',
                        'network:BBB' => 'BBB',
                        'network:CCC' => 'CCC',
                        'network:DDD' => 'DDD',
                        'network:EEE' => 'EEE'
        );

        $this->initLogoField($builder);
        // if ($result->pagination->total_result > 0) {
            $this->initNetworkFields($builder, $networks);
        // }
        $this->initButtonSubmit($builder);
    }

    public function getName()
    {
        return 'media';
    }
}
