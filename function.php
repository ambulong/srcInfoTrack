<?php
function echotest(){echo $GLOBALS['imgt_admin_name']."<br>".$_SESSION['user'];}

/*
function chk_api_authid($authid){};//check api auth id is exitst or not

function json_data($state,$data){};//print json data

function confirm_user($username,$password){};//Confirm user

function chklogin($username){};//Check login

function chktoken($token,$true_token){};//Check token

function get_trackinfo($start,$end,$orderby = 0,$DESC = true,$proj_id = NULL){};//Get trackinfo
function get_trackinfo_num($proj_id = NULL){};//Get trackinfo item number

function filter_trackinfo($filter_type = 0,$string,$start,$end,$exactly = TRUE){};//Filter trackinfo
function filter_trackinfo_num($filter_type = 0,$string,$exactly = TRUE){};//Filter trackinfo item number

function get_projects(){};//get all project id and name

function get_project($id){};//get project detail info

function create_project($name,$img[],$desc,$type = 0,$value  = NULL,$filter = 0){};//Create new project

function update_project($id,,$img[],$desc,$name,$type = 0,$value  = NULL,$filter = 0){};//update project

function delete_project($id){};//Delete project

function insert_trackinfo($proj_id,$content,$track_url,$domain,$ip){};//Insert new trackinfo

function delete_trackinfo($id){};//Delete trackinfo

function adaddslashes($string, $force = 0){};//Add slashes

function inintval($int){};//Int value

function bigger_than($than,$num){};//$num >= $than else return $than
*/

function chk_api_authid($authid){	//check api auth id is exitst or not
	$authid = adaddslashes($authid);
	$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."project WHERE `authid` = '$authid'";
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows >= 1){
		$row = mysql_fetch_array($result);
		return $row;
	}else{
		return 0;
	}
}

function json_data($state,$data){	//print json data
	$arr = array ("state" => $state, "data" => $data);
	echo json_encode($arr);
}

function confirm_user($username,$password){	//Confirm user
	$password = md5($username.md5($password));
	if($username == $GLOBALS['imgt_admin_name'] && $password == $GLOBALS['imgt_admin_password']){
		return 1;
	}else{
		return 0;
	}
}

function chklogin($username){	//Check login
	if($username == $GLOBALS['imgt_admin_name']){
		return 1;
	}else{
		return 0;
	}
}

function chktoken($token,$true_token){	//Check token
	if($token == $true_token){
		return 1;
	}else{
		return 0;
	}
}

function settoken(){	//Set token
	$token = md5(str_shuffle($_SERVER['REMOTE_ADDR'].rand().time()));
	return $token;
}

function get_trackinfo($start,$end,$orderby = 0,$DESC = true,$proj_id = NULL,$domain = NULL){	//Get trackinfo
/*
$orderby	value
	0		id
	1		proj_id
	2		time
	3		ip
	4		track_url
	5		domain
$domain	value
	all/NULL	*
	'null'	null
	string	string		
*/
	if($proj_id != NULL){
		$proj_id = inintval($proj_id);
	}
	if($domain != NULL){
		$domain = adaddslashes($domain);;
	}
	$start = inintval($start);
	$end = inintval($end);
	$orderby = inintval($orderby);
	$index = 0;
	$data[][] = NULL;

	switch($orderby){
	case 0:
		$orderby = "id";
		break;
	case 1:
		$orderby = "proj_id";
		break;
	case 2:
		$orderby = "time";
		break;
	case 3:
		$orderby = "ip";
		break;
	case 4:
		$orderby = "track_url";
		break;
	case 5:
		$orderby = "domain";
		break;
	default:
		$orderby = "id";
	}
	if($DESC){
		$orderby = "`".$orderby."`"." DESC";
	}
	if($proj_id == NULL){
		if($domain == NULL || $domain == 'all'){
			$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo ORDER BY $orderby LIMIT $start,$end";
		}else{
			$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `domain` = '$domain' ORDER BY $orderby LIMIT $start,$end";
		}
	}else{
		if($domain == NULL || $domain == 'all'){
			$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `proj_id` = '$proj_id' ORDER BY $orderby LIMIT $start,$end";
		}else{
			$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `proj_id` = '$proj_id' and `domain` = '$domain' ORDER BY $orderby LIMIT $start,$end";
		}
	}
	//echo $query."<br><br>";
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());

	while($row = mysql_fetch_array($result)){
		$data[$index] = array(
			'id' => $row['id'],
			'proj_id' => $row['proj_id'],
			'content' => json_decode($row['content']),
			'track_url' => $row['track_url'],
			'domain' => $row['domain'],
			'ip' => $row['ip'],
			'datetime' => $row['datetime']
		);
		$index ++;
	}
	return $data;
}

