<?php require('template/templateHeader.php'); ?>
	<form id="customField" method="get" action="<?php echo get_bloginfo('url')?>/wp-admin/options-general.php" name="mailSetting">
	<input type="hidden" name="page" value="templatesetting" />
	<input type="hidden" name="function" value="mailsetting" />
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<br/>						
						<p><b>From name:</b> 
							<input type="text" style="width:200px;" name="fromName" id="fromName" value="<?=$mailSetting[0]->from_name?>"/>
						</p>
						<p><b>From email:</b> 
							<input type="text" style="width:200px;" name="fromEmail" id="fromEmail" value="<?=$mailSetting[0]->from_email?>"/>
						</p>
						<input type="submit" name="save" value="Save"/>		
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</form>
</div>