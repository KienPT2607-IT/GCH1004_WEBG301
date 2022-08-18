<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                ChoiceType::class,
                [
                    'required' => true,
                    'label' => 'Name',
                    'choices' => [
                        // "VietNam", "China", "Japan", "USA"
                        'VietNam' => "VietNam",
                        'China' => "China",
                        'Japan' => "Japan",
                        'USA' => "USA",
                    ],
                    'multiple' => false,
                    'expanded' => false
                ]
            )
            ->add(
                'image',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Image',
                    'attr' => [
                        'maxlength' => 255
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
