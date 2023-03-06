<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Licencie;
use App\Entity\Sport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg hover:ring-rose-500 focus:border-rose-500 block w-full p-2.5'],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
        ])
        ->add('imageUrl', FileType::class, [
            'label' => 'Image', 
            'mapped' => false,
            'required' => false, 
            'constraints' => [
                new File([
                    'maxSize' => '5000k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Image trop lourde',
                ])
            ],
           

            'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg hover:ring-rose-500 focus:border-rose-500 block w-full'],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
        ])
        ->add('sport', EntityType::class, [
            'class' => Sport::class,
            'choice_label' => 'nom',

            'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'],
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
        ])
        
        ->add('licencies', EntityType::class, [
            'class' => Licencie::class,
            'choice_label' => function (Licencie $licencie) {
                return $licencie->getPrenom() . ' ' . $licencie->getNom();},
            'by_reference' => false,

            'multiple' => true,
            'expanded' => true,

            'label' => 'Licencié',
            'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
