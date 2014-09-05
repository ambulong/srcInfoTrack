<?php
include('./init.php');
include('./inc/header.php');
if(chklogin($_SESSION['user'])){
	header("Location: ./main.php");
	exit;
}
?>

<div id="login_panel">
	<div class="cunstom_panel">
	<table>
		<tr>
			<label for="login_panel_username">
			<td>Username:</td>
			<td><input type="text" name="username" id="login_panel_username" class="custom_input_big"></td>
			</label>
		</tr>
		<tr>
			<label for="login_panel_password">
			<td>Password:</td>
			<td><input type="password" name="password" id="login_panel_password" class="custom_input_big"></td>
			</label>
		</tr>
		<tr>
			<td><button id="login_panel_submit" class="btn btn-primary">Login</button></td>
			<td><label id="login_panel_info">Please login first</label></td>
		</tr>
	</table>
	</div>
</div>



<?php
include('./inc/footer.php');
?>
