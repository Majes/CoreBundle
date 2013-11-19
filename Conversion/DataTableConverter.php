<?php 

namespace Majes\CoreBundle\Conversion;
 
use Doctrine\Common\Annotations\Reader;
 
class DataTableConverter
{
    private $reader;
    private $annotationClass = 'Majes\\CoreBundle\\Annotation\\DataTable';
     
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
 
    public function convert($originalObject)
    {
        $convertedObject = array();
         
        $reflectionObject = new \ReflectionObject($originalObject);
        $i = 0;
        foreach ($reflectionObject->getMethods() as $reflectionMethod) {
            // fetch the @StandardObject annotation from the annotation reader
            $annotation = $this->reader->getMethodAnnotation($reflectionMethod, $this->annotationClass);

            if($reflectionMethod->getName() == '__construct'){
                $convertedObject['object']['isTranslatable'] = $annotation->getIsTranslatable();
                $convertedObject['object']['hasAdd'] = $annotation->getHasAdd();
                $convertedObject['object']['hasPreview'] = $annotation->getHasPreview();
                $convertedObject['object']['isDatatablejs'] = $annotation->getIsDatatablejs();
            }else{

                
                if (null !== $annotation) {
                    $propertyName = $annotation->getPropertyName();
     
                    // try to convert the value to the requested type
                    $convertedObject['column'][$i]['label'] = $annotation->getLabel();
                    $convertedObject['column'][$i]['isSortable'] = $annotation->getIsSortable();
                    $convertedObject['column'][$i]['isSearchable'] = $annotation->getIsSearchable();
                    $convertedObject['column'][$i]['column'] = $annotation->getColumn();
                    $convertedObject['column'][$i]['isMobile'] = $annotation->getIsMobile();
                    $convertedObject['column'][$i]['format'] = $annotation->getFormat();
                    $i++;
                }
            }
        }
         
        return $convertedObject;
    }
}