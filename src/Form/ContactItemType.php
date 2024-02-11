<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Choice;

class ContactItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'email' => 'email',
                    'phone' => 'phone',
                ],
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'required' => true,
                // 'default'=>"メインのメールアドレス",
            ])
            ->add('value')
            ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => ContactItem::class,
        ]);
    }
}
