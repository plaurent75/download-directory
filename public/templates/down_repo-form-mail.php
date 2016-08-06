<div id="alertlink">
	<p><?php _e('You will receive an email alert ONLY when a new version will be available','download-directory') ?></p>
	<form action="add_alert_down" method="post" id="newAlertForm">
		<div class="input-group">
			<input id="email_down" type="email" name="mail_alert" class="form-control" placeholder="email@domain.com" />
			<?php wp_nonce_field( 'ajax-alert-down-nonce', 'security' ); ?>
			<span class="input-group-btn"><button class="btn btn-warning" type="submit"><?php _e('Register','download-directory') ?></button></span>
		</div>
	</form>
	<div id="down-repo-feedback"></div>
</div>
