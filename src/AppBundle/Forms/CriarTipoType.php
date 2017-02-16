<?php
namespace AppBundle\Forms;

use AppBundle\Entity\Usuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType};

/**
 * Formulário de criação de uma nova categoria
 *
 * @author Vinicius Dias
 * @package AppBundle\Forms
 */
class CriarTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class)
            ->add('supervisorResponsavel', EntityType::class, [
                'class' => Usuario::class,
                'query_builder' => function (EntityRepository $repo) {
                    $query = $repo->createQueryBuilder('u');
                    $query->where('u.tipo = \'ROLE_SUPERVISOR\' OR u.tipo = \'ROLE_SUPER_ADMIN\'');

                    return $query;
                },
                'placeholder' => 'Selecione'
            ])
            ->add('salvar', SubmitType::class, ['label' => 'Salvar']);
    }
}
