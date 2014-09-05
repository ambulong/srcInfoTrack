<?php
include('init.php');

//public
$token = $_REQUEST['token'];
$do = $_REQUEST['do'];
$track_info_id = $_REQUEST['track_info_id'];
$domain = $_REQUEST['domain'];

//for get_trackinfo() , filter_trackinfo
$page = bigger_than($_REQUEST['page'],1);
$perpage_num = bigger_than($_REQUEST['perpage_num'],1);
$orderby = bigger_than($_REQUEST['orderby'],0);
$DESC = bigger_than($_REQUEST['desc'],0);
if(isset($_REQUEST['proj_id'])){
	$proj_id = bigger_than($_REQUEST['proj_id'],0);
}else{
	$proj_id = NULL;
}
$filter_type = bigger_than($_REQUEST['filter_type'],1);
$exactly = bigger_than($_REQUEST['exactly'],1);
$filter_string = $_REQUEST['filter_string'];

$start = ( $page - 1 ) * $perpage_num;
$end = $perpage_num;

//for create/update project
$proj_name = $_REQUEST['proj_name'];
$proj_type = $_REQUEST['proj_type'];
$proj_value = $_REQUEST['proj_value'];
$proj_filter = $_REQUEST['proj_filter'];
$proj_desc = $_REQUEST['proj_desc'];
$img_type = $_REQUEST['img_type'];
$img_height = $_REQUEST['img_height'];
$img_width = $_REQUEST['img_width'];
$img_bg_color = $_REQUEST['img_bg_color'];
$proj_logging = $_REQUEST['proj_state'];
$img = array(
			'img_type' => $img_type,
			'height' => $img_height,
			'width' => $img_width,
			'bg_color' => $img_bg_color
		);
		
switch($do){
	case 'confirm_user':
		if(!chklogin($_SESSION['user'])){
			if(confirm_user($_REQUEST['username'],$_REQUEST['password'])){
				$_SESSION['user'] = $_REQUEST['username'];
				$_SESSION['token'] = settoken();
				json_data("1","none");
			}else{
				json_data("0","You have not logined");
			}
		}else{
			json_data("You are already logged","none");
		}
		break;
	case 'logout':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			unset($_SESSION['user']);
			unset($_SESSION['token']);
			$_SESSION['user'] = "Guest";
			json_data("1","none");
		}else{
			json_data("0","none");
		}
		break;
	case 'get_trackinfo':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			$trackinfo_num = get_trackinfo_num($proj_id);
			$domains = get_trackinfo_domain($proj_id);
			$data = get_trackinfo($start,$end,$orderby,$DESC,$proj_id,$domain);
			$data = array(
				'total' => $trackinfo_num,
				'items' => $data,
				'domains' => $domains
			);
			json_data("1",$data);
		}else{
			json_data("0","none");
		}
		break;
	case 'filter_trackinfo':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			
		}else{
			json_data("0","none");
		}
		break;
	case 'get_projects':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			$projects = get_projects();
			json_data("1",$projects);
		}else{
			json_data("0","none");
		}
		break;
	case 'get_project':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			$project = get_project($proj_id);
			json_data("1",$project);
		}else{
			json_data("0","none");
		}
		break;
	case 'create_project':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			if(create_project($proj_name,$img,$proj_desc,$proj_type,$proj_value,$proj_filter,$proj_logging) == 1){
				json_data("1","none");
			}else{
				json_data("0","Fail to create project:<br>name:$proj_name<br>proj_desc:$proj_desc<br>proj_type:$proj_type<br>proj_value:$proj_value<br>proj_filter:$proj_filter<br>");
			}
		}else{
			json_data("0","none");
		}
		break;
	case 'update_project':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			update_project($proj_id,$img,$proj_desc,$proj_name,$proj_type,$proj_value,$proj_filter,$proj_logging);
			json_data("1","none");
		}else{
			json_data("0","none");
		}
		break;
	case 'delete_project':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			delete_project($proj_id);
			json_data("1","none");
		}else{
			json_data("0","none");
		}
		break;
	case 'delete_trackinfo':
		if(chktoken($token,$_SESSION['token']) && chklogin($_SESSION['user'])){
			delete_trackinfo($track_info_id);
			json_data("1","none");
		}else{
			json_data("0","none");
		}
		break;
	default:
		header("Location: ./login.php");
}
exit();
?>
