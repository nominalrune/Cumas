<?php

namespace App\Form;

use App\Entity\ContactItem;
use App\Entity\Inquiry;
use App\Entity\MailAccount;
use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewEmailType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options) : void
	{
		$builder
			->add('From', EntityType::class, [
				'label' => '送信者',
				'class' => MailAccount::class,
				'choice_label' => 'name',
			])
			->add('To', EntityType::class, [
				'label' => '宛先',
				'class' => ContactItem::class,
				'choice_label' => 'value',
			])
			->add('subject', null, [
				'label' => '件名',
				'required' => true,
				'max' => 255,
			])
			->add('body', TextareaType::class, [
				'required' => true,
			])
			->add('attachments', FileType::class, [
				'label' => '添付ファイル',
				'multiple' => true,
				'mapped' => false,
				'required' => false,
			])
			->add('send', SubmitType::class, [
				'label' => '送信',
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver) : void
	{
		$resolver->setDefaults([
			// FIXME
		]);
	}
}
