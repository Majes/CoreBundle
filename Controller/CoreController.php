<?php

namespace Majes\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;

use Majes\CmsBundle\Entity\Host;
use Majes\CmsBundle\Entity\Route;

class CoreController extends Controller
{
    public function installAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */

        //Check permissions

        //Check database
        $conn = mysql_connect($this->container->getParameter('database_host'), 
            $this->container->getParameter('database_user'), 
            $this->container->getParameter('database_password')) or die(mysql_error()); 
        $dbExists = mysql_select_db($this->container->getParameter('database_name'), 
            $conn); 

        $val = mysql_query('select 1 from `user`');

        if(!is_dir(__DIR__ . '/../../../../../../web/media'))
            mkdir(__DIR__ . '/../../../../../../web/media', 0775);

        $permissions['dir_cache'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../app/cache')), -4);
        $permissions['dir_var'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../app/var')), -4);
        $permissions['dir_bundles'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../web/bundles')), -4);
        $permissions['dir_media'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../web/media')), -4);
        $permissions['dir_log'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../app/logs')), -4);

        $permission_status = ($permissions['dir_cache'] >= '0775' && 
            $permissions['dir_var'] >= '0775' && 
            $permissions['dir_bundles'] >= '0775' && 
            $permissions['dir_media'] >= '0775' && 
            $permissions['dir_log'] >= '0775') ? true : false;

        if($permission_status && $val)
            return $this->redirect($this->get('router')->generate('_admin_index'));

        return $this->render('MajesCoreBundle:Core:install.html.twig', array(
            'auth' => true, 'db_exists' => $dbExists, 'permissions' => $permissions, 'permission_status' => $permission_status));
    }

    public function installDbAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        $request = $this->getRequest();

        $conn = mysql_connect($this->container->getParameter('database_host'), 
            $this->container->getParameter('database_user'), 
            $this->container->getParameter('database_password')) or die(mysql_error()); 
        $dbExists = mysql_select_db($this->container->getParameter('database_name'), 
            $conn); 

        if(!$dbExists)
            return $this->redirect($this->get('router')->generate('_majes_install'));

        $val = mysql_query('select 1 from `user`');

        if($val)
            return $this->redirect($this->get('router')->generate('_admin_index'));

        if($request->getMethod() == 'POST'){

            $email = $request->get('email');
            $password = $request->get('password');
            $url = $request->get('url');

            if(empty($url) || empty($email) || empty($password))
                return $this->redirect($this->get('router')->generate('_majes_install_db'));

            $query="";
    
            $sql=file(__DIR__ . '/../../../../../../app/var/db/db-mysql.sql'); // on charge le fichier SQL
            foreach($sql as $l){ // on le lit
                if (substr(trim($l),0,2)!="--"){ // suppression des commentaires
                    $query .= $l;
                }
            }
             
            $reqs = split(";",$query);// on sépare les requêtes
            foreach($reqs as $req){ // et on les éxécute
                if (!mysql_query($req, $conn) && trim($req)!=""){
                    die("ERROR : ".$req); // stop si erreur 
                }
            }
            
            /* SET URL */
            $em = $this->getDoctrine()->getManager();
            $host = $em->getRepository('MajesCmsBundle:Host')
                ->findOneById(1);
    
            $host->setUrl($url);
            $host->setTitle('majesteel example');
    
            $em->persist($host);
            $em->flush();
    
            $routes = $em->getRepository('MajesCmsBundle:Route')
                ->findAll();
            
            foreach($routes as $route){
                $route->setHost($url);
                
                $em->persist($route);
                $em->flush();
            }

            /* SET ADMIN */
            $admin = $em->getRepository('MajesCoreBundle:User\User')
                ->findOneById(1);

            if(!is_null($admin)){
                $admin->setPassword(sha1($password));
                $admin->setEmail($email);

                $em->persist($admin);
                $em->flush();
            }
        }

        return $this->redirect($this->get('router')->generate('_admin_index'));
    }

    
}