function get_trackinfo_num($proj_id = NULL,$domain = NULL){	//Get trackinfo item number
	if($proj_id != NULL){
		$proj_id = inintval($proj_id);
	}
	if($domain != NULL){
		$domain = adaddslashes($domain);;
	}
	if($proj_id == NULL){
		if($domain == NULL || $domain == 'all'){
			$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo";
		}else{
			$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `domain` = '$domain'";
		}
	}else{
		if($domain == NULL || $domain == 'all'){
			$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `proj_id` = '$proj_id'";
		}else{
			$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `proj_id` = '$proj_id' and `domain` = '$domain'";
		}
	}
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	$trackinfo_num = mysql_result($result,0,0);
	return $trackinfo_num;
}

function get_trackinfo_domain($proj_id = NULL){
	$index = 0;
	if($proj_id == NULL){
		$query_domain = "SELECT domain FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo GROUP BY domain";
	}else{
		$proj_id = inintval($proj_id);
		$query_domain = "SELECT domain FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE `proj_id` = '$proj_id' GROUP BY domain";
	}
	$result_domain = mysql_query($query_domain,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	while($domain_row = mysql_fetch_array($result_domain)){
		$domains[$index] = $domain_row[0];
		$index ++;
	}
	//echo $query_domain."<br><br>";
	return $domains;
}

function filter_trackinfo($filter_type = 0,$string,$start,$end,$exactly = TRUE){	//Filter trackinfo
/*
$filter_type	value
	0		proj_id
	1		date
	2		ip
	3		track_url
	4		domain
*/
	$filter_type = inintval($filter_typed);
	$string = adaddslashes($string);
	$start = inintval($start);
	$end = inintval($end);
	$index = 0;
	$data[][] = NULL;

	switch($filter_type){
		case 0:
			$filter_type = "proj_id";
			break;
		case 1:
			$filter_type = "date";
			break;
		case 2:
			$filter_type = "ip";
			break;
		case 3:
			$filter_type = "track_url";
			break;
		case 4:
			$filter_type = "domain";
			break;
		default:
			$filter_type = "proj_id";
	}
	
	if(!$exactly){
		$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE $filter_type LIKE %$string% ORDER BY 'id' DESC LIMIT $start,$end";
		if($filter_type == "date"){
		$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE date('time') = '$string' ORDER BY 'id' DESC LIMIT $start,$end";
		}
	}else{
		$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE $filter_type = '$string' ORDER BY 'id' DESC LIMIT $start,$end";
	}
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());

	while($row = mysql_fetch_array($result)){
		$data[$index] = array (
			"id" => "id",
			"proj_id" => "proj_id",
			"content" => "content",
			"track_url" => "track_url",
			"domain" => "domain",
			"ip" => "ip",
			"datetime" => "datetime"
		);
		$index ++;
	}
	
	return $data;
}

function filter_trackinfo_num($filter_type = 0,$string,$exactly = TRUE){//Filter trackinfo item number
	/*
$filter_type	value
	0		proj_id
	1		date
	2		ip
	3		track_url
	4		domain
*/
	$filter_type = inintval($filter_typed);
	$string = adaddslashes($string);

	switch($filter_type){
		case 0:
			$filter_type = "proj_id";
			break;
		case 1:
			$filter_type = "date";
			break;
		case 2:
			$filter_type = "ip";
			break;
		case 3:
			$filter_type = "track_url";
			break;
		case 4:
			$filter_type = "domain";
			break;
		default:
			$filter_type = "proj_id";
	}
	
	if(!$exactly){
		$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE $filter_type LIKE '%$string%'";
		if($filter_type == "date"){
		$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE date('time') = '$string'";
		}
	}else{
		$query = "SELECT COUNT(*) FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE $filter_type = '$string'";
	}
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	
	$filter_trackinfo_num = mysql_result($result,0,0);
	return $filter_trackinfo_num;
}

function get_projects(){	//get all project id and name
	$query = "SELECT `id`,`name` FROM ".$GLOBALS['imgt_tbPrefix']."project ORDER BY id;";
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	$index = 0;
	while ($row = mysql_fetch_array($result)) {
		$projects[$index]['id'] = $row['id'];
		$projects[$index]['name'] = $row['name'];
		$index ++;
	}
	return $projects;
};

function get_project($id){	//get project detail info
	$query = "SELECT * FROM ".$GLOBALS['imgt_tbPrefix']."project WHERE `id` = '$id'";
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	$project = mysql_fetch_array($result);
	$project['img_url'] = $GLOBALS['imgt_img_urlroot'].'/api.php?authid='.$project['authid'];
	//echo $query;
	return $project;
};

