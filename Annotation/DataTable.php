<?php 
namespace Majes\CoreBundle\Annotation;
 
/**
 * @Annotation
 */
class DataTable
{
    private $propertyName;
    private $label = 'string';
    private $isSortable = 1;
    private $isSearchable = 1;
    private $isMobile = 1;
    private $isTranslatable = 1;
    private $hasAdd = 1;
    private $hasPreview = 1;
    private $isDatatablejs = 1;
    private $ajaxUrl = '';
    private $format = 'text';
    private $column;
     
    public function __construct($options)
    {

        if (isset($options['value'])) {
            $options['propertyName'] = $options['value'];
            unset($options['value']);
        }
         
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }
             
            $this->$key = $value;
        }
    }
     
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getColumn()
    {
        return $this->column;
    }
     
    public function getLabel()
    {
        return $this->label;
    }

    public function getIsSortable()
    {
        return $this->isSortable;
    }

    public function getIsSearchable()
    {
        return $this->isSearchable;
    }

    public function getIsMobile()
    {
        return $this->isMobile;
    }

    public function getIsTranslatable()
    {
        return $this->isTranslatable;
    }

    public function getHasAdd()
    {
        return $this->hasAdd;
    }

    public function getHasPreview()
    {
        return $this->hasPreview;
    }

    public function getIsDatatablejs()
    {
        return $this->isDatatablejs;
    }

    public function getAjaxUrl()
    {
        return $this->ajaxUrl;
    }

    public function getFormat()
    {
        return $this->format;
    }
}