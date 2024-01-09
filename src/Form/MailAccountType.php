<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\MailAccount;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name')
            ->add('popServer')
            ->add('popPort')
            ->add('smtpServer')
            ->add('smtpPort')
            ->add('username')
            ->add('password', PasswordType::class, [
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
            ->add('group_', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add("group", GroupType::class,[
            'required' => false,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => MailAccount::class,
        ]);
    }
}
