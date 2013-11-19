<?php 
namespace Majes\CoreBundle\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use Majes\CoreBundle\Conversion\DataTableConverter;


class CoreExtension extends \Twig_Extension
{
   

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dataTable', array($this, 'dataTable')),
            new \Twig_SimpleFunction('get', array($this, 'get')),
            new \Twig_SimpleFunction('getMimeType', array($this, 'getMimeType')),
        );
    }

    public function dataTable($object, $objects){
    	

    	$reader = new AnnotationReader();
        $converter = new DataTableConverter($reader);
        
        $dataTableConfig = $converter->convert($object);

        return $dataTableConfig;

    }

    public function get($object, $property, $format){
    	
    	$function = 'get'.ucfirst($property);
        $value = $object->$function();

        if($format == 'datetime'){
            return $value->format('d/m/Y');
        }else
            return $value;

    }

    public function getMimeType($path){
        $file = __DIR__.'/../../../../web/'.$path;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($file);

        

    }

    public function getName()
    {
        return 'majescore_extension';
    }
}