	<h1>Register</h1>
	
	<div id="body">
		<p><b><a href='login'>Already Registered?</a></b></p>
		
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?=validation_errors()?>
		<?=form_open('pages/register')?>
			<?=form_fieldset('Register')?>
				<div class="textfield">
					<?=form_label('Username', 'username')?>
					<?=form_input('username', set_value('username'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Password', 'password')?>
					<?=form_password('password')?>
				</div>
				
				<div class="textfield">
					<?=form_label('Confirm Password', 'passconf')?>
					<?=form_password('passconf')?>
				</div>
				
				<div class="textfield">
					<?=form_label('Email', 'email')?>
					<?=form_input('email', set_value('email'))?>
				</div>
				
				<div class="captcha">
					<?=$image?>
				</div>
				
				<div class="captcha">
					<?=form_label('Captcha', 'captcha')?>
					<?=form_input('captcha')?>
				</div>
				
				<div class="buttons">
					<?=form_submit('register', 'Register')?>
				</div>
			<?=form_fieldset_close()?>
		<?=form_close()?>
		<script type="text/javascript">
			document.getElementsByName('username').focus();
		</script>
	</div>