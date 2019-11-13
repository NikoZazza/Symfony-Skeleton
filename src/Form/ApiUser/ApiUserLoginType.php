<?php

namespace App\Form\ApiUser;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiUserLoginType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email', EmailType::class, ['required' => true, 'documentation' => ['type' => 'string', 'description' => "Email of user"]])
            ->add('password', PasswordType::class, ['required' => true, 'documentation' => ['type' => 'string', 'description' => "Password of user"]]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['csrf_protection' => false]);
    }
}