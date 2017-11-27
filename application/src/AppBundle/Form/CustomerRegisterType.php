<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerRegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, array(
                    'error_bubbling' => true
                ))
                ->add('lastname', TextType::class, array(
                    'error_bubbling' => true
                ))
                ->add('nickname', TextType::class, array(
                    'error_bubbling' => true
                ))
                ->add('society', TextType::class)
                ->add('address', TextType::class)
                ->add('zipcode',TextType::class)
                ->add('city', TextType::class)
                ->add('birthdate', BirthdayType::class, array(
                    'required' => false,
                    'attr' => array('class' => 'datepicker'),
                    'widget' => 'single_text',
                    'required' => false,
                    'format' => 'dd/MM/yyyy',
                    'html5' => false
                ))
                ->add('user', UserRegisterType::class, array(
                    'error_bubbling' => true
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer',
            //'cascade_validation' => true, //Symfony 2
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }
}
