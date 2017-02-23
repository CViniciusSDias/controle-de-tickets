<?php
namespace AppBundle\Forms;

use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsuarioRepository;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\{
    IntegerType, TextType, SubmitType
};

/**
 * Formulário de gestão do ticket
 *
 * @author Vinicius Dias
 * @package AppBundle\Forms
 */
class GerenciarTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prioridade', IntegerType::class)
            ->add('resposta', TextType::class)
            ->add('atendenteResponsavel', EntityType::class, [
                'class' => Usuario::class,
                'placeholder' => 'Selecione',
                'query_builder' => function (UsuarioRepository $repo) {
                    $query = $repo->createQueryBuilder('u');
                    $query->where('u.tipo IN(\'ROLE_ADMIN\', \'ROLE_SUPERVISOR\', \'ROLE_SUPER_ADMIN\')');

                    return $query;
                }
            ])
            ->add('salvar', SubmitType::class, ['label' => 'Salvar']);
    }
}
