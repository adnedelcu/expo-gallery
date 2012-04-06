	<h1>Login</h1>
	
	<div id="body">
		<p><b><a href='register'>Not registered yet?</a></b> | <b><a href='forgot_pw'>Forgot your password?</a></b> | <b><a href='forgot_user'>Forgot your identity?</a></b></p>
		
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?=validation_errors()?>
		<?=form_open('pages/login')?>
			<?=form_fieldset('Login')?>
				<div class="textfield">
					<?=form_label('Username', 'username')?>
					<?=form_input('username', set_value('username'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Password', 'password')?>
					<?=form_password('password')?>
				</div>
				
				<div class="buttons">
					<?=form_submit('login', 'Login')?>
				</div>
			<?=form_fieldset_close()?>
		<?=form_close()?>
		<script type="text/javascript">
			document.getElementsByName('username').focus();
		</script>
	</div>