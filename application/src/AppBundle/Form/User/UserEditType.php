<?php

namespace AppBundle\Form\User;

use AppBundle\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
                ->add('email')
                ->add('center', EntityType::class, array(
                    'required' => false,
                    'class' => Center::class,
                    'placeholder' => '-- Choose a center --',
                    'choice_label' => function(Center $center)
                    {
                        return 'N°'. $center->getCode() . ' - ' . $center->getName();
                    }
                ))
                ->add('active')
                ->add('roles', ChoiceType::class, array(
                    'choices' => array(
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_STAFF' => 'ROLE_STAFF'
                    ),
                    'multiple' => true,
                    'expanded' => true
                ));
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
