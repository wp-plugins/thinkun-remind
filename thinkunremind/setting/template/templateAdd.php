<?php require('templateHeader.php'); ?>
	<form id="post" method="post" action="" name="addTemplate" onsubmit="return templateDataValidation();">
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<p><b>Add template</b></p>					
						<p><b>Template name:</b> 
							<input type="text" name="templateName" id="templateName" value=""/>
						</p>	
						<p><b>Render as:</b> 
							<input type="radio" name="renderAs" id="renderAs" value="T" /> Text
							<input type="radio" name="renderAs" id="renderAs" value="H" /> HTML
						</p>
						<b>Enter Text or HTML Below:</b>	
						<p>
							<textarea  rows="15" cols="120" id="templateText" name="templateText"></textarea>	
						</p>
						<div style="width:170px;">
							<span>
								<a href="options-general.php?page=templatesetting&function=templateSetting" style="text-decoration:none;">
									<input type="button" name="btnCancel" value="Cancel" />
								</a>
							</span>
							<span>
								<input type="button" onClick="templatePreview()"  value="Preview" name="preview">
							</span>
							<span id="btnSave" style="display:none;float:right">
								<input type="submit" name="save" value="Save"/>
							</span>
						</div>	
					</div>
				</div>
				<!--Display template preview Start-->
				<div id="preview" class="preview-content">
					<iframe id="previewIframe" name="previewIframe"></iframe>
				</div>
				<!--Display template preview End-->
			</div>
			<br class="clear">
		</div>
		<input type="hidden" name="page" value="templatesetting" />
		<input type="hidden" name="function" value="templateSetting" />
		<input type="hidden" name="task" value="add" />
	</form>
	
	<!--- Create form with target to iframe: For Preview -->
		<form id="frmPreview" name="frmPreview" method="post" target="previewIframe" action="<?= plugins_url('thinkunremind/setting/template/ajax_partial/templatePreview.php')?>">
			<input type="hidden" name="previewRenderAs" id="previewRenderAs" />
			<input type="hidden" name="previewTemplateText" id="previewTemplateText" />
			<input type="hidden" name="dirPath" value="<?=dirPath?>"/>
		</form>
	<!--- Create form with target to iframe: For Preview -->
</div>