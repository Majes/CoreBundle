<?php
namespace Majes\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ExceptionListener
{
    protected $templating;
    protected $container;
    private $securityContext = null;
    protected $em;

    public function __construct(EngineInterface $templating, Container $container, SecurityContext $securityContext)
    {

        $this->templating = $templating;
        $this->container = $container;
        $this->securityContext = $securityContext;

        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $request = $event->getRequest();
        $locale = $this->container->getParameter('locale');

        $token = $this->securityContext->getToken();
        if($token instanceof \Symfony\Component\Security\Core\Authentication\Token\AnonymousToken || $token instanceof FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken){
            $firewall = '';
        }
        else{

            $firewall = (empty($token) || !method_exists($token, 'getProviderKey')) ? '' : $token->getProviderKey();
        }

        $firewall = $firewall == 'admin_area' ? 'admin' : 'front';

        $this->container->enterScope('request');
        $this->container->set('request', $request, 'request');

        if(strpos($this->container->get('kernel')->getEnvironment(), 'dev' ) !== false)
            return;

        if(isset($request->server)){
            $domain = $request->server->get('HTTP_HOST');
            $langByDomain = $this->em->getRepository('MajesCoreBundle:Language')->findOneBy(
                    array('host' => $domain)
                    );
            if(!empty($langByDomain))
                $locale = $langByDomain->getLocale();
            else{
                $PATH_INFO = $request->server->get('PATH_INFO');
                if(!empty($PATH_INFO)){
                    $path_array = explode('/', $PATH_INFO);
                    $langByLocale = $this->em->getRepository('MajesCoreBundle:Language')->findOneBy(
                        array('locale' => $path_array[1])
                        );
                    if(!empty($langByLocale))
                        $locale = $langByLocale->getLocale();
                }

            }
        }

        $request->setLocale($locale);
        // exception object
        $exception = $event->getException();

        if(!empty($_user) && !is_string($_user)){
            $params = array_merge($request->query->all(),  $request->request->all(), $request->get('_route_params'));

            if(!is_null($request->get('_route'))){

                $log = new Log();
                $log->setUser($_user);
                $log->setName('ExceptionListener');
                $log->setRoute($request->get('_route'));
                $log->setLocale($locale);
                $log->setParams(json_encode($params));
                $log->setStatus(json_encode($exception->getStatusCode()));
                $log->setException(json_encode($exception->getMessage()));

                $this->entityManager->persist($log);
                $this->entityManager->flush();
            }
            $wysiwyg = $parameters['wysiwyg'] ? $_user->getWysiwyg() : false;
        }

        // new Response object
        $response = new Response();
        // set response content

        // HttpExceptionInterface is a special type of exception
        // that holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {

            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());

        } else {

            $statusCode = $response->getStatusCode();
            if(empty($statusCode) || $statusCode == 200)
                $response->setStatusCode($exception->getCode() != 0 ? $exception->getCode() : 500);

        }

        if($firewall == 'front' && $this->templating->exists('MajesTeelBundle:Exception:'.$response->getStatusCode().'.html.twig'))
            $template = 'MajesTeelBundle:Exception:'.$response->getStatusCode().'.html.twig';
        else
            $template = 'MajesCoreBundle:Exception:'.$response->getStatusCode().'.html.twig';

        $html = $this->templating->render(
                $template,
                array('message' => $exception->getMessage(), 'code' => $response->getStatusCode())
            );

        $response->setContent(
            // create you custom template AcmeFooBundle:Exception:exception.html.twig
            $html
        );
        // set the new $response object to the $event
        $event->setResponse($response);

    }
}
