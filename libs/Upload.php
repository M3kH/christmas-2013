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


class Upload {
	
	/**
	 * Initialization of the class.
	 *
	 * @return -
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function Upload( ){
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function UploadFile ( ){

		error_reporting(E_ALL | E_STRICT);
		require(MAIN.'/ext/UploadHandler.ext.php');
		$upload_handler = new UploadHandlerExt(array(), false);
		$files = $upload_handler->post();
		// var_dump($files);
		$f = $files['files'];
		foreach( $f as $k => $v){
			$type = $v->type;
			if( $type === 'image/gif'){
				$this->ExtractGif($v->name);
			}
		}
		
		$files =  (array) $files;
		foreach ($files['files'] as $k => $v) {
			$files['files'][$k] = (array) $files['files'][$k];
			$name = $files['files'][$k]["name"];
			$name = explode(".", $name);
			$files['files'][$k]["short"] = $name[0];
		}
		
		
		return $files;
		
	}
	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function ExtractGif ( $file_name ){
		
		if(file_exists(MAIN."/files/".$file_name)){
				
			require_once MAIN.'/ext/GifFrameExtractor.php';
			
			$file = MAIN."/files/".$file_name;
			$name = explode(".", $file_name);
	
			mkdir(MAIN."/files/".$name[0]);
			
		    $gfe = new GifFrameExtractor();
			$is_animated = $gfe->isAnimatedGif($file);
			
			if($is_animated){
				
		    	$frames = $gfe->extract($file);
				for($i=0;$i<count($frames);$i++){
					$this->CreateFrame($name[0], $frames[$i], $i);
					
					if($i == (count($frames)-1)){
						$this->CreateSprite($name[0], $i+1);
					}
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
	public function CreateFrame ( $name = '', $file = array(), $frame = '' ){
		
		if(!file_exists(MAIN."/files/".$name."/frame_0".$frame.".gif")){
			$f = MAIN."/files/".$name."/frame_0".$frame.".gif";
			// Open the file to get existing content
			$img = $file["image"];
			imagegif($img, $f);
		}
		
	}
	
	 
	function mergeImages($images) {
		$imageData = array();
		$len = count($images);
		$wc = $len;
		$hc = 1;
		$maxW = array();
		$maxH = array();
		for($i = 0; $i < $len; $i++) {
			$imageData[$i] = getimagesize($images[$i]);
			$found = false;
			for($j = 0; $j < $i; $j++) {
				if ( $imageData[$maxW[$j]][0] < $imageData[$i][0] ) {
					$farr = $j > 0 ? array_slice($maxW, $j-1, $i) : array();
					$maxW = array_merge($farr, array($i), array_slice($maxW, $j));
					$found = true;
					break;
				}
			}
			if ( !$found ) {
				$maxW[$i] = $i;
			}
			$found = false;
			for($j = 0; $j < $i; $j++) {
				if ( $imageData[$maxH[$j]][1] < $imageData[$i][1] ) {
					$farr = $j > 0 ? array_slice($maxH, $j-1, $i) : array();
					$maxH = array_merge($farr, array($i), array_slice($maxH, $j));
					$found = true;
					break;
				}
			}
			if ( !$found ) {
				$maxH[$i] = $i;
			}
		}
	 
		$width = 0;
		for($i = 0; $i < $wc; $i++) {
			$width += $imageData[$maxW[$i]][0];
		}
	 
		$height = 0;
		for($i = 0; $i < $hc; $i++) {
			$height += $imageData[$maxH[$i]][1];
		}
	 
		$im = imagecreatetruecolor($width, $height);
    	$bgc = imagecolorallocate ($im, 255, 255, 255);
   		imagefilledrectangle ($im,0, 0, $width, $height, $bgc);
	 
		$wCnt = 0;
		$startWFrom = 0;
		$startHFrom = 0;
		for( $i = 0; $i < $len; $i++ ) {
			$tmp = imagecreatefromgif($images[$i]);

			imagecopyresampled($im, $tmp, $startWFrom, $startHFrom, 0, 0, $imageData[$i][0], $imageData[$i][1], $imageData[$i][0], $imageData[$i][1]);
			$wCnt++;
			if ( $wCnt == $wc ) {
				$startWFrom = 0;
				$startHFrom += $imageData[$maxH[0]][1];
				$wCnt = 0;
			} else {
				$startWFrom += $imageData[$i][0];
			}
		}
	 
	 
		return $im;
	}

	
	/**
	 * This function check if the user already exist in the DB.
	 *
	 * @return BOOLEAN
	 * @see Main (DB)
	 * @author Mauro Mandracchia <info@ideabile.com>
	 */
	public function CreateSprite ( $name = '', $frame = '' ){
		
		// $frame = $frame-1;
		$path = realpath(MAIN."/files/".$name."/");
		// echo "montage ".MAIN."/files/".$name."/frame_*.gif  -tile x$frame  -geometry +1+1  sprite.gif";
		// echo "/Users/mm/ImageMagick-6.8.5/bin/montage $path/frame_0%d.gif[0-$frame] -tile x$frame  -geometry +1+1  $path/sprite.gif";
	    // exec("montage $path/frame_0%d.gif[0-$frame] -tile ".$frame."x1  -geometry +1+1  $path/sprite.gif", $output);
	    exec("montage $path/frame_0*.gif -tile ".$frame."x1  -geometry +1+1  $path/sprite.png", $output);
		// var_dump($output);
		// $arr = array();
		// for($i=0; $i<$frame;$i++){
			// $arr[] = MAIN."/files/".$name."/frame_0$i.gif";
		// }
		// $img = $this->mergeImages($arr);
		// imagegif($img, MAIN."/files/".$name."/sprite.gif");
		
		// $Montage = $Icons->montageImage(new imagickdraw(), "3x2+0+0", "34x34+3+3", imagick::MONTAGEMODE_UNFRAME, "0x0+0+0");
      	// $Canvas->compositeImage($Montage, $Montage->getImageCompose(), 5, 5);
		
	}
	
  
}