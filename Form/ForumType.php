<?php

namespace SocietoPlugin\Societo\ForumPlugin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityManager;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', 'text')
            ->add('body')
            ->add('redirect_to', 'hidden', array('property_path' => false))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        $options = parent::getDefaultOptions($options);

        $options['data_class'] = 'SocietoPlugin\Societo\ForumPlugin\Entity\Forum';

        return $options;
    }

    public function getName()
    {
        return 'societo_forum_forum';
    }
}
