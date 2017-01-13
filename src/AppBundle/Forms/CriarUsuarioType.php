<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{
    TextType, EmailType, PasswordType, SubmitType, ChoiceType
};

class CriarUsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class)
            ->add('email', EmailType::class)
            ->add('senha', PasswordType::class)
            ->add('salvar', SubmitType::class)
            ->add(
                'tipo',
                ChoiceType::class,
                [
                    'choices' => [
                        'Tipo' => '',
                        'Usuário' => 'ROLE_USER',
                        'Suporte' => 'ROLE_ADMIN',
                        'Administrador' => 'ROLE_SUPER_ADMIN'
                    ]
                ]
            );
    }
}