<?php 
namespace Majes\CoreBundle\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use Majes\CoreBundle\Conversion\DataTableConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoreExtension extends \Twig_Extension
{
    private $_container;
    private $_em;

    public function __construct(ContainerInterface $container = null, $em){
        $this->_em = $em;
        $this->_container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dataTable', array($this, 'dataTable')),
            new \Twig_SimpleFunction('get', array($this, 'get')),
            new \Twig_SimpleFunction('getMimeType', array($this, 'getMimeType')),
            new \Twig_SimpleFunction('majesCountNotification', array($this, 'majesCountNotification')),
            new \Twig_SimpleFunction('fileExists', array($this, 'fileExists')),
            new \Twig_SimpleFunction('setupAttribute', array($this, 'setupAttribute')),
            new \Twig_SimpleFunction('getListbox', array($this, 'getListbox')),
            new \Twig_SimpleFunction('getRoutes', array($this, 'getRoutes'))
        );
    }

    public function dataTable($object, $objects){
        

        $reader = new AnnotationReader();
        $converter = new DataTableConverter($reader);
        
        $dataTableConfig = $converter->convert($object);

        return $dataTableConfig;

    }

    public function get($object, $property, $format = null){
        
        $function = 'get'.ucfirst($property);
        $value = $object->$function();

        if($format == 'datetime'){
            if(is_null($value)) return '';
            return $value->format('d/m/Y');
        }else
            return $value;

    }

    public function getMimeType($path){

        $file = false;

        if(is_file(__DIR__.'/../../../../../../web/'.$path))
            $file = __DIR__.'/../../../../../../web/'.$path;

        if(is_file(__DIR__.'/../../../../../../app/private/'.$path))
            $file = __DIR__.'/../../../../../../app/private/'.$path;

        if(!$file)
            return;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($file);

        

    }

    public function majesCountNotification($type){

        $notification = $this->_container->get('majes.notification');
        return $notification->count($type);
    }

    public function fileExists($filename){
        return file_exists($filename);
    }

    public function setupAttribute($ref)
    {
        switch($ref){
            case 'listbox':
                $lists = $this->_em->getRepository('MajesCoreBundle:ListBox')
                ->findBy(array('deleted' => false));
                $setup=array('lists'=>$lists, 'ref'=>$ref);   
                return $setup;
            break;
            case 'listboxmultiple':
                $lists = $this->_em->getRepository('MajesCoreBundle:ListBox')
                ->findBy(array('deleted' => false));
                $setup=array('lists'=>$lists, 'ref'=>$ref);   
                return $setup;
            break;
        }
    }

    public function getListbox($id)
    {
        $list = $this->_em->getRepository('MajesCoreBundle:ListBox')
        ->findOneBy(array('deleted' => false, 'id' => $id));  
        return $list;
    }

    public function getRoutes(){

        $routes = $this->_em->getRepository('MajesCmsBundle:Route')
                    ->findAll();
        return $routes;
    }

    public function getName()
    {
        return 'majescore_extension';
    }
}