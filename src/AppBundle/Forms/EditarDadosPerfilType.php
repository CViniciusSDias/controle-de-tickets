<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    EmailType, SubmitType, TextType
};
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formulário de edição dos dados do usuário como nome e e-mail
 *
 * @author Vinicius Dias
 * @package AppBundle\Forms
 */
class EditarDadosPerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('nome', TextType::class)
            ->add('email', EmailType::class)
            ->add('salvar', SubmitType::class, ['label' => 'Salvar', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
