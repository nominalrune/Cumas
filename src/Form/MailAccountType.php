<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\MailAccount;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('host')
            ->add('port')
            ->add('username')
            ->add('password')
            ->add('group_', EntityType::class, [
                'class' => Group::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MailAccount::class,
        ]);
    }
}
