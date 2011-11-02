<?php require('templateHeader.php'); ?>
	<form id="post" method="get" action="<?php echo get_bloginfo('url')?>/wp-admin/options-general.php" name="templatePreview">
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<p><b>Preview template</b></p>								
						<p >
							<?=$templateData[0]->template_name?> (Render As: <?=$templateData[0]->render_as == "T" ? "Text" : "HTML" ?>) 
						</p>
					<!--	
						<?php if($templateData[0]->render_as == "T"): ?>
						<p>
							<textarea <?=$disabled?> rows="15" cols="120" name="message"><?=$templateData[0]->template_text?></textarea>
						</p>
						<?php else : ?>
						<div style="border:1px solid #DFDFDF; width:770px; height:500px; padding:4px; overflow:auto">
							<?php 
								$templateText = $templateData[0]->template_text;
								if(strlen($templateText) != strlen(strip_tags($templateText)))
								{
									//echo stripslashes($templateText);
									
								}
								else
								{
									//echo stripslashes(nl2br($templateText));
									
								}
							?>
						</div>
						<?php endif ?>
					-->		
						<div class="show">
							<iframe name="previewIframe" src="<?= plugins_url('thinkun-remind/setting/template/ajax_partial/templatePreview.php?previewFor=list&tempId='.$templateData[0]->template_id.'&dirPath='.dirPath)?>"></iframe>
						</div>						
						<div>
							<a href="options-general.php?page=templatesetting&function=templateSetting" style="text-decoration:none;">
								<input type="button" name="btnBack" value="Back" />
							</a>
							<input type="submit" name="edit" value="Edit"/>
						</div>	
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
		<input type="hidden" name="page" value="templatesetting" />
		<input type="hidden" name="task" value="edit" />
		<input type="hidden" name="function" value="templateSetting"/>
		<input type="hidden" name="template" value="<?=$templateData[0]->template_id?>" />	
		
	</form>
</div>