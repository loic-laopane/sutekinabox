<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname')
            ->add('lastname')
            ->add('email')
/*
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Marketing' => 'ROLE_MARKETING',
                    'Achat' => 'ROLE_ACHAT',
                    'Fournisseur' => 'ROLE_SUPPLIER',
                    'Admin' => 'ROLE_SUPERADMIN'
                ),
                'multiple' => true,
                'expanded' => true

            ))
*/
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
