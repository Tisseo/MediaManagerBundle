<?php

namespace CanalTP\MediaManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Media extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('logo', 'file', array('required' => true));
        $builder->add('button', 'submit', array('label' => 'form.add.submit', 'translation_domain' => 'CanalTPMediaManagerBundle'));
    }

    public function getName()
    {
        return 'media';
    }
}