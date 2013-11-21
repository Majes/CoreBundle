<?php

namespace Majes\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\Common\Annotations\AnnotationReader;

use Majes\CmsBundle\Entity\Host;

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
        
        if(!is_dir(__DIR__ . '/../../../../../../web/media'))
            mkdir(__DIR__ . '/../../../../../../web/media', 0775);

        $permissions['dir_cache'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../app/cache')), -4);
        $permissions['dir_var'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../app/var')), -4);
        $permissions['dir_bundles'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../web/bundles')), -4);
        $permissions['dir_media'] = substr(sprintf('%o', fileperms(__DIR__ . '/../../../../../../web/media')), -4);

        $permission_status = ($permissions['dir_cache'] >= '0775' && 
            $permissions['dir_var'] >= '0775' && 
            $permissions['dir_bundles'] >= '0775' && 
            $permissions['dir_media'] >= '0775') ? true : false;

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

        $conn = mysql_connect($this->container->getParameter('database_host'), 
            $this->container->getParameter('database_user'), 
            $this->container->getParameter('database_password')) or die(mysql_error()); 
        $dbExists = mysql_select_db($this->container->getParameter('database_name'), 
            $conn); 

        if(!$dbExists)
            return $this->redirect($this->get('router')->generate('_majes_install'));

        $query="";
 
        $sql=file(__DIR__ . '/../../../../../../app/var/sql/db-mysql.sql'); // on charge le fichier SQL
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


        $host = new Host();
        $host->setUrl($this->container->getParameter('domain_url'));
        $host->setTitle('Majesteel');

        $em = $this->getDoctrine()->getManager();
        $em->persist($host);
        $em->flush();

        return $this->redirect($this->get('router')->generate('_admin_index'));

    }

    
}
