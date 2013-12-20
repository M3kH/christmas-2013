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


class WidgetComments {

	public $result = array();

	/**
	 * Initialization of the class.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function WidgetComments( ){
		if(isset($_SESSION['id_user'])){
			$this->result['user'] = TRUE;
			include_once (MAIN . '/libs/Comments.php');
			$this -> comments = new Comments();
			$this->result['comments'] = $this -> comments -> GetAll();
// 			var_dump($this->result['comments']);
		}else{
			$this->result['not_user'] = TRUE;
		}

		// $this->result["script"] = $minifiedCode;
	}

}