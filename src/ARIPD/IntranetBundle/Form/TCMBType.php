<?php

namespace ARIPD\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TCMBType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('code')
            ->add('forexbuying')
            ->add('forexselling')
            ->add('banknotebuying')
            ->add('banknoteselling')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ARIPD\IntranetBundle\Entity\TCMB'
        ));
    }

    public function getName()
    {
        return 'aripd_intranetbundle_tcmbtype';
    }
}
