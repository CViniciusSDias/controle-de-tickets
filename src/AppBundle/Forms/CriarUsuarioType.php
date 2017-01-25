<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{
    TextType, EmailType, PasswordType, SubmitType, ChoiceType
};

class CriarUsuarioType extends EditarTipoUsuarioType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('nome', TextType::class)
            ->add('email', EmailType::class)
            ->add('senha', PasswordType::class);
    }
}
