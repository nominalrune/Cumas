<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactEmail;
use App\Entity\ContactPhone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name')
            ->add('notes')
            ->add('emails', ContactEmailType::class, [
                'required' => false,
            ])
            ->add('phones', EntityType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
