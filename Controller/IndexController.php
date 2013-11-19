<?php

namespace Majes\CoreBundle\Controller;

use Majes\CoreBundle\Controller\SystemController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\Common\Annotations\AnnotationReader;

use Majes\CoreBundle\Conversion\DataTableConverter;
use Majes\CoreBundle\Entity\Language;
use Majes\CoreBundle\Entity\User\User;
use Majes\CoreBundle\Entity\User\Role;
use Majes\MediaBundle\Entity\Media;

use Majes\CoreBundle\Form\User\Myaccount;
use Majes\CoreBundle\Form\User\UserType;
use Majes\CoreBundle\Form\Language\LanguageType;
use Majes\CoreBundle\Form\User\UserRoleType;
use Majes\CoreBundle\Form\User\RoleType;
use Majes\CoreBundle\Utils\GoogleAnalytics;

class IndexController extends Controller implements SystemController
{
    /**
     * @Secure(roles="ROLE_ADMIN")
     *
     */
    public function dashboardAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        $google_params = $this->container->getParameter('google');
        $google = new GoogleAnalytics($google_params);
        
        return $this->render('MajesCoreBundle:Index:dashboard.html.twig', array(
            'analytics' => $google->_analytics));
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     *
     */
    public function myaccountAction(){

        $request = $this->getRequest();

        $form = $this->createForm(new Myaccount($request->getSession()), $this->_user);

        if($request->getMethod() == 'POST'){
            $_current_user = $this->_user;
            $_current_password = $_current_user->getPassword();

            $form->handleRequest($request);
            if ($form->isValid()) {

                $password = $this->_user->getPassword();
                if(empty($password)){
                    $this->_user->setPassword($_current_password);
                }else{
                    $this->_user->setPassword(sha1($password));
                }
                $this->_user->setUsername($this->_user->getEmail());

                $em = $this->getDoctrine()->getManager();

                /*Media*/
                $media = $this->_user->getMedia();
                $file = $form['media']->getData();
                if(!is_null($file)){
                    if(is_null($media)){
                        $media = new Media();
                        $media->setUser($this->_user);
                        $media->setFolder('User');
                        $media->setType('picture');
                        $media->setTitle('Avatar - '.$this->_user->getFirstname());
                        $media->setAuthor($this->_user->getFirstname().' '.$this->_user->getLastname());
                    }

                    $media->setFile($file);

                    $em->persist($media);
                    $em->flush();

                    $this->_user->setMedia($media);
                }
                
                $em->persist($this->_user);
                $em->flush();

            }else{
                foreach ($form->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
            }
        }

        return $this->render('MajesCoreBundle:Index:myaccount.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languagesAction(){

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $this->_langs,
            'object' => new Language(),
            'pageTitle' => 'Languages',
            'pageSubTitle' => 'List off all languages currently available',
            'urls' => array(
                'add' => '_admin_language_edit',
                'edit' => '_admin_language_edit',
                'delete' => '_admin_language_delete'
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languageEditAction($id){

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository('MajesCoreBundle:Language')
            ->findOneById($id);


        $form = $this->createForm(new LanguageType($request->getSession()), $language);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if ($form->isValid()) {

                if(is_null($language)) $language = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($language);
                $em->flush();

                return $this->redirect($this->get('router')->generate('_admin_language_edit', array('id' => $language->getId())));

            }else{
                foreach ($form->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
            }
        }

        $pageSubTitle = empty($language) ? 'Add a new language' : 'Edit language ' . $language->getName();

        return $this->render('MajesCoreBundle:Index:language-edit.html.twig', array(
            'pageTitle' => 'Languages',
            'pageSubTitle' => $pageSubTitle,
            'language' => $language,
            'form' => $form->createView()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languageDeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository('MajesCoreBundle:Language')
            ->findOneById($id);

        if(!is_null($language)){
            $em->remove($language);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_languages', array()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function usersAction(){

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('MajesCoreBundle:User\User')
            ->findAll();

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $users,
            'object' => new User(),
            'pageTitle' => 'Users',
            'pageSubTitle' => 'List off all users currently "created"',
            'urls' => array(
                'add' => '_admin_user_edit',
                'edit' => '_admin_user_edit',
                'delete' => '_admin_user_delete'
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function userEditAction($id){

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MajesCoreBundle:User\User')
            ->findOneById($id);

        $form = $this->createForm(new UserType($request->getSession()), $user);
        
        if($request->getMethod() == 'POST'){
            if(!is_null($user)){
                $_current_password = $user->getPassword();
            }
                        
            $form->handleRequest($request);
            if ($form->isValid()) {

                if(is_null($user)){
                    $user = $form->getData();
                    $user->setCreateDate(new \DateTime(date('Y-m-d H:i:s')));
                }

                $password = $user->getPassword();

                if(empty($password) && isset($_current_password)){
                    $user->setPassword($_current_password);
                }else{
                    $factory = $this->container->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $pwd = $encoder->encodePassword($password, $user->getSalt());
                    
                    $user->setPassword($pwd);
                    //$user->setPassword(sha1($pwd));
                }

                $user->setUsername($user->getEmail());

                $em = $this->getDoctrine()->getManager();
                
                /*Media*/
                $media = $user->getMedia();
                $file = $form['media']->getData();
                if(!is_null($file)){
                    if(is_null($media)){
                        $media = new Media();
                        $media->setUser($user);
                        $media->setFolder('User');
                        $media->setType('picture');
                        $media->setTitle('Avatar - '.$user->getFirstname());
                        $media->setAuthor($user->getFirstname().' '.$user->getLastname());
                    }

                    $media->setFile($file);

                    $em->persist($media);
                    $em->flush();

                    $user->setMedia($media);
                }
                

                $em->persist($user);
                $em->flush();

                return $this->redirect($this->get('router')->generate('_admin_user_edit', array('id' => $user->getId())));

            }else{
                foreach ($form->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
            }
        }

        $pageSubTitle = empty($user) ? 'Add a new user' : 'Edit user ' . $user->getUsername();

        $form_role = null;
        if(!is_null($id))
            $form_role = $this->createForm(
                new UserRoleType($this->getDoctrine()->getManager(), $user),
                null,
                array('action' => $this->get('router')->generate('_admin_user_role', array('id' => $id)))
            )->createView();

        return $this->render('MajesCoreBundle:Index:user-edit.html.twig', array(
            'pageTitle' => 'Users',
            'pageSubTitle' => $pageSubTitle,
            'user' => $user,
            'form' => $form->createView(),
            'form_role' => $form_role));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function userActivityAction($id){

        $_results_per_page = 10; 

        $request = $this->getRequest();

        $type = $request->get('type');
        $page = $request->get('page');
        $loadmore = false;

        if(is_null($type)) $type = 'week';
        if(is_null($page)) $page = 1;

        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('MajesCoreBundle:Log')
            ->getActivity($id, $type, $page, $_results_per_page);

        $loadmore = count($activities) > $_results_per_page ? true : false;
        count($activities) > $_results_per_page ? array_pop($activities) : $activities;


        if($request->isXmlHttpRequest()){
            return $this->render('MajesCoreBundle:Index:ajax/user-activity.html.twig', array(
                'activities' => $activities,
                'loadmore' => $loadmore,
                'page' => $page,
                'id' => $id
                ));
        }else
            return $this->render('MajesCoreBundle:Index:user-activity.html.twig', array(
                'activities' => $activities,
                'loadmore' => $loadmore,
                'page' => $page,
                'id' => $id
                ));
       

    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function userRoleAction($id){

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MajesCoreBundle:User\User')
            ->findOneById($id);

        $form_role = $this->createForm(
            new UserRoleType($this->getDoctrine()->getManager(), $user),
            null,
            array('action' => $this->get('router')->generate('_admin_user_role', array('id' => $id)))
        );

        if($request->getMethod() == 'POST'){
            $form_role->handleRequest($request);
            if ($form_role->isValid()) {

                $roles = $form_role->getData();
                $user->removeRoles();

                foreach ($roles as $bundle => $role_array) {
                    foreach($role_array as $role_id)
                    {
                        $role = $em->getRepository('MajesCoreBundle:User\Role')
                            ->findOneById($role_id);

                        $user->addRole($role);
                    }
                }

                $em = $this->getDoctrine()->getManager();

                $em->persist($user);
                $em->flush();

            }else{
                foreach ($form_role->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
       
            }
        }

        return $this->redirect($this->get('router')->generate('_admin_user_edit', array('id' => $user->getId())));

    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function userDeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MajesCoreBundle:User\User')
            ->findOneById($id);

        if(!is_null($user)){
            $em->remove($user);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_users', array()));
    }


    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function rolesAction(){
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('MajesCoreBundle:User\Role')
            ->findAll();

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $roles,
            'object' => new Role(),
            'pageTitle' => 'Roles',
            'pageSubTitle' => 'List off all roles currently available',
            'urls' => array(
                'add' => '_admin_role_edit',
                'edit' => '_admin_role_edit',
                'delete' => '_admin_role_delete'
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function roleEditAction($id){

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('MajesCoreBundle:User\Role')
            ->findOneById($id);


        $form = $this->createForm(new RoleType(), $role);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if ($form->isValid()) {

                if(is_null($role)) $role = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($role);
                $em->flush();

                return $this->redirect($this->get('router')->generate('_admin_role_edit', array('id' => $role->getId())));

            }else{
                foreach ($form->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
            }
        }

        $pageSubTitle = empty($role) ? 'Add a new role' : 'Edit role ' . $role->getRole();

        return $this->render('MajesCoreBundle:Index:role-edit.html.twig', array(
            'pageTitle' => 'Roles',
            'pageSubTitle' => $pageSubTitle,
            'form' => $form->createView()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function roleDeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('MajesCoreBundle:User\Role')
            ->findOneById($id);

        if(!is_null($role)){
            $em->remove($role);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_roles', array()));
    }
}
