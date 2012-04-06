	<h1>Confirm Account</h1>
	
	<div id="body">
		<?php if(isset($error)): ?>
		<span style='color:red'><?=$error?></span>
		<?php endif;?>
		<?php if(isset($message)): ?>
		<span style='color:green'><?=$message?></span>
		<?php else: ?>
		<?=validation_errors()?>
		<?=form_open('pages/confirm')?>
			<?=form_fieldset('Confirm Account')?>
				<div class="textfield">
					<?=form_label('Confirm Code', 'confirmCode')?>
					<?=form_input('confirmCode')?>
				</div>
				
				<div class="buttons">
					<?=form_submit('confirm', 'Confirm')?>
				</div>
			<?=form_fieldset_close()?>
		<?=form_close()?>
		<script type="text/javascript">
			document.getElementsByName('confirmCode').focus();
		</script>
		<?php endif;?>
	</div>