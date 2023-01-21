<?php

namespace App\Form;

use App\Entity\Access;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      
      $builder
            ->add('name', TextType::class)
            ->add('permissions', ChoiceType::class, [
                'choices' => [
                    'Read' => 'read',
                    'Write' => 'write',
                    'Delete' => 'delete'
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'username'
            ])
            ->add('group', EntitiesType::class,[
                'class' => Group::class,
                'choice_label' => 'name'
            ])
        ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Access::class,
        ]);
    }
}
