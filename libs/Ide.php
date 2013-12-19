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


class Ide {
	
	/**
	 * Initialization of the class.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Ide( ){
		require_once(MAIN.'/core/db.php');
		
		$this->db = new DB();
		$this->errors = array();
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function GetDir ( $dir = '' ){
		if( $dir != '' ){
			$directory = MAIN.'/repos/test/'.$dir;
			// echo $directory."\n\n";
		}else{
			$directory = MAIN.'/repos/test/';
		}
		
		if( file_exists($directory) ) {
			$files = scandir($directory, 1);
			// natcasesort($files);
			
			if( count($files) > 2 ) { /* The 2 accounts for . and .. */
				$directories = array();
				$arr = array();
				// All dirs
				foreach( $files as $k => $file ) {
					if( file_exists($directory . $file) && $file != '.' && $file != '..' && is_dir($directory . $file ) ) {
						// This is directory here maybe I need to put recursive directory
						$directories[$file] = $this->GetDir($dir.$file.'/');
					} elseif (file_exists($directory . $file) && $file != '.' && $file != '..' && !is_dir($directory . $file ) ){
						//This is file
						$arr[$file] = $file;
					}
				}
				ksort($directories);
				ksort($arr);
				$arr = array_merge($directories, $arr);
				return $arr;
			}else{
				return array();
			}
		}else{
				return array();
		}
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function GetFile( $file = "index.php" ){
		if(isset($_GET['file'])){
			$file = $_GET['file'];
		}
		$file = MAIN.'/'.$file;
		return file_get_contents($file);
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Update ( $id = '', $first_name = '', $last_name = '' ){
		if( key_exists('id', $_POST)){
			$id = $_POST['id'];
			$query = $this->db->Main("SELECT login, first_name, last_name FROM users WHERE id = $1", array($id));
			
			if($query) {
				$result = $query->fetch(PDO::FETCH_ASSOC);
				if( $result ) {
					return $result;
				}else{
					return array();
				}
			} else {
				return array();
			}
			
		}else{
			return FALSE;
		}
	}
	
	
	/**
	 * This function return all errors saved in array, and empty the same array.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function GetErrors (){
		$errors = $this->errors;
		$this->errors = array();
		return array('errors' => $errors);
	}
	
	
	/**
	 * Add error to the array errors.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function AddError ( $error ){
		$this->errors[] = $error;
	}
	
  
}