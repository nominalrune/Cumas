<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'name',
            ])
            ->add('email')
            ->add('notes', TextareaType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => ContactEmail::class,
        ]);
    }
}
