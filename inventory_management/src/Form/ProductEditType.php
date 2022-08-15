<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ProductEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Name'
                ]
            )
            ->add(
                'image',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Image'
                ]
            )
            ->add(
                'price',
                MoneyType::class,
                [
                    'required' => true,
                    'label' => "Price",
                    'currency' => 'USD'
                ]
            )
            ->add(
                'brand',
                EntityType::class,
                [
                    'required' => true,
                    'label' => 'Brand',
                    'class' => Brand::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => false
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'required' => true,
                    'label' => 'Category',
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}