<?php
include('../init.php');
header('Content-type: text/javascript');
?>
$(function(){
<?php
if(chklogin($_SESSION['user'])){
?>
$.make_pagination = function(total,perpage_num,currentpage){
	total = parseInt(total);
	perpage_num = parseInt(perpage_num);
	currentpage = parseInt(currentpage);
	var index = 1;
	var prev_page = currentpage -1;
	var next_page = currentpage +1;
	var total_page = Math.ceil(total/perpage_num);
	var htmlcode = '<li class="disabled"><a href="#total">Total:'+total+' / '+total_page+'</a></li>';
	if(currentpage == 1){
		htmlcode = htmlcode + '<li class="disabled"><a href="#prev" title="1">&laquo;</a></li>';
	}else{
		htmlcode = htmlcode + '<li><a href="#prev" title="'+prev_page+'">&laquo;</a></li>';
	}
	for(index;index <= total_page;index ++){
		if(index == currentpage){
			htmlcode = htmlcode + '<li class="active"><a href="#page'+index+'" title="'+index+'">'+index+'</a></li>';
		}else{
			htmlcode = htmlcode + '<li><a href="#page'+index+'" title="'+index+'">'+index+'</a></li>';
		}
	}
	if(currentpage == total_page){
		htmlcode = htmlcode + '<li class="disabled"><a href="#next" title="'+currentpage+'">&raquo;</a></li>';
	}else{
		htmlcode = htmlcode + '<li><a href="#next" title="'+next_page+'">&raquo;</a></li>';
	}
	$('#pagination_top_div ul,#pagination_foot_div ul').html(htmlcode);
	$('#pagination_top_div li a,#pagination_foot_div li a').click(function(){
		$('#current_page').attr('href',$(this).attr('title'));
		$('#toolsbar_refresh').click();
	});
};

$.menu_active = function(obj){
	$('#main_panel_menu_ul .active').removeClass('active');
	$(obj).addClass('active');
};
$.load_project = function(proj_id){
	//var page=1,per_page_num=10;
	//alert(1);
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'get_project',
			token: $("#auth_token_a").attr('href'),
			proj_id: proj_id
		},
		dataType:'json',
		success:function(result){
			if(result.state == 1){
				var project = result.data;
				$('#current_operate').attr('href','project');
				$('#current_proj_id').attr('href',project.id);
				$('#edit_proj_name').val(project.name);
				$('option[name="edit_proj_logging"]').prop('selected',false);
				$('#edit_proj_logging_'+project.logging).prop('selected',true);
				$('option[name="edit_proj_filter"]').prop('selected',false);
				$('#edit_proj_filter_'+project.filter).prop('selected',true);
				$('option[name="project_info_edit_type"]').prop('selected',false);
				$('#project_info_edit_type_'+project.type).prop('selected',true);
				$('#project_info_edit_value').val(project.value);
				$('option[name="project_info_edit_img_type"]').prop('selected',false);
				$('#project_info_edit_img_type_'+project.img_type).prop('selected',true);
				$('#project_info_edit_img_width').val(project.width);
				$('#project_info_edit_img_height').val(project.height);
				$('#project_info_edit_img_bg_color').val(project.bg_color);
				$('#project_info_edit_description').val(project.description);
				
				if(project.logging == 1){
					project.logging == 'Logging';
				}else{
					project.logging == 'Stop';
				}
				if(project.filter == 1){
					project.filter = 'Yes';
				}else{
					project.filter = 'No';
				}
				if(project.type == 1){
					project.type = 'Normal track + Redirection';
				}
				if(project.type == 2){
					project.type = 'Normal track + HTTP Authorization';
				}
				if(project.type == 0){
					project.type = 'Normal track';
				}
				if(project.img_type == 1){
					project.img_type = 'JPEG';
				}
				if(project.img_type == 2){
					project.img_type = 'GIF';
				}
				if(project.img_type == 3){
					project.img_type = 'BMP';
				}
				if(project.img_type == 0){
					project.img_type = 'PNG';
				}
				$('#view_proj_info_img img').attr('src',project.img_url);
				$('#view_proj_info_img_url').html(project.img_url);
				$('#view_proj_info_name').html(project.name);
				$('#view_proj_info_state').html(project.logging);
				$('#view_proj_info_filter').html(project.filter);
				$('#view_proj_info_type').html(project.type);
				$('#view_proj_info_value').html(project.value);
				$('#view_proj_info_img_detail').html(project.img_type +'&nbsp;'+project.width +'x'+ project.height +'&nbsp;'+project.bg_color);
				$('#view_proj_info_desc').html(project.description);
				
				public_info('Load project info success');
			}else{
				public_info('Fail to load project info');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
};
$.load_all_track_info = function(page,per_page_num){
	//var page=1,per_page_num=10;
	//alert(1);
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'get_trackinfo',
			token: $("#auth_token_a").attr('href'),
			page: page,
			perpage_num: per_page_num,
			desc: 1,
			domain: $('#track_info_domains option:selected').val()
		},
		dataType:'json',
		success:function(result){
			if(result.state == 1){
				var trackinfos = result.data;
				var start_index = (page-1) * per_page_num +1;
				var htmlcode = '';
				var domains_options = '<option value ="all">All domains</option>';
				$('.track_info_summary,.track_info_div_detail').remove();
				if(trackinfos.total != 0){
					$.each(trackinfos.items, function( index , trackinfo ) {
						htmlcode = htmlcode +
						'<tr class="track_info_summary">'
						+'<td>'+start_index+'</td>'
						+'<td><input type="checkbox" name="select_delete" title="'+trackinfo.id+'"></td>'
						+'<td>'+trackinfo.datetime+'</td>'
						+'<td><div class="td_url">'+trackinfo.track_url+'</div></td>'
						+'<td>'+trackinfo.ip+'</td>'
						+'<td><div class="btn-group"><button type="button" class="btn btn-mini track_info_div_detail_btn" value="'+trackinfo.id+'">Detail</button><button type="button" class="btn dropdown-toggle btn-mini" data-toggle="dropdown"><span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#delete_track_info_'+trackinfo.id+'" title="'+trackinfo.id+'" class="track_info_div_delete_btn">Delete</a></li></ul></div></td>'
						+'</tr>'
						+'<tr class="track_info_div_detail track_info_div_detail_id_'+trackinfo.id+'" style="display:none">'
						+'<td colspan="6">'
							+'<table class="table table-bordered">'
								+'<tr>'
								+'<td colspan="2"><b>HTTP_REFERER</b></td>'
								+'<td colspan="2">'+trackinfo.track_url+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td colspan="2"><b>HTTP_USER_AGENT</b></td>'
								+'<td colspan="2">'+trackinfo.content.HTTP_USER_AGENT+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td><b>IP</b></td><td>'+trackinfo.ip+'</td>'
								+'<td><b>HTTP_ACCEPT_LANGUAGE</b></td>'
								+'<td>'+trackinfo.content.HTTP_ACCEPT_LANGUAGE+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td><b>REMOTE_HOST</b></td>'
								+'<td>'+trackinfo.content.REMOTE_HOST+'</td>'
								+'<td><b>REMOTE_PORT</b></td>'
								+'<td>'+trackinfo.content.REMOTE_PORT+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td><b>Username</b></td>'
								+'<td>'+trackinfo.content.Username+'</td>'
								+'<td><b>Password</b></td>'
								+'<td>'+trackinfo.content.Password+'</td>'
								+'</tr>'
							+'</table>'
						+'</td>'
						+'</tr>'
						;
					start_index ++;
					});
					$.each(trackinfos.domains, function( domain_index , domain ) {
						if(domain == ''){
							domains_options = domains_options + '<option value ="nulll">NULL</option>';
						}else{
							domains_options = domains_options + '<option value ="'+domain+'">'+domain+'</option>';
						}
					});
				}else{
					htmlcode = '<tr class="track_info_summary"><td colspan="6">Has not any track info</td></tr>';
				}
				$('#track_info_domains').html(domains_options);
				$('#track_info_table').append(htmlcode);
				$.make_pagination(trackinfos.total,23,$('#current_page').attr('href'));
				$('.track_info_div_detail_btn').click(function(){
				if($('.track_info_div_detail_id_'+$(this).val()).css('display') == 'none'){
					$('.track_info_div_detail_id_'+$(this).val()).stop(1,1).slideDown(200);
					$('.track_info_div_detail_id_'+$(this).val()).prev().css('background-color','#f5f5f5');
				}else{
					$('.track_info_div_detail_id_'+$(this).val()).stop(1,1).slideUp(200);
					$('.track_info_div_detail_id_'+$(this).val()).prev().css('background-color','#fbfbfb');
				}
				});
				$('.td_url').hover(function(){
					$(this).css("word-break","break-all");
					$(this).css("overflow","visible");
				},function(){
					$(this).css("word-break","normal");
					$(this).css("overflow","hidden");
				});
				$('#track_info_div input[name="select_delete"]').click(function(){
					if($('#track_info_div input[name="select_delete"]').length == $('#track_info_div input[name="select_delete"]:checked').length){
						$('.select_all_trackinfo').prop('checked',true);
					}else{
						$('.select_all_trackinfo').prop('checked',false);
					}
				});
				$('.track_info_div_delete_btn').click(function(){
					var track_info_id = $(this).attr('title');
					$.ajax({
						type:'GET',
						url: "./index.php",
						data:{
							do: 'delete_trackinfo',
							token: $("#auth_token_a").attr('href'),
							track_info_id: track_info_id
						},
						dataType:'json',
						success:function(data){
							if(data.state == 1){
								public_info('Delete project '+track_info_id+' success');
							}else{
								public_info('Fail to delete project '+track_info_id);
							}
						},
						error:function(){
							public_info('Please try it again');
						}
				});
					$('#toolsbar_refresh').click();
				});
				public_info('Load track info success');
			}else{
				public_info('Fail to load trackinfp');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
};
$.load_track_info = function(page,per_page_num,proj_id){
	//var page=1,per_page_num=10;
	//alert(1);
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'get_trackinfo',
			token: $("#auth_token_a").attr('href'),
			page: page,
			proj_id: proj_id,
			perpage_num: per_page_num,
			desc: 1,
			domain: $('#track_info_domains option:selected').val()
		},
		dataType:'json',
		success:function(result){
			if(result.state == 1){
				var trackinfos = result.data;
				var start_index = (page-1) * per_page_num +1;
				var htmlcode = '';
				var domains_options = '<option value ="all">All domains</option>';
				$('.track_info_summary,.track_info_div_detail').remove();
				if(trackinfos.total != 0){
					$.each(trackinfos.items, function( index , trackinfo ) {
						htmlcode = htmlcode +
						'<tr class="track_info_summary">'
						+'<td>'+start_index+'</td>'
						+'<td><input type="checkbox" name="select_delete" title="'+trackinfo.id+'"></td>'
						+'<td>'+trackinfo.datetime+'</td>'
						+'<td><div class="td_url">'+trackinfo.track_url+'</div></td>'
						+'<td>'+trackinfo.ip+'</td>'
						+'<td><div class="btn-group"><button type="button" class="btn btn-mini track_info_div_detail_btn" value="'+trackinfo.id+'">Detail</button><button type="button" class="btn dropdown-toggle btn-mini" data-toggle="dropdown"><span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#delete_track_info_"'+trackinfo.id+' title="'+trackinfo.id+'" class="track_info_div_delete_btn">Delete</a></li></ul></div></td>'
						+'</tr>'
						+'<tr class="track_info_div_detail track_info_div_detail_id_'+trackinfo.id+'" style="display:none">'
						+'<td colspan="6">'
							+'<table class="table table-bordered">'
								+'<tr>'
								+'<td colspan="2"><b>HTTP_REFERER</b></td>'
								+'<td colspan="2">'+trackinfo.track_url+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td colspan="2"><b>HTTP_USER_AGENT</b></td>'
								+'<td colspan="2">'+trackinfo.content.HTTP_USER_AGENT+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td><b>IP</b></td><td>'+trackinfo.ip+'</td>'
								+'<td><b>HTTP_ACCEPT_LANGUAGE</b></td>'
								+'<td>'+trackinfo.content.HTTP_ACCEPT_LANGUAGE+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td><b>REMOTE_HOST</b></td>'
								+'<td>'+trackinfo.content.REMOTE_HOST+'</td>'
								+'<td><b>REMOTE_PORT</b></td>'
								+'<td>'+trackinfo.content.REMOTE_PORT+'</td>'
								+'</tr>'
								+'<tr>'
								+'<td><b>Username</b></td>'
								+'<td>'+trackinfo.content.Username+'</td>'
								+'<td><b>Password</b></td>'
								+'<td>'+trackinfo.content.Password+'</td>'
								+'</tr>'
							+'</table>'
						+'</td>'
						+'</tr>'
						;
					start_index ++;
					});
					$.each(trackinfos.domains, function( domain_index , domain ) {
						if(domain == $('#track_info_domains option:selected').val()){
							domains_options = domains_options + '<option selected="selected" value ="'+domain+'">'+domain+'</option>';
						}else{
							domains_options = domains_options + '<option value ="'+domain+'">'+domain+'</option>';
						}
					});
				}else{
					htmlcode = '<tr class="track_info_summary"><td colspan="6">This Project has not any track info</td></tr>';
				}
				$('#track_info_domains').html(domains_options);
				$('#track_info_table').append(htmlcode);
				$.make_pagination(trackinfos.total,23,$('#current_page').attr('href'));
				$('.track_info_div_detail_btn').click(function(){
				if($('.track_info_div_detail_id_'+$(this).val()).css('display') == 'none'){
					$('.track_info_div_detail_id_'+$(this).val()).stop(1,1).slideDown(200);
					$('.track_info_div_detail_id_'+$(this).val()).prev().css('background-color','#f5f5f5');
				}else{
					$('.track_info_div_detail_id_'+$(this).val()).stop(1,1).slideUp(200);
					$('.track_info_div_detail_id_'+$(this).val()).prev().css('background-color','#fbfbfb');
				}
				});
				$('.td_url').hover(function(){
					$(this).css("word-break","break-all");
					$(this).css("overflow","visible");
				},function(){
					$(this).css("word-break","normal");
					$(this).css("overflow","hidden");
				});
				$('#track_info_div input[name="select_delete"]').click(function(){
					if($('#track_info_div input[name="select_delete"]').length == $('#track_info_div input[name="select_delete"]:checked').length){
						$('.select_all_trackinfo').prop('checked',true);
					}else{
						$('.select_all_trackinfo').prop('checked',false);
					}
				});
				$('.track_info_div_delete_btn').click(function(){
					var track_info_id = $(this).attr('title');
					$.ajax({
						type:'GET',
						url: "./index.php",
						data:{
							do: 'delete_trackinfo',
							token: $("#auth_token_a").attr('href'),
							track_info_id: track_info_id
						},
						dataType:'json',
						success:function(data){
							if(data.state == 1){
								public_info('Delete project '+track_info_id+' success');
							}else{
								public_info('Fail to delete project '+track_info_id);
							}
						},
						error:function(){
							public_info('Please try it again');
						}
					});
					$('#toolsbar_refresh').click();
				});
				public_info('Load track info success');
			}else{
				public_info('Fail to load trackinfp');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
};

$('#toolsbar_refresh').click(function(){
	var current_page = $('#current_page').attr('href');
	var current_proj_id = $('#current_proj_id').attr('href');
	if($('#current_operate').attr('href') == 'home'){
		$.load_all_track_info(current_page,23);
	}else{
		$.load_track_info(current_page,23,current_proj_id);
	}
});
$('#home_page').click(function(){
	$.load_all_track_info(1,23);
	$.menu_active(this);
	$('#current_operate').attr('href','home');
	$('#hide_all').click();
	$('#edit_proj,#delete_proj,#show_proj_info,#hide_all').prop('disabled',true);
	$('#current_page').attr('href',1);
});

$('#refresh_projects_list').click(function(){
	load_projs();
});

$('#project_info_edit_save').click(function(){
	public_info('<img src="./images/login_loader.gif" style="border:none;">&nbsp;Saving...');
	$("#new_proj_edit_save,#new_proj_edit_cancel,#new_proj_edit_hide").attr('disabled',true);
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'update_project',
			token: $("#auth_token_a").attr('href'),
			proj_id: $("#current_proj_id").attr('href'),
			proj_name: $('input[name="edit_proj_name"]').val(),
			proj_state: $('option[name="edit_proj_logging"]:selected').val(),
			proj_type: $('option[name="project_info_edit_type"]:selected').val(),
			proj_value: $('input[name="project_info_edit_value"]').val(),
			proj_filter: $('option[name="edit_proj_filter"]:selected').val(),
			proj_desc: $('textarea[name="project_info_edit_description"]').val(),
			img_type: $('option[name="project_info_edit_img_type"]:selected').val(),
			img_height: $('input[name="project_info_edit_img_height"]').val(),
			img_width: $('input[name="project_info_edit_img_width"]').val(),
			img_bg_color: $('input[name="project_info_edit_img_bg_color"]').val()	
		},
		dataType:'json',
		success:function(data){
			if(data.state == 1){
				public_info('Save success');
				load_projs();
				$('#show_proj_info').click();
			}else{
				public_info('Fail to update this project');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
});

$('#new_proj_edit_save').click(function(){
	public_info('<img src="./images/login_loader.gif" style="border:none;">&nbsp;Saving...');
	$("#new_proj_edit_save,#new_proj_edit_cancel,#new_proj_edit_hide").attr('disabled',true);
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'create_project',
			token: $("#auth_token_a").attr('href'),
			proj_name: $('input[name="new_proj_name"]').val(),
			proj_state: $('option[name="edit_proj_logging"]:selected').val(),
			proj_type: $('option[name="new_proj_type"]:selected').val(),
			proj_value: $('input[name="new_proj_value"]').val(),
			proj_filter: $('option[name="new_proj_filter"]:selected').val(),
			proj_desc: $('textarea[name="new_proj_description"]').val(),
			img_type: $('option[name="new_proj_img_type"]:selected').val(),
			img_height: $('input[name="new_proj_img_height"]').val(),
			img_width: $('input[name="new_proj_img_width"]').val(),
			img_bg_color: $('input[name="new_proj_img_bg_color"]').val()	
		},
		dataType:'json',
		success:function(data){
			if(data.state == 1){
				public_info('Save success');
				$('#new_proj_edit_cancel').click();
				load_projs();
				$('#new_proj_edit_cancel').click();
			}else{
				public_info('Fail to create this project');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
});

$('.select_all_trackinfo').click(function(){
	$('#track_info_div input[name="select_delete"]').prop('checked',this.checked);
});
$('#track_info_div input[name="select_delete"]').click(function(){
	if($('#track_info_div input[name="select_delete"]').length == $('#track_info_div input[name="select_delete"]:checked').length){
		$('.select_all_trackinfo').prop('checked',true);
	}else{
		$('.select_all_trackinfo').prop('checked',false);
	}
});// has a copy to load_all_trackinfo();

$('#unfold_and_fold_all_trackinfo').click(function(){
	if($('#unfold_and_fold_all_trackinfo > i').attr('class') == 'icon-plus'){
		$('#unfold_and_fold_all_trackinfo > i').attr('class','icon-minus');
		$('#unfold_and_fold_all_trackinfo > i').attr('title','Fold all trackinfo');
		$('.track_info_div_detail').show();
		$('.track_info_div_detail').prev().css('background-color','#f5f5f5');
	}else{
		$('#unfold_and_fold_all_trackinfo > i').attr('class','icon-plus');
		$('#unfold_and_fold_all_trackinfo > i').attr('title','Unfold all trackinfo');
		$('.track_info_div_detail').hide();
		$('.track_info_div_detail').prev().css('background-color','#fbfbfb');
	}
});

$('.track_info_div_detail_btn').click(function(){
	if($('.track_info_div_detail_id_'+$(this).val()).css('display') == 'none'){
		$('.track_info_div_detail_id_'+$(this).val()).stop(1,1).slideDown(200);
		$('.track_info_div_detail_id_'+$(this).val()).prev().css('background-color','#f5f5f5');
	}else{
		$('.track_info_div_detail_id_'+$(this).val()).stop(1,1).slideUp(200);
		$('.track_info_div_detail_id_'+$(this).val()).prev().css('background-color','#fbfbfb');
	}
});// has a copy to load_all_trackinfo();

$('#hide_all').click(function(){
	$('#new_proj_edit_cancel,#new_proj_edit_hide').click();
	$('#project_info_edit_cancel,#project_info_edit_hide').click();
	$('#project_img,#project_info_hide').click();
	$('#public_info').click();
});

$('#new_proj_edit_cancel,#new_proj_edit_hide').click(function(){
	$('#new_project').stop(1,1).slideUp(100);
	$('#public_info').click();
});

$('li#create_new_project').click(function(){
	$('#hide_all').click();
	$('#new_project').stop(1,1).slideDown(100);
	public_info('Create new project');
});

$('#project_info_edit_cancel,#project_info_edit_hide').click(function(){
	$('#project_info_edit').stop(1,1).slideUp(100);
	$('#edit_proj').attr("disabled",false);
	$('#public_info').click();
});

$('#edit_proj').click(function(){
	var proj_id = $('#current_proj_id').attr('href');
	$('#new_proj_edit_cancel,#new_proj_edit_hide').click();
	$('#project_img,#project_info_hide').click()
	$('#project_info_edit').stop(1,1).slideDown(100);
	$('#edit_proj').attr("disabled",true);
	$.load_project(proj_id);
	public_info('Edit current project');
});

$('#project_img,#project_info_hide').click(function(){
	$('#project_info_div').stop(1,1).slideUp(100);
	$('#show_proj_info').attr("disabled",false);
	$('#public_info').click();
});

$('#show_proj_info').click(function(){
	var proj_id = $('#current_proj_id').attr('href');
	$('#project_info_edit_cancel,#project_info_edit_hide').click();
	$('#new_proj_edit_cancel,#new_proj_edit_hide').click();
	$('#project_info_div').stop(1,1).slideDown(100);
	$('#show_proj_info').attr("disabled",true);
	$.load_project(proj_id);
	public_info('Current project info');
});

$('.td_url').hover(function(){
	$(this).css("word-break","break-all");
	$(this).css("overflow","visible");
},function(){
	$(this).css("word-break","normal");
	$(this).css("overflow","hidden");
});// has a copy to load_all_trackinfo();

$('#logout_btn').click(function(){ //logout_btn click
	public_info('<img src="./images/login_loader.gif" style="border:none;">&nbsp;Loading...');
	$.ajax({
		type:'GET',
		url: "./index.php?do=logout&token="+$("#auth_token_a").attr('href'),
		dataType:'json',
		success:function(data){
			if(data.state == 1){
				public_info('Logout success');
				$('body').fadeOut(500);
				self.location='login.php';
			}else{
				public_info('Logout failed');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
});
<?php
}
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
?>
$('#login_panel_submit').bind("click",function(){ //login_btn click
	public_info('<img src="./images/login_loader.gif" style="border:none;">&nbsp;Loading...');
	$("#login_panel_submit,#login_panel_username,#login_panel_password").attr('disabled',true);
	$.ajax({
		type:'GET',
		url: "./index.php?do=confirm_user&username="+$("#login_panel_username").val()+"&password="+$("#login_panel_password").val(),
		dataType:'json',
		success:function(data){
			if(data.state == 1){
				public_info('Login success');
				$('#login_panel_info').html('<a href="./main.php">Go to Control Panel >></a>');
				$('body').fadeOut(500);
				self.location='main.php';
			}else{
				public_info('Username or Password incorrect');
			}
		},
		error:function(){
			public_info('Please try it again');
		},
		complete:function(){
			$("#login_panel_submit,#login_panel_username,#login_panel_password").attr('disabled',false);
		}
	});
	
	
});

$('#public_info').click(function(){
	$('#public_info').stop(1,1).fadeOut(1000);
});
<?php
if(chklogin($_SESSION['user'])){
?>
$('#delete_selected_trackinfo').click(function(){
	$.each($('input[name="select_delete"]'),function(index,item){
		//alert(index);
		if($(item).prop('checked') == true){
			//alert(index);
			var track_info_id = $(item).attr('title');
			$.ajax({
				type:'GET',
				url: "./index.php",
				data:{
					do: 'delete_trackinfo',
					token: $("#auth_token_a").attr('href'),
					track_info_id: track_info_id
				},
				dataType:'json',
				success:function(data){
					if(data.state == 1){
						public_info('Delete project '+track_info_id+' success');
					}else{
						public_info('Fail to delete project '+track_info_id);
					}
				},
				error:function(){
					public_info('Please try it again');
				}
			});
		}
	});
	$('#toolsbar_refresh').click();
});

$('.track_info_div_delete_btn').click(function(){
	var track_info_id = $(this).attr('title');
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'delete_trackinfo',
			token: $("#auth_token_a").attr('href'),
			track_info_id: track_info_id
		},
		dataType:'json',
		success:function(data){
			if(data.state == 1){
				public_info('Delete project '+track_info_id+' success');
			}else{
				public_info('Fail to delete project '+track_info_id);
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
	$('#toolsbar_refresh').click();
});//has 2 copy load_all_trackinfo(),load_trackinfo()

$('#delete_proj').click(function(){
	if(confirm('Are you sure to delete this project?') == true){
		var proj_id = $('#current_proj_id').attr('href');
		$.ajax({
			type:'GET',
			url: "./index.php",
			data:{
				do: 'delete_project',
				token: $("#auth_token_a").attr('href'),
				proj_id: proj_id
			},
			dataType:'json',
			success:function(data){
				if(data.state == 1){
					public_info('Delete project '+proj_id+' success');
				}else{
					public_info('Fail to delete project '+proj_id);
				}
			},
			error:function(){
				public_info('Please try it again');
			}
		});
		load_projs();
		$('#home_page').click();
	}
});

$('#pagination_top_div li a,#pagination_foot_div li a').click(function(){
	$('#current_page').attr('href',$(this).attr('title'));
	$('#toolsbar_refresh').click();
});

$('#track_info_domains').change(function(){
	$('#toolsbar_refresh').click();
});

load_projs();
$('#toolsbar_refresh').click();
$('#edit_proj,#delete_proj,#show_proj_info,#hide_all').prop('disabled',true);

<?php
}
?>
});



/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/

function public_info(htmlcode){
	$('#public_info').stop(1,1).fadeOut(100);
	$('#public_info').html(htmlcode);
	$('#public_info').fadeIn(1000);
}
<?php
if(chklogin($_SESSION['user'])){
?>
function load_projs(){
	public_info('<img src="./images/login_loader.gif" style="border:none;">&nbsp;Loading...');
	$.ajax({
		type:'GET',
		url: "./index.php",
		data:{
			do: 'get_projects',
			token: $("#auth_token_a").attr('href')
		},
		dataType:'json',
		success:function(result){
			if(result.state == 1){
				var projects = result.data;
				$('.project_list_item').remove();
				$.each(projects, function( index , project ) {
					$('#projects_list_divider').before('<li class="project_list_item" title="'+project.id+'"><a href="#project_id_'+project.id+'">&nbsp;<i class="icon-chevron-right icon-white"></i>&nbsp;'+project.name+'</a></li>');
				});
				public_info('Load projects list success');
				$('.project_list_item').click(function(){
					var proj_id = $(this).attr('title');
					$.menu_active(this);
					$.load_track_info(1,23,proj_id);
					$.load_project(proj_id);
					$('#edit_proj,#delete_proj,#show_proj_info,#hide_all').prop('disabled',false);
					$('#current_page').attr('href',1);
					//alert(1);
				});
			}else{
				public_info('Fail to load projects list');
			}
		},
		error:function(){
			public_info('Please try it again');
		}
	});
}
<?php
}
?>
