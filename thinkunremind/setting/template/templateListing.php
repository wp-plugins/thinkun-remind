<?php require('templateHeader.php'); ?>
	<form id="post" method="post" action="" name="post">
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
				<div id="titlediv">
				<br/>
				<a class="link-button" href="options-general.php?page=templatesetting&task=add&function=templateSetting">Add new template</a> 
					<br/>
					<p><b>Available templates</b></p>					
					<div>
						<?php $template = $this->getTemplate(); if(count($template)>0) {
						foreach($template as $t)
						{
						?>
						<div>
							<?=$t->template_name?>
							<p style="margin-top:0px;">
							<a href="options-general.php?page=templatesetting&task=preview&function=templateSetting&template=<?=$t->template_id?>">Preview</a> |
							<a href="options-general.php?page=templatesetting&task=edit&function=templateSetting&template=<?=$t->template_id?>">Edit</a> | 
							<a onClick="return deleteTemplate(<?=$t->template_id?>);" href="options-general.php?page=templatesetting&function=deleteTemplate&template=<?=$t->template_id?>">Delete</a></p>
						</div>
						
						<?php } } else { echo "No template found.";}?>		
					</div>
				</div>

				</div>
			</div>
			<br class="clear">
		</div>
	</form>
</div>