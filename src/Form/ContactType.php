<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Votre nom',
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-white'],
                'row_attr' => ['class' => 'mb-6'],

                'required' => true,
                'attr' => [
                    'class' => 'shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5',
                    'placeholder' => 'John DOE'
                ]
            ])
            ->add('email',EmailType::class, [
                'label' => 'Votre email',
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-white'],
                'row_attr' => ['class' => 'mb-6'],

                'required' => true,
                'attr' => [
                    'class' => 'shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5',
                    'placeholder' => 'nom@exemple.com'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-white'],
                'row_attr' => ['class' => 'mb-6'],

                'required' => true,
                'attr' => [
                    'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg shadow-sm border border-gray-300 focus:ring-red-500 focus:border-red-500',
                    'placeholder' => 'Laisser un commentaire...',
                    'rows' => 6
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
