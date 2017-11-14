<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class CustomerType extends AbstractType
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
                ->add('birthdate', BirthdayType::class)
                ->add('user', UserRegisterType::class, array(
                    //'constraints' => array(new Valid()),
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
