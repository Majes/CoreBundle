<?php
namespace Majes\CoreBundle\Utils;

class TeelFunction
{

	private $kernel;
	public function __construct($kernel){

		$this->kernel = $kernel;

	}

	public function delTree($dir, $include_root = false, $exclude = array()) {
   		$files = array_diff(scandir($dir), array('.','..'));
    	foreach ($files as $file) {
    		if(in_array($file, $exclude)) continue;
      		(is_dir("$dir/$file")) ? self::delTree("$dir/$file", true) : unlink("$dir/$file");
    	}
    	if($include_root) return rmdir($dir);
    	else return;
  	}
}