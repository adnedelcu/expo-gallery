	<h1>Change Password</h1>
	
	<div id="body">
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?php if(isset($message)): ?>
		<span style='color:green'><?=$message?></span>
		<?php else: ?>
		<?=validation_errors()?>
		<?=form_open('account/change_password')?>
			<?=form_fieldset('Change Password')?>
				<div class="textfield">
					<?=form_label('Old password', 'old_password')?>
					<?=form_password('old_password')?>
				</div>
				
				<div class="textfield">
					<?=form_label('New password', 'new_password')?>
					<?=form_password('new_password')?>
				</div>
				
				<div class="textfield">
					<?=form_label('Confirm password', 'confirm_password')?>
					<?=form_password('confirm_password')?>
				</div>
				
				<div class="buttons">
					<?=form_submit('modify', 'Modify')?>
				</div>
			<?=form_fieldset_close()?>
		<?=form_close()?>
		<?php endif;?>
		<hr />
		<a href="profile">Back to profile</a>
		<script type="text/javascript">
			document.getElementsByName('old_password').focus();
		</script>
	</div>