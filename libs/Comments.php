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
		$result = array();
		$_totals = array();
		$_total_res = array();

		$query = $this->db->Main($q, $p);

		if($query) {
			$_result = $query->fetchAll(PDO::FETCH_ASSOC);
			if( $_result ) {
				foreach ($_result as $key => $value) {

					$res = $_result[$key]['brings'];
					$res = json_decode($res, true);
					$totals = $this->GetTotals($res);

					$_total_res = array_merge_recursive($_total_res,$res);

					$res = array_merge_recursive($res, $totals);
					$_totals = array_merge_recursive($_totals, $totals);
					$_result[$key]['brings'] = $res;
				}

				$result["totals"] =  $this->GetGlobalTotals($_totals);
				$result["totals_result"] =  $_total_res;
				$result["details"] = $_result;
				return $result;
			}else{
				return array();
			}
		} else {
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
	public function NewLink ( ){


		if(count($_POST) > 0 && isset($_SESSION['id_user']) ){

			$user_id = $_SESSION['id_user'];

			foreach($_POST as $k => $v){
				$key = (string) $k;
				switch($key){
					case 'msg':
						$message = $v;
						break;
					case 'brings':
						$brings = $v;
						break;
				}
			}

// 			var_dump($_POST);
			if( $message != '' && $brings != '' && $user_id > 0 ){
				return $this->Create ( $message, $brings, $user_id );
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
	public function Create (  $message, $brings, $user = 0 ){

		if($user === 0){
			return false;
		}

		if(!$date){
			$dtNow = new DateTime();
			$date = $dtNow->format(DateTime::ISO8601);
		}

		if(isset($_SESSION['id_user'])){
			$user = $_SESSION['id_user'];
		}

		$query = $this->db->Main("INSERT INTO comments( `user`, `message`, `brings`, `date` ) VALUES ( ?, ?, ?, NOW() )", array($user, $message, $brings));
		$id = $this->db->db->lastInsertId('id');

		return $id;
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

	private function GetGlobalTotals( $arr ){
		$res = array();
		$res["total"] = 0;
		foreach ($arr as $k => $v) {
			if( is_array($v) && count($v) > 0 ){
				$res[$k] = 0;
				foreach( $v["total"] as $val){
// 					var_dump($val);
					$res[$k] = $res[$k] + $val*1;
					$res["total"] += $res[$k];
				}
			}
		}
		return $res;
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