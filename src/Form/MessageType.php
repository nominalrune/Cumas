<?php

namespace App\Form;

use App\Entity\ContactEmail;
use App\Entity\ContactPhone;
use App\Entity\Inquiry;
use App\Entity\Message;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('file')
            ->add('messageId')
            ->add('referenceId')
            ->add('subject')
            ->add('inquiry', EntityType::class, [
                'class' => Inquiry::class,
                'choice_label' => 'id',
            ])
            ->add('mail', EntityType::class, [
                'class' => ContactEmail::class,
                'choice_label' => 'id',
            ])
            ->add('phone', EntityType::class, [
                'class' => ContactPhone::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
