<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{SubmitType, ChoiceType};

class EditarTipoUsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            )
            ->add('salvar', SubmitType::class);
    }
}
