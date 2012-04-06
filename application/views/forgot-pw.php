	<h1>Recover Password</h1>
	
	<div id="body">
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?php if(isset($message)): ?>
		<span style='color:green'><?=$message?></span>
		<?php else: ?>
		<?=validation_errors()?>
		<?=form_open('pages/forgot_pw')?>
			<?=form_fieldset('Recover Password')?>
				<div class="textfield">
					<?=form_label('Username', 'username')?>
					<?=form_input('username', set_value('username'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Email', 'email')?>
					<?=form_input('email', set_value('email'))?>
				</div>
				
				<div class="buttons">
					<?=form_submit('recover', 'Recover')?>
				</div>
			<?=form_fieldset_close()?>
		<?=form_close()?>
		<script type="text/javascript">
			document.getElementsByName('username').focus();
		</script>
		<?php endif;?>
	</div>