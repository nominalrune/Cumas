<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Group;
use App\Entity\Inquiry;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
        ->add('title', TextType::class, [   'required' => true,] )
            ->add('status', ChoiceType::class,
                ['choices' => [
                    '未対応' => 0,
                    '返信済み' => 1,
                    '完了' => 2,
                ],])
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'name',
            ])
            ->add('notes', null, ["required" => false])
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
            ->add('messages', EntityCollectionType::class,
                [
                    'entry_type' => MessageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
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
