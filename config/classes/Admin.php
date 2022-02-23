<?php
error_reporting(0);
date_default_timezone_set('Asia/Calcutta');
class Admin {
    public $config;
	 function __construct() {
             
            $this->config  = include(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'/config'.DIRECTORY_SEPARATOR.'config.php');
            $this->main    = FALSE;	
            $config 	   = $this->getConfig();
            $this->website = "https://krea.edu.in/";
	}
        
    function getConfig($name='mysql', $default=null) {
        return $this->config[$name] ? $this->config[$name] : $default;
    }
	
	/* Cross Site Scripting*/

	function removeXSS($val) {
	    $val     = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
	    $val     = stripslashes($val); 
	    $val     = strip_tags($val);
	    $val     = addslashes($val); 
	    $val     = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val); 
	    $search  = 'abcdefghijklmnopqrstuvwxyz';
	    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $search .= '1234567890!@#$%^&*()';
	    $search .= '~`";:?+/={}[]-_|\'\\';

	    for ($i = 0; $i < strlen($search); $i++) {
	        $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); 
	        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); 
	    }
	    
	    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title');
	    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	    $ra = array_merge($ra1, $ra2);

	    $found = true; 
	    while ($found == true) {
	        $val_before = $val;
	        for ($i = 0; $i < sizeof($ra); $i++) {
	            $pattern = '/';
	            for ($j = 0; $j < strlen($ra[$i]); $j++) {
	                if ($j > 0) {
	                $pattern .= '(';
	                $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
	                $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
	                $pattern .= ')?';
	                }
	                $pattern .= $ra[$i][$j];
	            }
	            $pattern .= '/i';
	            $replacement =  substr($ra[$i], 0, 2).''.substr($ra[$i], 2); 
	            $replacement = htmlentities($replacement);
	            $val = preg_replace($pattern, $replacement, $val); 
	            if ($val_before == $val) {
	                $found = false;
	            }
	        }
	    }
	    return $val;
	}
   
    /***** Prevent from unwanted characters *****/

    function fetchDBdetails($connection = '', $CompanyID = ''){
    	$result = 'Timeout Not Found';
		if($connection !=''){
    		$config 		 = $this->getConfig();
            $this->p_db_host = $config[$connection]['hostname'];
            $this->p_db_user = $config[$connection]['user'];
            $this->p_db_pass = $config[$connection]['password'];
            $this->p_db_name = $config[$connection]['dataBase'];

            $result = array('hostname'=>$this->p_db_host,'username'=>$this->p_db_user,'password'=>$this->p_db_pass,'database'=>$this->p_db_name);
        } else {
        	$result = "Time out.Kindly restart the application. local db";
        }
    	
    	return $result;    
    }

	/*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     * @pdo means database connection
     * @$type = 1 Normally excuting the query and if $type= 2 passed it returns the sql query
     */
    function insertPdo($table,$data,$pdo,$type = 1){
    	if(!empty($data) && is_array($data)){
    		if($type == 1){
	            $columns 		= '';
	            $values  		= '';
	            $i 				= 0;
	            $column  		= implode(',', array_keys($data));
	            $value 			= ":".implode(',:', array_keys($data)); //value will change :name
	            $sql 			= "INSERT INTO `".$table."` (".$column.") VALUES (".$value.")";
	            /*echo "<pre>".$sql.'<br>';print_r($data);echo"</pre>";*/
	            $query 			= $pdo->prepare($sql);

	            foreach($data as $key => $val){
	            	$val = $this->removeXSS($val);
	            	if(is_numeric($val)){ 
				    	$param = PDO::PARAM_INT; 
				    }elseif(is_bool($val))   { 
				    	$param = PDO::PARAM_BOOL; 
				    } elseif(is_null($val)){
				     	$param = PDO::PARAM_NULL; 
				    } elseif(is_string($val)) {
				     	$param = PDO::PARAM_STR; 
				 	} else { 
				 		$param = PDO::PARAM_STR;
				 	}
					if($param){
						$query->bindValue(':'.$key, $val);
					}
	                 //$query->bindValue();
	            }

	            $insert 		= $query->execute();
	            return $insert?$pdo->lastInsertId():false;
	        } else if($type == 2){ //htmltemplate only changes started
	            $columns 		= '';
	            $values  		= '';
	            $i 				= 0;
	            $column  		= implode(',', array_keys($data));
	            $value 			= ":".implode(',:', array_keys($data)); //value will change :name
	            $sql 			= "INSERT INTO `".$table."` (".$column.") VALUES (".$value.")";
	            /*echo "<pre>".$sql.'<br>';print_r($data);echo"</pre>";*/
	            $query 			= $pdo->prepare($sql);

	            foreach($data as $key => $val){
	            	//$val = $this->removeXSS($val);
	            	if(is_numeric($val)){ 
				    	$param = PDO::PARAM_INT; 
				    }elseif(is_bool($val))   { 
				    	$param = PDO::PARAM_BOOL; 
				    } elseif(is_null($val)){
				     	$param = PDO::PARAM_NULL; 
				    } elseif(is_string($val)) {
				     	$param = PDO::PARAM_STR; 
				 	} else { 
				 		$param = PDO::PARAM_STR;
				 	}
					if($param){
						$query->bindValue(':'.$key, $val,$param);
					}
	                 //$query->bindValue();
	            }

	            $insert 		= $query->execute();
	            return $insert?$pdo->lastInsertId():false; //htmltemplate only changes complted
	        } else {
	        	$columns 		 = '';
	            $value  		 = '';
	        	$column          = implode(',', array_keys($data));
			    $value           = implode(',', array_values($data)); //value will change :name
			    $sql 			= "INSERT INTO `".$table."` (".$column.") VALUES (".$this->removeXSS($value).")";
			    return "<pre>".$sql."</pre>";
	        }
        } else {
            return false;
        }
    }

    /*
     * Update data into the database with conditions
     * @table name will be string
     * @data will be array
     * @conditions will be array
     * @pdo means database connection with pdo
     * @$type = 1 Normally excuting the query and if $type= 2 passed it returns the sql query dont execute the query returns the query
     */

    function updatePdo($table,$data,$conditions,$pdo,$type=1){
    	if(!empty($data) && is_array($data)){
    		
            $colvalSet = '';
            $whereSql  = '';
            $i = 0;
           
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';

                //newly added for Templates started
                if($type == 2){
                	$val = $val;
                } /*else {
                	//$val = $this->removeXSS($val);
                	$val;
                } */
                //newly added for Templates completed
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){ 
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$this->removeXSS($value)."'";
                    $i++;
                }
            }
            if($type == 2){ //newly added for Templates started
            	$sql    = "UPDATE `".$table."` SET ".$colvalSet.$whereSql;
            	$query  = $pdo->prepare($sql);
	            $update = $query->execute();
	            return $update?$query->rowCount():false; //newly added for Templates completed
            } else if($type == 1){
            	$sql    = "UPDATE `".$table."` SET ".$colvalSet.$whereSql;
            	$query  = $pdo->prepare($sql);
	            $update = $query->execute();
	            return $update?$query->rowCount():false;
            } else if($type == 3){ // Writing query on model side and prepare query here
            	$query  = $pdo->prepare($table);
            	if(count($data) > 0){
					foreach($data as $key => $value){
						$value = $this->removeXSS($value);
					    if(is_numeric($value)){ 
					    	$param = PDO::PARAM_INT; 
					    }elseif(is_bool($value))   { 
					    	$param = PDO::PARAM_BOOL; 
					    } elseif(is_null($value)){
					     	$param = PDO::PARAM_NULL; 
					    } elseif(is_string($value)) {
					     	$param = PDO::PARAM_STR; 
					 	} else { 
					 		$param = PDO::PARAM_STR;
					 	}
						if($param){
							$query->bindValue($key, $value, $param);
						}
					}
				}
	            $update = $query->execute();
	            return $update?$query->rowCount():false;
            } else {
            	$sql    = "UPDATE `".$table."` SET ".$colvalSet.$whereSql;
            	return "<pre>".$sql."</pre>";
            }
	        
        } else {
            return false;
        }
    }

    function companyPDODBConnect($credentials){
		$dbHostName   = "localhost";//$credentials['hostname'];
		$dbUserName   = "root";//$credentials['username'];
		$dbPassword   = "";//$credentials['password'];
		$dataBaseName = "gsb_alumni_db";//$credentials['database'];
		try {
			$pdo = new PDO("mysql:host=".$dbHostName.";dbname=".$dataBaseName."", $dbUserName, $dbPassword,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $pdo;
			$pdo = null;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}

	/*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     * @pdo means database connection
     * Pass query and excute
     */
	function selectPDO($sql_query,$data=array(),$conditions=array(),$pdo){
		$query 			= $pdo->prepare($sql_query);
		if(count($data) > 0){
			foreach($data as $key => $value){
			    if(is_numeric($value)){ 
			    	$param = PDO::PARAM_INT; 
			    }elseif(is_bool($value))   { 
			    	$param = PDO::PARAM_BOOL; 
			    } elseif(is_null($value)){
			     	$param = PDO::PARAM_NULL; 
			    } elseif(is_string($value)) {
			     	$param = PDO::PARAM_STR; 
			 	} else { 
			 		$param = FALSE;
			 	}
				if($param){
					$query->bindValue($key, $value, $param );
				}
			}
			$query->execute();
		} else {
			$query->execute();
		}
        
        //$st = $conditions['return_type'];
        if(isset($conditions['return_type']) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $result_data = $query->rowCount();
                    break;
                case 'single':
                    $result_data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                case 'row_count':
                	$result_data = $query->fetchColumn();
                default:
                    $result_data = '';
            }
        } else {
            if($query->rowCount() > 0){
                $result_data = $query->fetchAll(PDO::FETCH_ASSOC);
            } else {
            	$result_data = '';
            }
        }
        return !empty($result_data)?$result_data:array();
	}

	function deletePDO($sql_query,$data,$pdo){
		$query 			= $pdo->prepare($sql_query);
		if(count($data) > 0){
			foreach($data as $key => $value){
			    if(is_numeric($value)){ 
			    	$param = PDO::PARAM_INT; 
			    }elseif(is_bool($value))   { 
			    	$param = PDO::PARAM_BOOL; 
			    } elseif(is_null($value)){
			     	$param = PDO::PARAM_NULL; 
			    } elseif(is_string($value)) {
			     	$param = PDO::PARAM_STR; 
			 	} else { 
			 		$param = FALSE;
			 	}
				if($param){
					$query->bindValue($key, $value, $param );
				}
			}
		} 
		$delete = $query->execute();
       return $delete?$query->rowCount():false;
	}

/******Encryption and Decryption section started******/
    var $secret_key = 'AA74CllIIIer2BBRT9GDYE84735136HEA7B63C27';
	var $secret_iv  = '5fgf5HJ5g8f8de827';
	var $ciphering  = "AES-256-CBC";
	
	var $options    = 0;

	function aes_encrypt_string($string = "") {
		return $this->encrypt_decrypt($string, $action = 'encrypt');
	}

	function aes_decrypt_string($string = "") {
		return $this->encrypt_decrypt($string, $action = 'decrypt');
	}

	function encrypt_decrypt($string, $action = 'encrypt'){
	    $encrypt_method = "AES-256-CBC";
	    $secret_key     = 'AA74CIFMRGSBkjefhGDYE84735136HEA7B63C27'; 
	    $secret_iv      = '5fgf5HJ5g8f8de827'; // user define secret key
	    $key            = hash('sha256', $secret_key);
	    $iv             = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
	    if ($action == 'encrypt') {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    } else if ($action == 'decrypt') {
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }
	    return $output;
	}


	function encrypt_random_digits($limit) {
	    $length = random_bytes($limit);
  		return bin2hex($length);
	}
/******Encryption and Decryption section completed******/
/****** Token section started*******/
	function token_expire_time($time=15){
		date_default_timezone_set("Asia/Kolkata"); //default india time
	 	$selectedTime = date('h:i:s');//"9:15:00";
		$endTime 	  = strtotime("+".$time." minutes", strtotime($selectedTime));
		$new_time 	  =  date('h:i:s', $endTime);
		return $new_time;
	}

	function local_current_time(){
		date_default_timezone_set("Asia/Kolkata"); //default india time
	 	$selectedTime = date('h:i:s');
	 	return $selectedTime;
	}

	function generate_Anti_CSRF_token($expires, $content){
    	$key   = '4410442012345612';////$this->encrypt_random_digits(32);//'key code secret';  
    	$token = [
	        'expires' => $expires,
	        'content' => $content, 
	        'ip' 	  => $_SERVER['REMOTE_ADDR'], 
	    ];
	    $encode = json_encode($token);
	    return hash_hmac('sha256', $encode, $key);
	}

    function hash_equals($str1, $str2){
    	$version = phpversion();
    	$old_ver = '5.6.0';
    	if($version >= $old_ver){
    		return hash_equals($str1, $str2); 
	    } else {
	        if(strlen($str1) != strlen($str2)){
	            return false;
	        } else {
	            $res = $str1 ^ $str2;
	            $ret = 0;
	            for($i = strlen($res) - 1; $i >= 0; $i--){
	                $ret |= ord($res[$i]);
	            }
	            return !$ret;
	        }
	    }
	}
	/****** Token section completed*******/
	function get_total_percentage( $number, $everything, $decimals = 2 ){
        return round( $number / $everything * 100, $decimals );
    }
    
    function remove_Form_values($post){
        if(count($post)){
           /* foreach($post as $key => $val){
                //unset($_POST[$key]);
                $_POST = array();
            }*/
            unset($_POST);
            $_POST = array();
        }
    }
    
    function removeURLFilter($string){
        if(isset($string) && $string!=''){
            $string = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', ' ', $string);
        }
        return $string;
    }

    function executeQueryOnly($sql_query,$pdo){
		$query = $pdo->prepare($sql_query);
		if ($query->execute()) {
			$response = "success";
		} else {
			$response = "error";
		}
        return $response;
	}

    function latest_database_Update($param){
    	/*$command    = "mysql --user={$dbUser} --password='{$dbPass}' ". "-h {$dbHost} -D {$dataBase} < {$script_path}";

		$output = shell_exec($command.$this->mig_path.$dataBase.'.sql');
		return $output;*/
		$response = array('status'=>'error','msg'=>'No execution found');
		$dataBase  = $param['database'];
		$query     = '';
		$sqlScript = file($this->website.$dataBase.'.sql');
		if(count($sqlScript) > 0){
			foreach ($sqlScript as $line)	{
				$startWith = substr(trim($line), 0 ,2);
				$endWith   = substr(trim($line), -1 ,1);
				if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
					continue;
				}
					
				$query = $query.$line;
				if ($endWith == ';') {
					$result   = $this->executeQueryOnly($query,$dataBase);
					if($result=="success"){
						$response = array('status'=>'success','msg'=>'Executed Successfully');
					} else {
						$response = array('status'=>'error','msg'=>'Execution Error');
					}
					$query= '';		
				}
			}
		}
		return $response;
    }

    function convertimagetobase64encode($image){
    	if(!empty($image) && $image!=''){
    		$image = $image;
    	} else {
    		$image = $this->website.'images/logo1000.jpg';
    	}
		$imageData = base64_encode(file_get_contents($image));
		$src = 'data: '.$this->get_mime_type($image).';base64,'.$imageData;
		
		return $src;
    }

    function get_mime_type($filename) {
	    $idx           = explode('.', $filename);
	    $count_explode = count($idx);
	    $idx           = strtolower($idx[$count_explode-1]);
	    $mimet = array( 
	        'txt' => 'text/plain',
	        'htm' => 'text/html',
	        'html' => 'text/html',
	        'php' => 'text/html',
	        'css' => 'text/css',
	        'js' => 'application/javascript',
	        'json' => 'application/json',
	        'xml' => 'application/xml',
	        'swf' => 'application/x-shockwave-flash',
	        'flv' => 'video/x-flv',

	        /* images*/
	        'png' => 'image/png',
	        'jpe' => 'image/jpeg',
	        'jpeg' => 'image/jpeg',
	        'jpg' => 'image/jpeg',
	        'gif' => 'image/gif',
	        'bmp' => 'image/bmp',
	        'ico' => 'image/vnd.microsoft.icon',
	        'tiff' => 'image/tiff',
	        'tif' => 'image/tiff',
	        'svg' => 'image/svg+xml',
	        'svgz' => 'image/svg+xml',

	        /* archives*/
	        'zip' => 'application/zip',
	        'rar' => 'application/x-rar-compressed',
	        'exe' => 'application/x-msdownload',
	        'msi' => 'application/x-msdownload',
	        'cab' => 'application/vnd.ms-cab-compressed',

	        /*audio/video*/
	        'aac' => 'audio/aac',
	        'mp3' => 'audio/mpeg',
	        'qt'  => 'video/quicktime',
	        'mov' => 'video/quicktime',
	        'avi' =>'video/x-msvideo',
	        'mp4' =>'video/mp4',
	        'mpeg' =>'video/mpeg',
	        'oga' =>'audio/ogg',
	        'ogv' =>'video/ogg',
	        'ts' =>'video/mp2t',
	        'wav' =>'audio/wav',
	        'weba' =>'audio/webm',
	        'webm' =>'video/webm',
	        '3gp' => 'video/3gpp',

	        /*adobe*/
	        'pdf' => 'application/pdf',
	        'psd' => 'image/vnd.adobe.photoshop',
	        'ai' => 'application/postscript',
	        'eps' => 'application/postscript',
	        'ps' => 'application/postscript',

	        /* ms office*/
	        'doc' => 'application/msword',
	        'rtf' => 'application/rtf',
	        'xls' => 'application/vnd.ms-excel',
	        'ppt' => 'application/vnd.ms-powerpoint',
	        'docx' => 'application/msword',
	        'xlsx' => 'application/vnd.ms-excel',
	        'pptx' => 'application/vnd.ms-powerpoint',


	        /* open office*/
	        'odt' => 'application/vnd.oasis.opendocument.text',
	        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	    );

	    if (isset($mimet[$idx])) {
	     	return $mimet[$idx];
	    }  else {
	     	return 'application/octet-stream';
	    }
	}

	function createAvatarImage($string,$id)
	{
	    require_once __DIR__."/../../env.php";
	    $imageFilePath     = file_up_path."<?php echo MAIN_BASE_URL;?>/assets/img/avatar/".$id.".png";
	    $font              = file_up_path."<?php echo MAIN_BASE_URL;?>/assets/fonts/gd-files/gd-font.gdf";
	    $avatar            = imagecreatetruecolor(60,60);
	    $bg_color          = imagecolorallocate($avatar, 211, 211, 211);
	    imagefill($avatar,0,0,$bg_color);
	    $avatar_text_color = imagecolorallocate($avatar, 0, 0, 0);
	    $font              = imageloadfont($font);
	    imagestring($avatar, $font, 10, 10, $string, $avatar_text_color);
	    imagepng($avatar, $imageFilePath);
	    imagedestroy($avatar);

	    return $imageFilePath;
	}

	function make_avatar($character,$id)
	{
		require_once __DIR__."/../../env.php";
	    $path  = file_up_path."<?php echo MAIN_BASE_URL;?>/assets/img/avatar/".$id.".png";
	    $font  = file_up_path."<?php echo MAIN_BASE_URL;?>/assets/fonts/gd-files/arial.ttf";
		$image = imagecreate(200, 200);
		$red   = rand(0, 255);
		$green = rand(0, 255);
		$blue  = rand(0, 255);
	    imagecolorallocate($image, $red, $green, $blue);
	    $textcolor = imagecolorallocate($image, 255,255,255);

	    imagettftext($image, 100, 0, 55, 150, $textcolor, $font, $character);
	    //header("Content-type: image/png");
	    imagepng($image, $path);
	    imagedestroy($image);
	    return $path;
	}


}
?>