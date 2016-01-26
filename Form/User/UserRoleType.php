<?php
// src/Acme/TaskBundle/Form/Type/TagType.php
namespace Majes\CoreBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;


class UserRoleType extends AbstractType
{

	protected $em;
    protected $user;

	public function __construct(EntityManager $em, $user){
        $this->em = $em;
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$roles = $this->em->getRepository('MajesCoreBundle:User\Role')
            ->findBy(array('deleted' => false));

        $roles_array = array();$roles_has = array();
        $bundle = null;
        foreach($roles as $role){
            $roles_array[$role->getBundle()][$role->getId()] = $role->getName();
            $roles_has[$role->getBundle()][$role->getId()] = $this->user->hasRole($role->getId()) ? $role->getId() : false;
        }

        foreach ($roles_array as $bundle => $role) {

            $builder->add(empty($bundle) ? 'general' : $bundle , 'choice', array(
                'choices' => $role,
                'multiple' => true,
                'expanded' => true,
                'data' => $roles_has[$bundle]
                ));
        }
    }

    public function configureOptions(OptionsResolverInterface $resolver)
    {

    }

    public function getName()
    {
        return 'userroletype';
    }
}