function create_project($name,$img,$desc,$type = 0,$value  = NULL,$filter = 0,$logging = 1){	//Create new project
/*
$type describe
0	normal track
1	track + redirection //security risks
2	track + HTTP Authorization Cheat
$filter describe
1	prevent multi-loging
*/
	$name = adaddslashes(htmlspecialchars($name));
	$type = inintval($type);
	$value = adaddslashes(htmlspecialchars($value));
	$desc = adaddslashes(htmlspecialchars($desc));
	$filter = inintval($filter);
	$logging = inintval($logging);
	$img_type = inintval($img['img_type']);
	$height = inintval($img['height']);
	$width = inintval($img['width']);
	$bg_color = htmlspecialchars($img['bg_color']);
	$authid = md5(str_shuffle($_SERVER['REMOTE_ADDR'].rand().time()));
	$datetime = date('Y-m-d H:i:s');
	if($type != 0 && $type != 1 && $type != 2){
		$type = 0;
	}
	if($filter != 0 && $filter != 1){
		$filter = 0;
	}
	$query = "INSERT INTO ".$GLOBALS['imgt_tbPrefix']."project (`name`,`type`,`value`,`filter`,`img_type`,`height`,`width`,`bg_color`,`description`,`authid`,`datetime`,`logging`) VALUES('$name','$type','$value','$filter','$img_type','$height','$width','$bg_color','$desc','$authid','$datetime','$logging');";
	$result = mysql_query($query,$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	if($result){
		return 1;
	}else{
		return 0;
	}
}

function update_project($id,$img,$desc,$name,$type = 0,$value  = NULL,$filter = 0,$logging = 1){	//update project
/*
$type describe
0	normal track
1	track + redirection //security risks
2	track + HTTP Authorization Cheat

$filter describe
1	prevent multi-loging
*/
	$id = inintval($id);
	$name = adaddslashes(htmlspecialchars($name));
	$type = inintval($type);
	$value = adaddslashes(htmlspecialchars($value));
	$desc = adaddslashes(htmlspecialchars($desc));
	$filter = inintval($filter);
	$logging = inintval($logging);
	$img_type = inintval($img['img_type']);
	$height = inintval($img['height']);
	$width = inintval($img['width']);
	$bg_color = htmlspecialchars($img['bg_color']);
	if($type != 0 && $type != 1 && $type != 2){
		$type = 0;
	}
	if($filter != 0 && $filter != 1){
		$filter = 0;
	}
	$result = mysql_query("UPDATE ".$GLOBALS['imgt_tbPrefix']."project SET `name` = '$name',`type` = '$type',`value` = '$value',`filter` = $filter,`img_type` = '$img_type',`height` = '$height',`width` = '$width',`bg_color` = '$bg_color',`description` = '$desc',`logging` = '$logging' WHERE id = $id;",$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	if($result){
		return 1;
	}else{
		return 0;
	}
}

function delete_project($id){	//Delete project
	$id = inintval($id);
	$result1 = mysql_query("DELETE FROM ".$GLOBALS['imgt_tbPrefix']."project WHERE id = '$id'; ",$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	$result2 = mysql_query("DELETE FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE proj_id = '$id'; ",$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	if($result1&&$result2){
		return 1;
	}else{
		return 0;
	}
}

function insert_trackinfo($proj_id,$content,$track_url,$domain,$ip){	//Insert new trackinfo
	$proj_id = inintval($proj_id);
	$content = adaddslashes($content);
	$track_url = adaddslashes($track_url);
	$domain = adaddslashes($domain);
	$ip = adaddslashes($ip);
	$datetime = date("Y-m-d H:i:s");
	$result = mysql_query("INSERT INTO ".$GLOBALS['imgt_tbPrefix']."trackinfo (`proj_id`,`content`,`track_url`,`domain`,`ip`,`datetime`) VALUES('$proj_id','$content','$track_url','$domain','$ip','$datetime');",$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	if($result){
		return 1;
	}else{
		return 0;
	}
}

function delete_trackinfo($id){	//Delete trackinfo
	$id = inintval($id);
	$result = mysql_query("DELETE FROM ".$GLOBALS['imgt_tbPrefix']."trackinfo WHERE id = $id; ",$GLOBALS['imgt_sql_conn']) or die(mysql_errno().":".mysql_error());
	if($result){
		return 1;
	}else{
		return 0;
	}
}

function adaddslashes($string, $force = 0){	//Add slashes
	if(!get_magic_quotes_gpc() || $force) { 
		if(is_array($string)) { 
			foreach($string as $key => $val) { 
				$string[$key] = adaddslashes($val, $force); 
			} 
		} else { 
			$string = addslashes($string); 
		} 
	} 

	return $string; 
}

function inintval($int){ //Int value
	if(is_array($int)) {
			foreach($int as $key => $val) {
				$int[$key] = intval($val);
			} 
		} else { 
			$int = intval($int); 
		}

	return $int;
}

function bigger_than($num,$than = 0){	//$num >= $than else return $than
	$num = inintval($num);
	$than = inintval($than);
	if($num >= $than){
		return $num;
	}else{
		return $than;
	}
}

function html2rgb($color,$returnstring=false){
    if ($color[0] == '#') 
       $color = substr($color, 1);
    if (strlen($color) == 6)
       list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;
    //$key = 1/255; // use this to get a range from 0 to 1 eg: (0.5, 1, 0.1)
    $key = 1; // use this for normal range 0 to 255 eg: (0, 255, 50)
    $r = hexdec($r)*$key;
    $g = hexdec($g)*$key;
    $b = hexdec($b)*$key;
    if($returnstring){
        return "{rgb $r $g $b}";
    }else{
        return array($r, $g, $b);
    }
}

?>
