<?php

namespace AppBundle\Form\Customer;

use AppBundle\Form\User\UserRegisterType;
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
        $builder->add('firstname', TextType::class)
                ->add('lastname', TextType::class)
                ->add('nickname', TextType::class)
                ->add('society', TextType::class, array('required' => false))
                ->add('address', TextType::class, array('required' => false))
                ->add('zipcode',TextType::class, array('required' => false))
                ->add('city', TextType::class, array('required' => false))
                ->add('birthdate', BirthdayType::class, array(
                    'required' => false,
                    'attr' => array('class' => 'datepicker'),
                    'widget' => 'single_text',
                    'required' => false,
                    'format' => 'dd/MM/yyyy',
                    'html5' => false
                ))
                ->add('user', UserRegisterType::class);
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
        return null;
    }
}
