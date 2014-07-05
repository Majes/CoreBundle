<?php 
namespace Majes\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ExceptionListener
{
    protected $templating;
    protected $kernel;
    protected $container;
    protected $em;

    public function __construct(EngineInterface $templating, $kernel, Container $container)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
        $this->container = $container;

        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $request = $event->getRequest();
        $locale = $this->container->getParameter('locale');


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
            if($statusCode == 200) header("Location: /install");
            
            if(empty($statusCode))
                $response->setStatusCode($exception->getCode() != 0 ? $exception->getCode() : 500);
        }

        if($this->templating->exists('MajesTeelBundle:Exception:'.$response->getStatusCode().'.html.twig'))
            $template = 'MajesTeelBundle:Exception:'.$response->getStatusCode().'.html.twig';
        elseif($this->templating->exists('MajesTeelBundle:Exception:'.$response->getStatusCode().'.html.twig'))
            $template = 'MajesCmsBundle:Exception:'.$response->getStatusCode().'.html.twig';
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
