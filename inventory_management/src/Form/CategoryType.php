<?php


namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', ChoiceType::class,
        [
            'required' => true,
            'label' => 'Title',
            'choices' =>[
            // "Shirt", "Dress", "Hoodies", "T-Shirt"
            'Shirt' => 'Shirt',
            'Dress' => 'Dress',
            'Hoodies' => 'Hoodies',
            'T-Shirt' => 'T-Shirt',
        ],
            'multiple' => false,
            'expanded' => false
        ])
        ->add('image', TextType::class,
        [
            'required' => true,
            'label' => 'Image',
            'attr' => [
                'maxlength' => 255
                ]
                
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
