<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Group;
use App\Entity\Inquiry;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('status', ChoiceType::class,
            ['choices'  => [
                'Maybe' => null,
                'Yes' => true,
                'No' => false,
            ],])
            ->add('notes', null,["required"=>false])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('department', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
            ])
            ->add('agent', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Inquiry::class,
        ]);
    }
}
