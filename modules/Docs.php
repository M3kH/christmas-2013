<?php
/**
 * Users managements
 *
 * @category   Login / Registration
 * @package    Ideabile Framework
 * @author     Mauro Mandracchia <info@ideabile.com>
 * @copyright  2013 - 2014 Ideabile
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Release: 0.1a
 * @link       http://www.ideabile.com
 * @see        -
 * @since      -
 * @deprecated -
 */


class WidgetDocs {
	
	public $result = array();
	
	/**
	 * Initialization of the class.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function WidgetDocs( ){
		
		if (!isset($this -> ide)) {
			include_once (MAIN . '/libs/Ide.php');
			$this -> ide = new Ide();
		}
		$arr = array();
		$arr["file"] = $this -> ide -> GetFile();
		
		$this->result = $arr;
		// var_dump($this->result);
	}
  
}