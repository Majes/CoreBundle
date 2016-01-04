<?php

namespace Majes\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Majes\CoreBundle\Controller\SystemController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Majes\CoreBundle\Entity\Language;
use Majes\CoreBundle\Entity\Log;
use Majes\CoreBundle\Entity\User\User;
use Majes\CoreBundle\Entity\User\Role;

require_once __DIR__.'/../../../../../facebook/php-sdk/src/facebook.php';

class SystemListener
{

    public $_user;
    public $_lang;
    public $_langs;
    public $_default_lang;
    public $_translator;
    public $_env;
    public $_host;
    public $_is_multilingual;

    private $entityManager = null;
    private $securityContext = null;
    private $container = null;
    private $router = null;


    public function __construct(EntityManager $entityManager, SecurityContext $securityContext, Container $container, $router)
    {
        $this->entityManager = $entityManager;
        $this->securityContext = $securityContext;
        $this->container = $container;
        $this->router = $router;
    }

    public function onKernelController(FilterControllerEvent $event)
    {

        $controller = $event->getController();
        $request = $event->getRequest();
        $locale = $request->getLocale();


        //Check if an admin user exists
        $_route = $request->get('_route');
        if(!empty($_route) && $_route != '_majes_install' && $_route != '_majes_install_db'){

            $userAdmin = $this->entityManager->getRepository('MajesCoreBundle:User\User')->findOneById(1);

            if(empty($userAdmin)){
                $redirectUrl = $this->router->generate('_majes_install');
                header('Location: '.$redirectUrl);
                exit;
            }
        }

        $routeDoc = $request->get('routeDocument');

        $domain = $request->server->get('HTTP_HOST');

        if(!empty($routeDoc)){
            $locale = $routeDoc->getOption('lang');
            $request->setLocale($locale);
        }else{
            //Check if domain is linked to a language
            $langByDomain = $this->entityManager->getRepository('MajesCoreBundle:Language')->findOneBy(
                array('host' => $domain)
                );


            if(!empty($langByDomain))
                $locale = $langByDomain->getLocale();

        }

        $host = $this->entityManager->getRepository('MajesCoreBundle:Host')->findOneBy(
                array('url' => $domain)
            );


        $this->_host = empty($host) ? null : $host;

        $request->setLocale($locale);
        $controllerObject = $controller[0];


        /*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controllerObject instanceof SystemController) {

            $parameters = $this->container->getParameter('admin');
            $google = $this->container->getParameter('google');
            $facebook = $this->container->getParameter('facebook');
            $twitter = $this->container->getParameter('twitter');

            //try autologin via facebook, twitter or google
            $social = $this->container->get('majes.social');

            $user = $social->login();

            $wysiwyg = $parameters['wysiwyg'];
            $menu = $this->container->getParameter('menu');
            $is_multilingual = $this->container->getParameter('is_multilingual');

            $env = $this->container->get( 'kernel' )->getEnvironment();
            if(isset($parameters['maintenance']) && $parameters['maintenance'] && $env == 'prod') {
                $redirectUrl = $this->router->generate($parameters['maintenance_route']);
                header('Location: '.$redirectUrl);
                exit;
                // $this->render(redirectUrl($parameters['maintenance_route']));
            }

            try{
                $token = $this->securityContext->getToken();
                $_user = !is_null($token) ? $token->getUser() : false;

                if((empty($_user) || is_string($_user)) && !empty($user)){
                    //TODO :: Auto login user
                    $token = new UsernamePasswordToken($user, $user->getPassword(), "myaccount", $user->getRoles());
                    $this->securityContext->setToken($token);
                    $_user = $user;
                }

                if(!empty($_user) && !is_string($_user)){
                    $params = array_merge($request->query->all(),  $request->request->all(), (array) $request->get('_route_params'));

                    if(!is_null($request->get('_route'))){
                        $log = new Log();
                        $log->setUser($_user);
                        $log->setName('SystemListener');
                        $log->setRoute($request->get('_route'));
                        $log->setLocale($locale);
                        $log->setParams(json_encode($params));

                        $this->entityManager->persist($log);
                        $this->entityManager->flush();
                    }

                    $wysiwyg = $parameters['wysiwyg'] ? $_user->getWysiwyg() : false;

                }
            }catch(Exception $e){}

            $matches    = array();
            $controller = $request->attributes->get('_controller');
            preg_match("/([a-zA-Z]*)\\\([a-zA-Z]*)Bundle\\\Controller\\\([a-zA-Z]*)Controller::([a-zA-Z]*)Action/", $controller, $matches);

            if(count($matches) > 0){
                $request->attributes->set('namespace',  $matches[1]);
                $request->attributes->set('bundle',     $matches[2]);
                $request->attributes->set('controller', $matches[3]);
                $request->attributes->set('action',     $matches[4]);
            }


            $language_rowset = $this->entityManager->getRepository('MajesCoreBundle:Language')->findAll();

            $twig = $this->container->get('twig');
            $globals = $twig->getGlobals();
            $default_lang = $globals['default_lang'];

            $controllerObject->_env = $env;
            $controllerObject->_user = $_user;
            $controllerObject->_lang = $locale;
            $controllerObject->_host = $this->_host;
            $controllerObject->_langs = $language_rowset;
            $controllerObject->_default_lang = $default_lang;
            $controllerObject->_translator = $controllerObject->get('translator');
            $controllerObject->_is_multilingual = $is_multilingual;

            $session = $request->getSession();
            $session->set('langs', $controllerObject->_langs);
            $session->set('_locale', $locale);
            $session->set('wysiwyg', $wysiwyg);
            $session->set('menu', $menu);
            $session->set('google', $google);
            $session->set('facebook', $facebook);
            $session->set('twitter', $twitter);

            /*NOTIFICATION*/
            // Google analytics
            // Google api
            $ga = $this->container->get('majes.ga');
            $ga_status = $ga->isUp();

            $notification = $this->container->get('majes.notification');

            $notification->set(array('_source' => 'core'));
            $notification->reinit();

            $google = $this->container->getParameter('google');
            if($ga_status == -1)
                $notification->add('notices', array('status' => 'warning', 'title' => 'Google API is down', 'url' => $ga->_authUrl));
            elseif($ga_status == -2)
                $notification->add('notices', array('status' => 'danger', 'title' => 'Google API params have not been set', 'url' => '#'));

            if(!isset($google['analytics']) || empty($google['analytics']))
                $notification->add('notices', array('status' => 'danger', 'title' => 'Google Analytics tag has not been set', 'url' => '#'));


        }

    }

}
