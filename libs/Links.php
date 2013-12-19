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


class Links {
	
	/**
	 * Initialization of the class.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Links( ){
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
		// id	int(11)			No	Nessuno	AUTO_INCREMENT	 Modifica	 Elimina	 Naviga tra i valori distinti	Primaria	 Unica	 Indice	Spatial	Testo completo
	 // 2	user	int(11)			No	0		 Modifica	 Elimina	 Naviga tra i valori distinti	 Primaria	 Unica	 Indice	Spatial	Testo completo
	 // 3	url	text	latin1_swedish_ci		No	Nessuno		 Modifica	 Elimina	 Naviga tra i valori distinti	Primaria	Unica	Indice	Spatial	Testo completo
	 // 4	title	varchar(255)	latin1_swedish_ci		No	Nessuno		 Modifica	 Elimina	 Naviga tra i valori distinti	 Primaria	 Unica	 Indice	Spatial	Testo completo
	 // 5	desc	text	latin1_swedish_ci		Sì	NULL		 Modifica	 Elimina	 Naviga tra i valori distinti	Primaria	Unica	Indice	Spatial	Testo completo
	 // 6	date
	 	$userId = $this->user_id;
		if($userId === 0){
			$q = "SELECT `id`, `user`, `url`, `title`, `desc`, `date`, DATE(`date`) AS `day` FROM `links` WHERE `share_level` = '0' ORDER BY `day` DESC";
			$p = array();
		}else{
			$q = "SELECT `id`, `user`, `url`, `title`, `desc`, `date`, DATE(`date`) AS `day` FROM `links` WHERE `user` = ? ORDER BY `day` DESC";
			$p = array($userId);
		}
		$query = $this->db->Shell($q, $p);
		
		if($query) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			// $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if( $result ) {
				return $result;
			}else{
				return array();
			}
		} else {
			return array();
		}
	}

	public function InsertRandom ( $items = 50 ){
		$ids = array();
		for($i=0; $i<$items;$i++){
			$ids[$i] = $this->Create("Test titolo", "http://www.ideabile.com/", "Soluzioni per la comunicazione");
		}
		return $ids;
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Get( $search = '', $tags = array() ){
		
		if(count($_GET) > 0 ){
			
			foreach($_GET as $k => $v){
				$key = (string) $k;
				switch($key){
					case 'search':
						$search = $v;
					break;
					
					// This should be just id of the tags
					case 'tags':
						$t = explode(",", $v);
						$tags = $t;
					break;
				}
			}
		}
		
		$p = array();
		$sql = " SELECT `links`.`id`, `links`.`user`, `links`.`url`, `links`.`title`, `links`.`desc`, `links`.`date`, DATE(`date`) AS `day` ";
		$sql .= " FROM links ";
		if( count($tags) > 0 ){
			$tagsMerge = implode(",", $tags);
			$p[':tags'] = $tagsMerge;
			$sql .= " JOIN `rel_tags_links` AS `rel` ON `rel`.`id_links` = `links`.`id` AND `rel`.`id_tag` IN( :tags ) ";
		}
		
		$sql .= " WHERE 1=1 ";
		if(isset($_SESSION['id_user'])){
			$p[':user'] = $_SESSION['id_user'];
			$sql .= " AND `links`.`user` = :user ";
		}
		
		if($search != ""){
			$p[':surl'] = "%{$search}%";
			$p[':stitle'] = "%{$search}%";
			$p[':sdesc'] = "%{$search}%";
			$sql .= " AND ( `links`.`url` LIKE :surl OR `links`.`title` LIKE :stitle  OR `links`.`desc` LIKE :sdesc ) ";
		}
		$sql .= " ORDER BY `day` DESC ";
		
		$query = $this->db->Shell($sql, $p);
		
		$result = false;
		if($query) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
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
	public function NewLink ( $title = '', $url = '', $desc = '', $tags = '' ){
		
		if(count($_POST) > 0 ){
			
			foreach($_POST as $k => $v){
				$key = (string) $k;
				switch($key){
					case 'title':
						$title = $v;
					break;
					case 'url':
						$url = $v;
					break;
					case 'desc':
						$desc = $v;
					break;
					case 'tags':
						$tags = $v;
					break;
				}
			}
			
			if( $title != '' && $url != '' ){
				$this->Create ( $title, $url, $desc, $tags );
				return true;
			}
			
			return false;
			
		}else{
			return false;
		}
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Create ( $title, $url, $desc = '', $tags = '', $date = false, $user = 0 ){
		
		if(!$date){
			$dtNow = new DateTime();
			$date = $dtNow->format(DateTime::ISO8601);
		}
		
		if(isset($_SESSION['id_user'])){
			$user = $_SESSION['id_user'];
		}
		
		$query = $this->db->Shell("INSERT INTO links( `user`, `url`, `title`, `desc`, `date` ) VALUES ( ?, ?, ?, ?, ?)", array($user, $url, $title, $desc, $date));
		$id = $this->db->db->lastInsertId('id');
		
		require_once(MAIN.'/libs/Tags.php');
		$tagsClass = new Tags();
		$tagsClass->AddMultyTagLink($id,$tags);
		return $id;
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Update ( $id = '', $user = '', $url = '', $title = '' ){
		if( key_exists('id', $_POST)){
			$id = $_POST['id'];
			$query = $this->db->Shell("SELECT login, first_name, last_name FROM users WHERE id = $1", array($id));
			
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
