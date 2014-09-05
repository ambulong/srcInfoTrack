<div style="background: #fff;border: 1px solid #e5e5e5;-webkit-box-shadow: rgba(200,200,200,0.7) 0 4px 10px -1px;box-shadow: rgba(200,200,200,0.7) 0px 2px 5px -1px;padding:4px 8px;position:fixed;z-index: 10000;top:3px;left:3px;cursor:pointer;display:none;"  id="public_info">
</div>
<?php if(chklogin($_SESSION['user'])){ ?>
<div id="auth_token_div" class="display:none;">
<a href="<?php echo $_SESSION['token']; ?>" id="auth_token_a"></a>
<a href="1" id="current_page"></a>
<a href="home" id="current_operate"></a>
<a href="0" id="current_proj_id"></a>
</div>
<?php } ?>
</body>
</html>
