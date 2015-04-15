<?php 
namespace Majes\CoreBundle\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use Majes\CoreBundle\Conversion\DataTableConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoreExtension extends \Twig_Extension
{
    private $_container;
    private $_em;
    private $_router;

    public function __construct(ContainerInterface $container = null, $em, $router){
        $this->_em = $em;
        $this->_container = $container;
        $this->_router = $router;
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
            new \Twig_SimpleFunction('getRoutes', array($this, 'getRoutes')),
            new \Twig_SimpleFunction('dataTableJson', array($this, 'dataTableJson'))
        );
    }

    public function dataTable($object){
        

        $reader = new AnnotationReader();
        $converter = new DataTableConverter($reader);
        
        $dataTableConfig = $converter->convert($object);

        return $dataTableConfig;

    }

    public function dataTableJson($dataTemp, $draw = 1){

        $config = $this->dataTable($dataTemp['object']);
        $params = array();
        $rows = array();
        $count = 0;
        foreach ($dataTemp['datas'] as $data) {

            //config actions
            $actions = '';
            if($config['object']['hasPreview']) $actions .= '<a class="table-actions" href=""><i class="icon-eye-open"></i></a>';
            $params['id'] = $data->getId();
            if(isset($dataTemp['urls']['params']))
                foreach($dataTemp['urls']['params'] as $key => $param)
                    $params[] = array($key => $coreTwig->get($data, $param['key']));

            if(isset($dataTemp['urls']['edit']))
                $actions .= '<a href="'.$this->_router->generate($dataTemp['urls']['edit'], $params).'" class="table-actions"><i class="icon-pencil"></i></a>';

            if(isset($dataTemp['urls']['delete']))
                if(method_exists($data, 'getIsSystem') && !$data['isSystem']) $actions .= '<a href="'.$this->_router->generate($dataTemp['urls']['delete'], $params).'" class="table-actions" onclick="return CoreAdmin.Common.confirmDelete(\''.$dataTemp['message'].'\')"><i class="icon-trash"></i></a>';
                elseif(!method_exists($data, 'getIsSystem')) $actions .= '<a href="'.$this->_router->generate($dataTemp['urls']['delete'], $params).'" class="table-actions" onclick="return CoreAdmin.Common.confirmDelete(\''.$dataTemp['message'].'\')"><i class="icon-trash"></i></a>';

            unset($row);
            foreach($config['column'] as $config_item){
                $row[] = $this->get($data, $config_item['column'], $config_item['format']);
            }
            $row[] = $actions;
            $rows[] = $row;
            $count++;
        }

        return array("draw" => $draw, "recordsTotal" => count($dataTemp['datas']),"recordsFiltered" => count($dataTemp['datas']), "data" => $rows);

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
        if(is_numeric($id))
            $list = $this->_em->getRepository('MajesCoreBundle:ListBox')->findOneBy(array('deleted' => false, 'id' => $id));
        else
            $list = $this->_em->getRepository('MajesCoreBundle:ListBox')->findOneBy(array('deleted' => false, 'name' => $id));

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
