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


class Comments {
	
	/**
	 * Initialization of the class.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Comments( ){
		require_once(MAIN.'/core/db.php');
		$this->db = new DB();
		$this->errors = array();
		if(isset($_SESSION['id_user'])){
			$this->user_id = (int) $_SESSION['id_user'];
		}else{
			$this->user_id = 0;
		}
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function GetAll ( ){
		
		$q = "SELECT `comments`.`id`, `comments`.`message`, `comments`.`brings`, `comments`.`date`,
		`users`.`name`, `users`.`last_name`, `users`.`id` as `users`  
FROM `comments` JOIN `users` ON `comments`.`user` = `users`.`id` ORDER BY `date` ASC";
		$p = array();
		
		$query = $this->db->Main($q, $p);
		
		if($query) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if( $result ) {
				foreach ($result as $key => $value) {
					$res = $result[$key]['brings'];
					$res = json_decode($res, true);
					$totals = $this->GetTotals($res);
					$res = array_merge_recursive($res, $totals);
					
					$result[$key]['brings'] = $res;
				}
				return $result;
			}else{
				return array();
			}
		} else {
			return array();
		}
	}
	
	private function GetTotals( $arr ){
		$res = array();
		foreach ($arr as $k => $v) {
			if( is_array($v) && count($v) > 0 ){
				$res[$k]["total"] = 0;
				foreach( $v as $val){
					// var_dump($val);
					$res[$k]["total"] = $res[$k]["total"] + $val["qt"]*1;
				}
			}
		}
		return $res;
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function GetByLink ( $id = '' ){
		$id = $_POST['id'];
		$query = $this->db->Main("SELECT `tags`.`id`, `tags`.`name`, (`rel_tags_links`.`id_link` IS NOT NULL) AS `id_link` FROM `tags` LEFT OUTER JOIN `rel_tags_links` ON `rel_tags_links`.`id_link` = ? GROUP BY `tags`.`id`, `tags`.`name` ORDER BY `tags`.`name` ASC ", array($id));
		
		$result = false;
		if($query) {
			$result = $query->fetch(PDO::FETCH_ASSOC);
		}
		if( $result ) {
			return $result;
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
	public function GetByTag ( $tag = '' ){
		$query = $this->db->Main("SELECT `tags`.`id` FROM `tags` WHERE `tags`.`name` = ?", array($tag));
		// var_dump($query);
		
		$result = false;
		if($query) {
			$result = $query->fetch(PDO::FETCH_ASSOC);
		}
		
		if( $result ) {
			return $result['id'];
		}else{
			return $this->Create( $tag );
		}
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Create ( $tag ){
		$query = $this->db->Main("INSERT INTO tags( `name` ) VALUES ( ? )", array($tag));
		return $this->db->db->lastInsertId('id');
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @param $tags should be a string = "one, two, three, four"
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function AddMultyTagLink ( $idLink = '', $tags = '' ){
		$tags = explode(",", $tags);
		foreach( $tags as $k => $v ){
			$tag = trim($v);
			// var_dump($tag);
			if($tag != ''){
				$res = $this->AddTagLink($idLink, $tag);
				if(!$res){
					$this->AddError("{$tag}, is invalid.");
				}
			}
		}
		
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function AddTagLink ( $idLink = '', $tag = '' ){
			
		// Here we need to check if the tag is already exist
		$idTag = $this->GetByTag($tag);
		
		$query = $this->db->Main("SELECT `id_link`, `id_tag` FROM `rel_tags_links` WHERE `id_link` = ?", array($idLink));
		$result = false;
		if($query) {
			$result = $query->fetch(PDO::FETCH_ASSOC);
		}
		
		if( $result ) {
			return TRUE;
		}else{
			if( $idTag && $idLink != ''){
				$query = $this->db->Main("INSERT INTO rel_tags_links( `id_link`, `id_tag` ) VALUES ( ?, ? )", array($idLink, $idTag));
				return TRUE;
			}else{
				require FALSE;
			}
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
