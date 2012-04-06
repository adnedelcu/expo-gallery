	<h1>Resend Confirmation Mail</h1>
	
	<div id="body">
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?php if(isset($message)): ?>
		<span style='color:green'><?=$message?></span>
		<?php else: ?>
		<?=validation_errors()?>
		<?=form_open('pages/resend')?>
			<?=form_fieldset('Resend Confirmation Mail')?>
				<div class="textfield">
					<?=form_label('Username', 'username')?>
					<?=form_input('username', set_value('username'))?>
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
					<?=form_submit('resend', 'Resend')?>
				</div>
			<?=form_fieldset_close()?>
		<?=form_close()?>
		<script type="text/javascript">
			document.getElementsByName('username').focus();
		</script>
		<?php endif;?>
	</div>