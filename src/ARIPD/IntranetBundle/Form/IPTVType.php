<?php

namespace ARIPD\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IPTVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('clipurl')
            ->add('netconnectionurl')
            ->add('width')
            ->add('height')
            ->add('playersrc')
            ->add('playerkey')
            ->add('influxisurl')
            ->add('controlsurl')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ARIPD\IntranetBundle\Entity\IPTV'
        ));
    }

    public function getName()
    {
        return 'aripd_intranetbundle_iptvtype';
    }
}
