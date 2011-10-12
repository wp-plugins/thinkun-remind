<?php require('template/templateHeader.php'); ?>
	<form id="customField" method="get" action="<?php echo get_bloginfo('url')?>/wp-admin/options-general.php" name="customField">
	<input type="hidden" name="page" value="templatesetting" />
	<input type="hidden" name="function" value="customFieldSetting" />
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<br/>
						<table width="55%" cellpadding="5" cellspacing="5">
							<tr>
								<th align="left">Field Name</th>
								<th align="left">Code</th>
							</tr>
							<tr>
								<td>Name</td>
								<td>[name]</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>Message</td>
								<td>[message]</td>
								<td>&nbsp;</td>
							</tr>
							<?php foreach($customFiled as $cf) {
							?>
							<tr>
								<td><?=$cf->field_name?></td>
								<td>[<?=$cf->field_code?>]</td>
								<td><a onClick= "return deleteField(<?=$cf->field_id?>,'<?=$cf->field_code?>');" href="options-general.php?page=templatesetting&function=deletefield&field=<?=$cf->field_id?>">Delete</a></td>
							</tr>
							<?php } ?>
							
						</table>
						<br/>	
						<hr/>
						<br/>
						<div>
							<p>
								<input type="text" name="fieldName" id="fieldName"/>
							</p>
																						
							<input type="submit" name="save" value="Add custom field"/>		
																
						</div>	
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</form>
</div>