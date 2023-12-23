<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Group;
use App\Entity\Inquiry;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('notes')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category', EntityType::class, [
                'class' => Category::class,
'choice_label' => 'id',
            ])
            ->add('department', EntityType::class, [
                'class' => Group::class,
'choice_label' => 'id',
            ])
            ->add('agent', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inquiry::class,
        ]);
    }
}
