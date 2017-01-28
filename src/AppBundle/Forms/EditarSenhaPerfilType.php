<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    PasswordType, RepeatedType, SubmitType
};
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formulário de edição de senha do usuário
 *
 * @author Vinicius Dias
 * @package AppBundle\Forms
 */
class EditarSenhaPerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('senhaAtual', PasswordType::class)
            ->add('novaSenha', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'As senhas devem ser coincidir',
                'required' => true,
                'first_options'  => ['label' => 'Digite sua nova senha'],
                'second_options' => ['label' => 'Repita sua nova senha'],
            ])
            ->add('salvar', SubmitType::class, ['label' => 'Salvar', 'attr' => ['class' => 'btn btn-primary']]);
    }
}