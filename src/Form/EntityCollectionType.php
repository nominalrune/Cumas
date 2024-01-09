<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactEmail;
use App\Entity\ContactPhone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EntityCollectionType extends CollectionType
{
	public function getBlockPrefix(): string
    {
        return 'entity_collection';
    }
}
