<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('mainImage', FileType::class, [
                'data_class' => null,
                'required' => false,
                'label' => false,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ])
            ->add('youtubeLink')
            ->add('githubLink')
            ->add('programmingLanguages')
            ->add('technologiesUsed')
            ->add('imageFiles', CollectionType::class, [
                'data_class' => null,
                'entry_type' => FileType::class,
                'allow_add' => true,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hidden'
                ]
            ])
            ->add('deleteImages', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hidden'
                ]
            ])
            ->add('deleteMainImage', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hidden'
                ]
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_project';
    }


}
