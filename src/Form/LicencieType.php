<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Licencie;
use App\Entity\Sport;
use App\Repository\EquipeRepository;
use App\Repository\SportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class LicencieType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getSportRepository(): SportRepository
    {
        return $this->entityManager->getRepository(Sport::class);
    }

    private function getEquipeRepository(): EquipeRepository
    {
        return $this->entityManager->getRepository(Equipe::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5'],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5'],
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


                'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full'],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
            ->add('dateEntree', DateType::class, [
                'label' => 'Date d\'entrée dans le club',
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
                'choice_attr' => function ($choice, $key, $value) {
                    // Récupère l'id du sport de l'équipe
                    $equipeSport = $this->getEquipeRepository()->find($value)->getSport();
                    // return le nom du sport
                    return ['data-sport' => $this->getSportRepository()->find($equipeSport)->getNom()];
                },

                'multiple' => true,
                'expanded' => true,
    
                'label' => 'Équipe',
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Licencie::class,
        ]);
    }
}
