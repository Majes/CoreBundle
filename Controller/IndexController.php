<?php

namespace Majes\CoreBundle\Controller;

use Majes\CoreBundle\Controller\SystemController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use Majes\CoreBundle\Conversion\DataTableConverter;
use Majes\CoreBundle\Entity\Language;
use Majes\CoreBundle\Entity\LanguageTranslation;
use Majes\CoreBundle\Entity\LanguageToken;
use Majes\CoreBundle\Entity\User\User;
use Majes\CoreBundle\Entity\User\Role;
use Majes\MediaBundle\Entity\Media;
use Majes\CoreBundle\Entity\Chat;
use Majes\CoreBundle\Entity\Host;
use Majes\CoreBundle\Entity\ListBox;

use Majes\CoreBundle\Form\User\Myaccount;
use Majes\CoreBundle\Form\User\UserType;
use Majes\CoreBundle\Form\HostType;
use Majes\CoreBundle\Form\Language\LanguageType;
use Majes\CoreBundle\Form\User\UserRoleType;
use Majes\CoreBundle\Form\User\RoleType;
use Majes\CoreBundle\Form\Language\LanguageTokenType;
use Majes\CoreBundle\Form\Language\LanguageTranslationType;
use Majes\CoreBundle\Form\ListBoxType;
use Majes\CoreBundle\Utils\TeelFunction;
use Majes\CoreBundle\Annotation\DataTable;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class IndexController extends Controller implements SystemController
{
    /**
     * @Secure(roles="ROLE_ADMIN")
     *
     */
    public function dashboardAction()
    {
        $ga = $this->container->get('majes.ga');

        $request = $this->getRequest();
        if($request->getMethod() == 'POST'){

            $chatObj = new Chat();
            
            $chatObj->setUser($this->_user);
            $chatObj->setContent($request->get('content'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($chatObj);
            $em->flush();

        }

        //Get chat info
        $em = $this->getDoctrine()->getManager();
        $chat = $em->getRepository('MajesCoreBundle:Chat')
            ->findForDashboard();
        
        $stats_lastmonth = $ga->pastMonth();
        $global_stats = array();
        foreach ($stats_lastmonth as $date => $row) {
            $global_stats = $row;
        }

        // Various widgets
        $variouswidgets=array('users' => array('label' => 'Users', 
                                            'number' => array("icon" => "icon-user", "value" => count($em->getRepository('MajesCoreBundle:User\User')->findAll()) ), 
                                            'active'=> array("icon" => "icon-ok", "value" => count($em->getRepository('MajesCoreBundle:User\User')->findBy(array('isActive' => true, 'deleted' => false))) ), 
                                            'deleted' => array("icon" => "icon-trash", "value" =>count($em->getRepository('MajesCoreBundle:User\User')->findBy(array('deleted' => true)))) ),
                                'domains' => array('label' => 'Domains', 
                                            'number' => array("icon" => "icon-globe", "value" => count($em->getRepository('MajesCoreBundle:Host')->findAll()) ), 
                                            'active'=> array("icon" => "icon-ok", "value" => count($em->getRepository('MajesCoreBundle:Host')->findBy(array('deleted' => false))) ), 
                                            'deleted' => array("icon" => "icon-trash", "value" =>count($em->getRepository('MajesCoreBundle:Host')->findBy(array('deleted' => true)))) ),
                                'pages' => array('label' => 'Pages', 
                                            'number' => array("icon" => "icon-file-alt", "value" => count($em->getRepository('MajesCmsBundle:PageLang')->findAll()) ), 
                                            'active'=> array("icon" => "icon-ok", "value" => count($em->getRepository('MajesCmsBundle:PageLang')->findBy(array('deleted' => false))) ), 
                                            'deleted' => array("icon" => "icon-trash", "value" =>count($em->getRepository('MajesCmsBundle:PageLang')->findBy(array('deleted' => true)))) ),
                                'media' => array('label' => 'Medias', 
                                            'number' => array("icon" => "icon-picture", "value" => count($em->getRepository('MajesMediaBundle:Media')->findAll()) )),
                                'language' => array('label' => 'Languages', 
                                            'number' => array("icon" => "icon-flag-alt", "value" => count($em->getRepository('MajesCoreBundle:Language')->findAll()) ), 
                                            'items' => $em->getRepository('MajesCoreBundle:Language')->findAll()),
                                'activity' => array('label' => "Today's Activity",
                                            'number' => array('icon' => 'icon-stackexchange', 'value' => count($em->getRepository('MajesCoreBundle:Log')->getActivityReal($this->_user->getId(), $type = 'day', $page = 1, $limit = 200)))) 

        );
        return $this->render('MajesCoreBundle:Index:dashboard.html.twig', array(
            'google' => $stats_lastmonth,
            'stats' => $global_stats,
            'chat' => $chat,
            'variouswidgets' => $variouswidgets));
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
    public function emptycacheAction()
    {
        /*Clear cache*/
        if(is_dir($this->get('kernel')->getCacheDir())) {
            TeelFunction::delTree($this->get('kernel')->getCacheDir(), false, array('annotations'));

            echo json_encode(array('error' => false, 'message' => 'Cache has been cleared successfully!'));
        }

        return new Response();
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languagesAction(){

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $this->_langs,
            'object' => new Language(),
            'pageTitle' => $this->_translator->trans('Languages'),
            'pageSubTitle' => $this->_translator->trans('List off all languages currently available'),
            'label' => 'language',
            'message' => 'Are you sure you want to delete this language ?',
            'urls' => array(
                'add' => '_admin_language_edit',
                'edit' => '_admin_language_edit',
                'export' => '_admin_language_export'
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
                $kernel = $this->get('kernel');
                $path = $kernel->locateResource('@MajesCoreBundle/Resources/translations');
                $filename=$path."/messages.".$language->getLocale().".db";
                if(!file_exists($filename)){
                    fopen($filename, 'x');
                } 
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

        $pageSubTitle = empty($language) ? $this->_translator->trans('Add a new language') : $this->_translator->trans('Edit language') . ' ' . $language->getName();

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
            
        }


        return $this->redirect($this->get('router')->generate('_admin_languages', array()));
    }

     /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languageExportAction()
    {

        $reader = new AnnotationReader();
        
        $accessor = PropertyAccess::createPropertyAccessor();

        $reflClass = new \ReflectionClass('Majes\CoreBundle\Entity\Language');
        $methods = $reflClass->getMethods();

        $mapper=array();
        foreach ($methods as $method) {
            $classAnnotations = $reader->getMethodAnnotations($reflClass->getMethod($method->name));
            foreach ($classAnnotations AS $annot) {
                if ($annot instanceof DataTable) {
                    $label = $annot->getLabel();
                    $property = $annot->getColumn();
                    $merger=array($label => $property);
                    if(!empty($label) && !empty($property)){
                        $mapper=array_merge($mapper, $merger);
                    }
                    
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $languagetranslations = $em->getRepository('MajesCoreBundle:Language')
            ->findAll();

        $csv=array();

        array_push($csv, array_keys($mapper));
        
        foreach ($languagetranslations as $languagetranslation) {
            $line=array();
            foreach (array_values($mapper) as $property) {
                if(gettype($accessor->getValue($languagetranslation, $property)) == "object"){
                    if(get_class($accessor->getValue($languagetranslation, $property)) == "DateTime"){
                        array_push($line, $accessor->getValue($languagetranslation, $property)->format('Y-m-d H:i:s'));
                    }

                }else{
                    array_push($line, $accessor->getValue($languagetranslation, $property));
                }
            }
            array_push($csv, $line);
        }
        $exportsDir=$this->get('kernel')->getRootDir()."/../web/exports";
        $filename=$exportsDir."/Languages.csv";

        if(!file_exists($exportsDir)){
            mkdir($exportsDir);
        }
        $fp = fopen($filename, 'w');

        foreach ($csv as $line) {
            fputcsv($fp, $line);
        }

        fclose($fp);

        $response = new Response();

        // set headers
        $response->headers->set('Content-Type', 'text/csv');
        // $response->headers->set('Content-Length', $file['length']);
        $response->headers->set('Content-Disposition', 'attachment;filename="Languages.csv"');

        $response->setContent(file_get_contents($filename));
        return $response;
    }

     
    public function SitemapAction($host_id = null){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $CmsServices = $this->container->get('majescms.cms_service');
        $TeelServices = $this->container->get('majesteel.teel_service');

        $host = $em->getRepository('MajesCoreBundle:Host')->findOneById($host_id);

        $sitemap = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $sitemap->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        if(!is_null($host)){
            $sitemap = $CmsServices->Sitemap($sitemap, $host->getUrl());
            $sitemap = $TeelServices->Sitemap($sitemap, $host->getUrl());
        }else{
            $sitemap = $CmsServices->Sitemap($sitemap, null);
            $sitemap = $TeelServices->Sitemap($sitemap, null);
        }
        $response = new Response($sitemap->asXML());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function domainsAction(){

        $em = $this->getDoctrine()->getManager();
        $hosts = $em->getRepository('MajesCoreBundle:Host')
            ->findBy(array('deleted' => false));

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $hosts,
            'object' => new Host(),
            'label' => "domains",
            'message' => 'Are you sure you want to delete this domain ?',
            'pageTitle' => $this->_translator->trans('Domains'),
            'pageSubTitle' => $this->_translator->trans('List off all domains currently available'),
            'urls' => array(
                'add' => '_admin_domain_edit',
                'edit' => '_admin_domain_edit',
                'delete' => '_admin_domain_delete',
                'export' => '_admin_domain_export'
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function domainEditAction($id){

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $host = $em->getRepository('MajesCoreBundle:Host')
            ->findOneById($id);
       
        if(!is_null($host)){
            $oldhost=$host->getUrl();
        }

        $form = $this->createForm(new HostType($request->getSession()), $host);

        if($request->getMethod() == 'POST'){


            $form->handleRequest($request);
            if ($form->isValid()) {


                if(is_null($host)){
                    $host = $form->getData();
                } else {
                    $routes = $em->getRepository('MajesCmsBundle:Route')->findByHost($oldhost);
                    foreach ($routes as $route) {
                        $route->setHost($host->getUrl());
                        $em->persist($route);
                        $em->flush();
                    }
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($host);
                $em->flush();

                return $this->redirect($this->get('router')->generate('_admin_domain_edit', array('id' => $host->getId())));

            }else{
                foreach ($form->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
            }
        }

        $pageSubTitle = empty($host) ? $this->_translator->trans('Add a new domain') : $this->_translator->trans('Edit domain') . ' ' . $host->getTitle();

        return $this->render('MajesCoreBundle:Index:domain-edit.html.twig', array(
            'pageTitle' => 'Domains',
            'pageSubTitle' => $pageSubTitle,
            'host' => $host,
            'form' => $form->createView()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function domainDeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $host = $em->getRepository('MajesCoreBundle:Host')
            ->findOneById($id);

        if(!is_null($host)){
            $pages = $em->getRepository('MajesCmsBundle:Page')
            ->findByHost($host);
            foreach ($pages as $page) {
                $page->setDeleted(true);
                $pageLangs = $em->getRepository('MajesCmsBundle:PageLang')
                    ->findBy(array("page" => $page));
                foreach($pageLangs as $pagelang){
                    $pagelang->setDeleted(true);
                    $em->persist($pagelang);
                    $em->flush();
                }

                $em->persist($page);
                $em->flush();

                //Set routes to table
                $em->getRepository('MajesCmsBundle:Page')->generateRoutes($page->getMenu()->getRef(), $this->_is_multilingual);
            }

            $host->setDeleted(true);
            $em->persist($host);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_domains', array()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function domainUndeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $host = $em->getRepository('MajesCoreBundle:Host')
            ->findOneById($id);

        if(!is_null($host)){
            $host->setDeleted(false);
            $em->persist($host);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_trashs', array()));
    }

     /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function DomainExportAction()
    {

        $reader = new AnnotationReader();
        
        $accessor = PropertyAccess::createPropertyAccessor();

        $reflClass = new \ReflectionClass('Majes\CoreBundle\Entity\Host');
        $methods = $reflClass->getMethods();

        $mapper=array();
        foreach ($methods as $method) {
            $classAnnotations = $reader->getMethodAnnotations($reflClass->getMethod($method->name));
            foreach ($classAnnotations AS $annot) {
                if ($annot instanceof DataTable) {
                    $label = $annot->getLabel();
                    $property = $annot->getColumn();
                    $merger=array($label => $property);
                    if(!empty($label) && !empty($property)){
                        $mapper=array_merge($mapper, $merger);
                    }
                    
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $languagetranslations = $em->getRepository('MajesCoreBundle:Host')
            ->findBy(array('deleted' => false));

        $csv=array();

        array_push($csv, array_keys($mapper));
        
        foreach ($languagetranslations as $languagetranslation) {
            $line=array();
            foreach (array_values($mapper) as $property) {
                if(gettype($accessor->getValue($languagetranslation, $property)) == "object"){
                    if(get_class($accessor->getValue($languagetranslation, $property)) == "DateTime"){
                        array_push($line, $accessor->getValue($languagetranslation, $property)->format('Y-m-d H:i:s'));
                    }

                }else{
                    array_push($line, $accessor->getValue($languagetranslation, $property));
                }
            }
            array_push($csv, $line);
        }
        $exportsDir=$this->get('kernel')->getRootDir()."/../web/exports";
        $filename=$exportsDir."/Hosts.csv";

        if(!file_exists($exportsDir)){
            mkdir($exportsDir);
        }
        $fp = fopen($filename, 'w');

        foreach ($csv as $line) {
            fputcsv($fp, $line);
        }

        fclose($fp);

        $response = new Response();

        // set headers
        $response->headers->set('Content-Type', 'text/csv');
        // $response->headers->set('Content-Length', $file['length']);
        $response->headers->set('Content-Disposition', 'attachment;filename="Hosts.csv"');

        $response->setContent(file_get_contents($filename));
        return $response;
    }


    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languageMessagesAction(){
        $_results_per_page = 20; 
        $request = $this->getRequest();

        $catalogues = $request->get('catalogues');
        $langs = $request->get('langs');
        $page = $request->get('page');
        $loadmore = false;

        $em = $this->getDoctrine()->getManager();

        if(!is_null($catalogues) && in_array('', $catalogues)) $catalogues = null;
        if(!is_null($langs) && in_array('', $langs)) $langs = null;
        if(is_null($page)) $page = 1;

        $translations = $em->getRepository('MajesCoreBundle:LanguageToken')
                ->findForAdmin($catalogues, $langs/*, $page, $_results_per_page*/);
        

        $loadmore = count($translations) > $_results_per_page ? true : false;
        //count($translations) > $_results_per_page ? array_pop($translations) : $translations;


        $all_catalogues = $em->getRepository('MajesCoreBundle:LanguageTranslation')
            ->listCatalogues();

        return $this->render('MajesCoreBundle:Index:language-messages.html.twig', array(
            'pageTitle' => $this->_translator->trans('Languages'),
            'object' => new LanguageToken(),
            'pageSubTitle' => $this->_translator->trans('List of translations'),
            'loadmore' => $loadmore,
            'page' => $page,
            'catalogues' => $catalogues,
            'langs' => $langs,
            'all_langs' => $this->_langs,
            'datas' => $translations,
            'all_catalogues' => $all_catalogues,
            'label' => 'translation',
            'message' => 'Are you sure you want to delete this translation ?',
            'urls' => array(
                'add' => '_admin_language_message_edit',
                'edit' => '_admin_language_message_edit',
                'delete' => '_admin_language_message_delete',
                'export' => '_admin_language_message_export',
                'params' => array()
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function languageMessageEditAction($id)
    {   
        $token_id = $id;
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();



        $languagetranslation = $em->getRepository('MajesCoreBundle:LanguageTranslation')
            ->findOneBy(array('token' => $token_id));


        $translations = array();
        foreach($this->_langs as $lang){

            $translations[$lang->getLocale()] = array('name' => $lang->getName(), 'value' => '');

        }

        //Get token
        if(!is_null($languagetranslation)){
            
            $token = $languagetranslation->getToken();
            foreach($token->getTranslations() as $token_translation){
                $translations[$token_translation->getLocale()]['value'] = $token_translation->getTranslation();
            }

        }else{
            $token = null;
        }

        //Perform post submit
        //$form = $this->createForm(new LanguageTokenType($lang), $token);
        if($request->getMethod() == 'POST'){

            if(is_null($token)){
                $token = new LanguageToken();
            }

            $token->setToken($request->get('token'));
            
            $em->persist($token);
            $em->flush();

            $token_id = $token->getId();

            $form_translations = $request->get('translations');
            foreach ($form_translations as $form_translation_lang => $form_translation) {
                
                //Get translation if exists in db
                $form_translation_temp = $em->getRepository('MajesCoreBundle:LanguageTranslation')
                    ->findOneBy(array('token' => $token_id, 'locale' => $form_translation_lang));
                if(is_null($form_translation_temp)){
                    $form_translation_temp = new LanguageTranslation();
                    $form_translation_temp->setToken($token);
                    $form_translation_temp->setLocale($form_translation_lang);
                    $form_translation_temp->setCatalogue('messages'); //to change
                }

                $form_translation_temp->setTranslation($form_translation);
                $em->persist($form_translation_temp);
                $em->flush();
            }

            //Clear translation cache
            foreach ($this->_langs as $_lang) {
                $lang = $_lang->getLocale();
                if(is_file($this->get('kernel')->getCacheDir().'/translations/catalogue.'.$lang.'.php')) 
                    unlink($this->get('kernel')->getCacheDir().'/translations/catalogue.'.$lang.'.php');
                if(is_file($this->get('kernel')->getCacheDir().'/translations/catalogue.'.$lang.'.php.meta')) 
                    unlink($this->get('kernel')->getCacheDir().'/translations/catalogue.'.$lang.'.php.meta');
            }
           

            //Set routes to table
            return $this->redirect($this->get('router')->generate('_admin_language_message_edit', array('id' => $token_id)));

         

            
        }

        $edit = !is_null($token_id) ? 1 : 0;


        $pageSubTitle = is_null($token) ? $this->_translator->trans('Add a new translation') : $this->_translator->trans('Edit translation') .' '. (!is_null($token->getToken()) ? $token->getToken() : '--');
        return $this->render('MajesCoreBundle:Index:language-message-edit.html.twig', array(
            'pageTitle' => $this->_translator->trans('Language translation'),
            'pageSubTitle' => $pageSubTitle,
            'translation' => $languagetranslation,
            'token' => $token,
            'translations' => $translations,
            'edit' => $edit,
            'lang' => $this->_lang
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function LanguageMessageDeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $translation = $em->getRepository('MajesCoreBundle:LanguageToken')
            ->findOneById($id);

        if(!is_null($translation)){
            $translations = $em->getRepository('MajesCoreBundle:LanguageTranslation')
            ->findBy(array('token' => $translation->getId()));
            foreach($translations as $translate){
                $em->remove($translate);
                $em->flush();
            }
            
            $em->remove($translation);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_language_messages', array()));
    }

    /**
    * @Secure(roles="ROLE_SUPERADMIN")
    *
    */
    public function languageMessageExportAction()
    {

        $reader = new AnnotationReader();
        
        $accessor = PropertyAccess::createPropertyAccessor();

        $reflClass = new \ReflectionClass('Majes\CoreBundle\Entity\LanguageTranslation');
        $methods = $reflClass->getMethods();

        $mapper=array();
        foreach ($methods as $method) {
            $classAnnotations = $reader->getMethodAnnotations($reflClass->getMethod($method->name));
            foreach ($classAnnotations AS $annot) {
                if ($annot instanceof DataTable) {
                    $label = $annot->getLabel();
                    $property = $annot->getColumn();
                    $merger=array($label => $property);
                    if(!empty($label) && !empty($property)){
                        $mapper=array_merge($mapper, $merger);
                    }
                    
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $languagetranslations = $em->getRepository('MajesCoreBundle:LanguageTranslation')
            ->findAll();

        $csv=array();

        array_push($csv, array_keys($mapper));
        
        foreach ($languagetranslations as $languagetranslation) {
            $line=array();
            foreach (array_values($mapper) as $property) {
                array_push($line, $accessor->getValue($languagetranslation, $property));
            }
            array_push($csv, $line);
        }
        $exportsDir=$this->get('kernel')->getRootDir()."/../web/exports";
        $filename=$exportsDir."/Traductions.csv";

        if(!file_exists($exportsDir)){
            mkdir($exportsDir);
        }
        $fp = fopen($filename, 'w');

        foreach ($csv as $line) {
            fputcsv($fp, $line);
        }

        fclose($fp);

        $response = new Response();

        // set headers
        $response->headers->set('Content-Type', 'text/csv');
        // $response->headers->set('Content-Length', $file['length']);
        $response->headers->set('Content-Disposition', 'attachment;filename="Traductions.csv"');

        $response->setContent(file_get_contents($filename));
        return $response;
    }
   
    /**
     * @Secure(roles="ROLE_SUPERADMIN,ROLE_ADMIN_USER")
     *
     */
    public function usersAction(){

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('MajesCoreBundle:User\User')
            ->findBy(array('deleted' => false));
        if(!$this->get('security.context')->isGranted('ROLE_SUPERADMIN'))
            $users = array_filter($users,function($user){
                if($user->hasRole($this->getDoctrine()->getManager()->getRepository('MajesCoreBundle:User\Role')->findOneBy(array('deleted' => false, 'role' => 'ROLE_SUPERADMIN'))->getId())){
                    return false;
                } 
                return true;
            });            

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $users,
            'object' => new User(),
            'pageTitle' => 'Users',
            'pageSubTitle' => $this->_translator->trans('List off all users currently "created"'),
            'label' => 'user',
            'message' => 'Are you sure you want to delete this user ?',
            'urls' => array(
                'add' => '_admin_user_edit',
                'edit' => '_admin_user_edit',
                'delete' => '_admin_user_delete',
                'export' => '_admin_user_export'
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN,ROLE_ADMIN_USER")
     *
     */
    public function userEditAction($id){

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MajesCoreBundle:User\User')->findOneById($id);

        if(!$this->get('security.context')->isGranted('ROLE_SUPERADMIN'))
            if($user->hasRole($this->getDoctrine()->getManager()->getRepository('MajesCoreBundle:User\Role')->findOneBy(array('deleted' => false, 'role' => 'ROLE_SUPERADMIN'))->getId()))
                throw new AccessDeniedException();
                

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

        $pageSubTitle = empty($user) ? $this->_translator->trans('Add a new user') : $this->_translator->trans('Edit user') . ' ' . $user->getUsername();

        $form_role = null;
        if(!is_null($id))
            $form_role = $this->createForm(
                new UserRoleType($this->getDoctrine()->getManager(), $user),
                null,
                array('action' => $this->get('router')->generate('_admin_user_role', array('id' => $id)))
            )->createView();

        return $this->render('MajesCoreBundle:Index:user-edit.html.twig', array(
            'pageTitle' => $this->_translator->trans('Users'),
            'pageSubTitle' => $pageSubTitle,
            'user' => $user,
            'form' => $form->createView(),
            'form_role' => $form_role));
    }

     /**
     * @Secure(roles="ROLE_SUPERADMIN,ROLE_ADMIN_USER")
     *
     */
    public function UserExportAction()
    {

        $reader = new AnnotationReader();
        
        $accessor = PropertyAccess::createPropertyAccessor();

        $reflClass = new \ReflectionClass('Majes\CoreBundle\Entity\User\User');
        $methods = $reflClass->getMethods();

        $mapper=array();
        foreach ($methods as $method) {
            $classAnnotations = $reader->getMethodAnnotations($reflClass->getMethod($method->name));
            foreach ($classAnnotations AS $annot) {
                if ($annot instanceof DataTable) {
                    $label = $annot->getLabel();
                    $property = $annot->getColumn();
                    $merger=array($label => $property);
                    if(!empty($label) && !empty($property)){
                        $mapper=array_merge($mapper, $merger);
                    }
                    
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $languagetranslations = $em->getRepository('MajesCoreBundle:User\User')
            ->findBy(array('deleted' => false));

        $csv=array();

        array_push($csv, array_keys($mapper));
        
        foreach ($languagetranslations as $languagetranslation) {
            $line=array();
            foreach (array_values($mapper) as $property) {
                if(gettype($accessor->getValue($languagetranslation, $property)) == "object"){
                    if(get_class($accessor->getValue($languagetranslation, $property)) == "DateTime"){
                        array_push($line, $accessor->getValue($languagetranslation, $property)->format('Y-m-d H:i:s'));
                    }

                }else{
                    array_push($line, $accessor->getValue($languagetranslation, $property));
                }
            }
            array_push($csv, $line);
        }
        $exportsDir=$this->get('kernel')->getRootDir()."/../web/exports";
        $filename=$exportsDir."/Users.csv";

        if(!file_exists($exportsDir)){
            mkdir($exportsDir);
        }
        $fp = fopen($filename, 'w');

        foreach ($csv as $line) {
            fputcsv($fp, $line);
        }

        fclose($fp);

        $response = new Response();

        // set headers
        $response->headers->set('Content-Type', 'text/csv');
        // $response->headers->set('Content-Length', $file['length']);
        $response->headers->set('Content-Disposition', 'attachment;filename="Users.csv"');

        $response->setContent(file_get_contents($filename));
        return $response;
    }


    /**
     * @Secure(roles="ROLE_SUPERADMIN,ROLE_ADMIN_USER")
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
     * @Secure(roles="ROLE_SUPERADMIN,ROLE_ADMIN_USER")
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
     * @Secure(roles="ROLE_SUPERADMIN,ROLE_ADMIN_USER")
     *
     */
    public function userDeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MajesCoreBundle:User\User')
            ->findOneById($id);

        if(!is_null($user)){
            foreach ($user->getRoles() as $role) {
                $user->removeRole($role);
            }
            $user->setDeleted(true);
            $em->persist($user);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_users', array()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function userUndeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MajesCoreBundle:User\User')
            ->findOneById($id);

        if(!is_null($user)){
            $user->setDeleted(false);
            $em->persist($user);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_trashs', array()));
    }


    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function rolesAction(){
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('MajesCoreBundle:User\Role')
            ->findBy(array('deleted' => false));

        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $roles,
            'object' => new Role(),
            'label' => 'roles',
            'message' => 'Are you sure you want to delete this role ?',
            'pageTitle' => $this->_translator->trans('Roles'),
            'pageSubTitle' => $this->_translator->trans('List off all roles currently available'),
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

        $pageSubTitle = empty($role) ? $this->_translator->trans('Add a new role') : $this->_translator->trans('Edit role') . ' ' . $role->getRole();

        return $this->render('MajesCoreBundle:Index:role-edit.html.twig', array(
            'pageTitle' => $this->_translator->trans('Roles'),
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

        if(!is_null($role) && !$role->getIsSystem()){
            foreach ($role->getUsers() as $user) {
                $user->removeRole($role);
            }
            foreach ($role->getPages() as $page) {
                $page->removeRole($role);
            }
            $role->setDeleted(true);
            $em->persist($role);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_roles', array()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function roleUndeleteAction($id){
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('MajesCoreBundle:User\Role')
            ->findOneById($id);

        if(!is_null($role)){
            $role->setDeleted(false);
            $em->persist($role);
            $em->flush();
        }


        return $this->redirect($this->get('router')->generate('_admin_trashs', array()));
    }

    /**
     * @Secure(roles="ROLE_SUPERADMIN")
     *
     */
    public function TrashsAction($context)
    {
        $_results_per_page = 10; 

        $em = $this->getDoctrine()->getManager();
        $accessor = PropertyAccess::createPropertyAccessor();

        $request = $this->getRequest();
        $session = $this->get('session');

        $filter = $request->get("types");

        $loadmore = false;

        $headers = array(array('isMobile' => true, 'isSortable' => true, 'label' => 'Id'),
                    array('isMobile' => true, 'isSortable' => true, 'label' => 'Type'),
                    array('isMobile' => true, 'isSortable' => true, 'label' => 'Name'));

        $trash=$session->get('menu')['trash'];

        $choices = array();

        foreach ($trash['entities'] as $choice) {
            $choices[]=$choice['label'];   
        }

        $entities = array();
        if(is_null($filter) || in_array("", $filter)){
            foreach ($trash['entities'] as $entity) {
                $collection = $em->getRepository($entity['bundle'].'Bundle:'.$entity['entity'])->findBy(array("deleted"=> true));
                foreach($collection as $iteration){
                    $pseudoEntity=array(
                        'Id' => $iteration->getId(),
                        'Name' => $accessor->getValue($iteration, $entity['title']),
                        'Type' => $entity['label'],
                        'actions' => array(
                            'undelete' => '_admin_'.strtolower($entity['label']).'_undelete')
                        );
                    array_push($entities, $pseudoEntity);
                }
            }
        }elseif(!is_null($filter) && !in_array("", $filter)){
            foreach ($trash['entities'] as $entity) {
                if(in_array($entity['label'], $filter)){
                    $collection = $em->getRepository($entity['bundle'].'Bundle:'.$entity['entity'])->findBy(array("deleted"=> true));
                    foreach($collection as $iteration){
                        $pseudoEntity=array(
                            'Id' => $iteration->getId(),
                            'Name' => $accessor->getValue($iteration, $entity['title']),
                            'Type' => $entity['label'],
                            'actions' => array(
                                'undelete' => '_admin_'.strtolower($entity['label']).'_undelete')
                            );
                        array_push($entities, $pseudoEntity);
                    }
                }
            }
        }
        return $this->render('MajesCoreBundle:Index:trash.html.twig', array(
            'pageTitle' => $this->_translator->trans('Trash'),
            'pageSubTitle' => $this->_translator->trans('List of all objects trashed'),
            'page' => 1,
            'chosen' => $filter,
            'choices' => $choices,
            'headers' => $headers,
            'entities' => $entities,
            ));
    }
    /**
     * @Secure(roles="ROLE_CMS_DESIGNER,ROLE_SUPERADMIN")
     *
     */
    public function listboxsAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        $listbox = $em->getRepository('MajesCoreBundle:ListBox')
            ->findBy(array("deleted" => false));
        
        return $this->render('MajesCoreBundle:common:datatable.html.twig', array(
            'datas' => $listbox,
            'object' => new ListBox(),
            'label' => 'listbox',
            'message' => 'Are you sure you want to delete this list ?',
            'pageTitle' => $this->_translator->trans('Lists management'),
            'pageSubTitle' => $this->_translator->trans('List of all available lists'),
            'urls' => array(
                'add' => '_admin_listbox_edit',
                'edit' => '_admin_listbox_edit',
                'delete' => '_admin_listbox_delete'
                )
            ));
    }

    /**
     * @Secure(roles="ROLE_CMS_DESIGNER,ROLE_SUPERADMIN")
     *
     */
    public function listboxEditAction($id)
    {
        
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $listbox = $em->getRepository('MajesCoreBundle:ListBox')
            ->findOneById($id);

        
        $form = $this->createForm(new ListBoxType($request->getSession()), $listbox);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if ($form->isValid()) {

                if(is_null($listbox)) 
                    $listbox = $form->getData();

                $content=array();
                               
                foreach ($form->get('content')->getData() as $item) {
                    if(is_null($item['key'])) $item['key']=date("dmy").mt_rand();
                    if(is_null($item['slug'])) $item['slug']=strtolower(str_replace(" ", "-", $item['value']));
                    array_push($content, $item);
                }
                $i=0;
                foreach ($content as $item) {
                    $k=0;
                    for($j=$i-1 ;$j>=0; $j-- ){
                        if($content[$j]['slug'] == $content[$i]['slug']) $k++;
                    }
                    if($k>0) $content[$i]['slug'] .= (string)$k;
                    $i++;
                }
                $listbox->setContent($content);

                $em->persist($listbox);
                $em->flush();

                return $this->redirect($this->get('router')->generate('_admin_listbox_edit', array('id' => $listbox->getId())));

            }else{
                foreach ($form->getErrors() as $error) {
                    echo $message[] = $error->getMessage();
                }
            }
        }

        $pageSubTitle = empty($block) ? $this->_translator->trans('Add a new list') : $this->_translator->trans('Edit List'). ' ' . $listbox->getName();
        

        return $this->render('MajesCoreBundle:Index:entity-edit.html.twig', array(
            'icon' => 'icon-list-ul',
            'collections' => array(array('label' => 'Item', 'holder' => 'listboxtype_content')),
            'pageTitle' => $this->_translator->trans('List'),
            'pageSubTitle' => $pageSubTitle,
            'entity' => $listbox,
            'form' => $form->createView(),
            'form_role' => null));
    }

    /**
     * @Secure(roles="ROLE_CMS_DESIGNER,ROLE_SUPERADMIN")
     *
     */
    public function listboxDeleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $list = $em->getRepository('MajesCoreBundle:ListBox')
                ->findOneById($id);

        if (!is_null($list)) {

            $list->setDeleted(true);
            $em->persist($list);
            $em->flush();
        }
        
        return $this->redirect($this->get('router')->generate('_admin_listboxs_list', array()));
    }

    /**
     * @Secure(roles="ROLE_CMS_DESIGNER,ROLE_SUPERADMIN")
     *
     */
    public function listboxUndeleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $list = $em->getRepository('MajesCmsBundle:List')
                ->findOneById($id);

        if (!is_null($list)) {
            $list->setDeleted(false);
            $em->persist($list);
            $em->flush();
        }
        
        return $this->redirect($this->get('router')->generate('_admin_trashs', array()));
    }


}
