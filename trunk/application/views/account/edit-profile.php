	<h1>Edit Profile</h1>
	
	<div id="body">
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?php if(isset($message)): ?>
		<span style='color:green'><?=$message?></span>
		<?php else: ?>
		<?=validation_errors()?>
		<?=form_open('account/edit_profile')?>
			<?=form_fieldset('Edit Profile')?>
				<div class="textfield">
					<?=form_label('First name', 'fname')?>
					<?=form_input('fname', set_value('fname'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Last Name', 'lname')?>
					<?=form_input('lname', set_value('lname'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Nickname', 'nickname')?>
					<?=form_input('nickname', set_value('nickname'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Email', 'email')?>
					<?=form_input('email', set_value('email'))?>
				</div>
				
				<div class="textfield">
					<?=form_label('Birthdate','birthdate')?>
					<select name='day'>
					<?php foreach($days as $id => $i): ?>
						<option value='<?=$i?>'><?=$i?></option>
					<?php endforeach;?>
					</select>
					<select name='month'>
					<?php foreach($months as $id => $i): ?>
						<option value='<?=$i?>'><?=$i?></option>
					<?php endforeach;?>
					</select>
					<select name='year'>
					<?php foreach($years as $id => $i): ?>
						<option value='<?=$i?>'><?=$i?></option>
					<?php endforeach;?>
					</select>
				</div>
				
				<div class="textfield">
					<?=form_label('Country', 'country')?>
					<select name='country'>
					<?php foreach($countries as $id => $i): ?>
						<option value='<?=$i?>'><?=$i?></option>
					<?php endforeach;?>
					</select>
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
			document.getElementsByName('fname').focus();
		</script>
	</div>