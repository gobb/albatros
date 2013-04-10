<?php
namespace ARIPD\SCMBundle\Form;
use ARIPD\SCMBundle\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('name')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => get_class(new Supplier()),
						'translation_domain' => 'ARIPDSCMBundle',
        ));
    }

    public function getName()
    {
        return 'aripd_scmbundle_suppliertype';
    }
}
