<?php require('templateHeader.php'); ?>
	<form id="post" method="post" action="" name="editTemplate" onsubmit="return templateDataValidation();">
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<p><b>Edit template</b></p>					
						<p><b>Template name:</b> 
							<input type="text" name="templateName" id="templateName" value="<?=$templateData[0]->template_name?>"/>
						</p>	
						<p><b>Render as:</b> 
							<input type="radio" name="renderAs" id="renderAs" <?= $templateData[0]->render_as=="T" ? "checked" : "" ?> value="T" /> Text
							<input type="radio" name="renderAs" id="renderAs" <?= $templateData[0]->render_as=="H" ? "checked" : "" ?> value="H" /> HTML
						</p>
							<b>Enter Text or HTML Below:</b>	
						<p>
							<textarea <?=$disabled?> rows="15" cols="120" id="templateText" name="templateText"><?=stripslashes($templateData[0]->template_text)?></textarea>	
						</p>
						<div style="width:240px;">
							<!-- a href="options-general.php?page=templatesetting&task=preview&function=templateSetting&template=<?=$templateData[0]->template_id?>">Preview</a -->
							<span>
								<a href="options-general.php?page=templatesetting&function=templateSetting" style="text-decoration:none;">
									<input type="button" name="btnCancel" value="Cancel" />
								</a>									
							</span>
							<span>
								<input type="button" onClick="templatePreview()"  value="Continue to Preview" name="preview">
							</span>
							<span id="btnSave" style="display:none;float:right">
								<input type="submit" name="save" value="Save"/>		
							</span>
						</div>	
					</div>
					<!--Display template preview Start-->
					<div id="preview" class="preview-content">
						<iframe id="previewIframe" name="previewIframe"></iframe>
					</div>
					<!--Display template preview End-->
				</div>
			</div>
			<br class="clear">
		</div>
		<input type="hidden" name="page" value="templatesetting" />
		<input type="hidden" name="function" value="templateSetting" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="template" value="<?=$templateData[0]->template_id?>" />
	</form>
	
	<!--- Create form with target to iframe: For Preview -->
		<form id="frmPreview" name="frmPreview" method="post" target="previewIframe" action="<?= plugins_url('thinkun-remind/setting/template/ajax_partial/templatePreview.php')?>">
			<input type="hidden" name="previewRenderAs" id="previewRenderAs" />
			<input type="hidden" name="previewTemplateText" id="previewTemplateText" />
			<input type="hidden" name="dirPath" value="<?=dirPath?>"/>
		</form>
	<!--- Create form with target to iframe: For Preview -->
</div>