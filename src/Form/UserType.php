<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',null, ['label'=>'user.labels.username'])
            // // ->add('roles')
            // ->add('password')
            ->add('nom',null, ['label'=>'user.labels.nom'])
            ->add('prenom',null, ['label'=>'user.labels.prenom'])
            ->add('email',null, ['label'=>'user.labels.email'])
            ->add('submit', SubmitType::class, ['label'=>'user.labels.submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
