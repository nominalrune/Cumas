<?php

namespace App\Form;

use App\Entity\ContactItem;
use App\Entity\Inquiry;
use App\Entity\Message;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('file', TextType::class, [
                'required' => false,
            ])
            ->add('messageId', TextType::class, [
                'required' => false,
            ])
            ->add('referenceId', TextType::class, [
                'required' => false,
            ])
            ->add('subject', TextType::class, [
                'required' => true,
            ])
            ->add('inquiry', EntityType::class, [
                'class' => Inquiry::class,
                'choice_label' => 'id',
            ])
            ->add('contact', EntityType::class, [
                'class' => ContactItem::class,
                'choice_label' => 'id',
            ])
            ->add('senderType',ChoiceType::class,
            ['choices' => [
                '相手' => 0,
                '弊社' => 1,
            ]
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
