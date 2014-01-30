<?php 
namespace Majes\CoreBundle\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use Majes\CoreBundle\Conversion\DataTableConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoreExtension extends \Twig_Extension
{
    private $_container;
    public function __construct(ContainerInterface $container = null){
        $this->_container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dataTable', array($this, 'dataTable')),
            new \Twig_SimpleFunction('get', array($this, 'get')),
            new \Twig_SimpleFunction('getMimeType', array($this, 'getMimeType')),
            new \Twig_SimpleFunction('majesCountNotification', array($this, 'majesCountNotification')),
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

    public function getName()
    {
        return 'majescore_extension';
    }
}