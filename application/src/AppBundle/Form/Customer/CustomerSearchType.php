<?php

namespace AppBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numero', TextType::class, array('required' => false, 'mapped' => false));
        $builder->add('nickname', TextType::class, array('required' => false, 'mapped' => false, 'attr' => ['class' => 'autocomplete']));
        $builder->add('lastname', TextType::class, array('required' => false, 'mapped' => false));
        $builder->add('firstname', TextType::class, array('required' => false, 'mapped' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'AppBundle\Entity\Card'
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
