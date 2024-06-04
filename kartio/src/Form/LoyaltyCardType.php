<?php

namespace App\Form;

use App\Document\LoyaltyCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoyaltyCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, [
                'label' => 'Customer Name',
                'attr' => ['class' => 'grow']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'grow']
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
                'attr' => ['class' => 'grow']
            ])
            ->add('cardIdentifier', TextType::class, [
                'label' => 'Card Identifier',
                'attr' => ['class' => 'grow']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LoyaltyCard::class,
        ]);
    }
}
