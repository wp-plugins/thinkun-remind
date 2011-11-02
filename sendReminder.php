<link rel='stylesheet' href="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkun-remind/css/main.css" type='text/css' media='all' />
<script src="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkun-remind/js/jquery.min.js"></script>
<script src="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkun-remind/js/main.js"></script>
<div class="wrap">	
	<h2>Thinkun Remind</h2>
	<div style="float:left;width:716px;">
	Send a branded and personalised html or text email to anyone, like a customer who you'd like to remind to leave feedback for you. Create your own email templates and reuse them. Include custom fields to personalise the email. Export a history of all emails sent as a CSV file.
	</div>
	<div style="float:right;">
		<a class="link-button" href="<?php echo get_bloginfo('url')?>/wp-admin/options-general.php?page=templatesetting">Edit plugin settings</a>
		<p style="padding-top:5px;"><a class="link-button" style="padding-right:12px;" href="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkun-remind/exportData.php?dirPath=<?=dirPath?>">Export logs as CSV</a></p>
	</div>
	<div  style="clear:both;"></div>
	<form id="post" method="post" action="" name="post" onsubmit="return sendMailValidation();">
		<div class="metabox-holder has-right-sidebar" id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<div id="titlewrap">
							<?php 
								if(isset($_GET['msg'])) 
								{
									echo '<div class="info">'.$_GET['msg'].'</div>';
								}
							?>
						<br/>	
						
						<div class="lable">Email template:</div>
						<div class="field">						
							<select name="template" id="template" class="input-box">
								<option value=''>--- Please select email template ---</option>
								<?php foreach($this->getTemplate() as $key=>$temp) {?>
								<option value="<?=$temp->template_id?>"><?=$temp->template_name?></option>
								<?php } ?>
							</select>							
						</div>
						<div style="clear:both"></div>						
						
						<h2>Step 1: personalise email</h2>
						
						<div class="lable">Name:</div>
						<div class="field">
							<input type="text" id="name"  class="input-box" value="" name="name">
						</div>
						<div style="clear:both"></div>
						<div class="lable">Subject:</div>
						<div class="field">
							<input type="text" id="subject" class="input-box" value="" name="subject">
						</div>
						<div style="clear:both"></div>
						<div class="lable">Email address:</div>
						<div class="field">
							<input type="text" id="email" class="input-box" value=""  name="to">
						</div>
						<div style="clear:both"></div>
						<div class="lable" >Message:(optional)</div>
						<div class="field">
							<textarea rows="5" cols="38" id="message" name="message"></textarea>						
						</div>
						<div style="clear:both"></div>
						<?php 
							$customField = '';
							foreach($this->getCustomField() as $key=>$cField) 
							{
								if($key==0)	
									$customField .= $cField->field_code;
								else 
								$customField .= '|'.$cField->field_code;
						?>
						<div class="lable"><?=stripslashes($cField->field_name)?></div>
						<div class="field">
							<input type="text"  class="input-box" value=''  name=customField[<?=stripslashes($cField->field_code)?>] id=<?=$cField->field_code?>>
						</div>
						<div style="clear:both"></div>
						<?php 
							} 
						?>
						<input type="hidden" name="customFields" id="customField" value='<?php echo $customField; ?>' />
						<br/>
						<div class="lable" style="width:80px;"><h2 style="margin-top:0px;">Step 2 : </h2></div>
						<div class="field">
						<h2 style="margin-top:0px;padding-top:5px;">
							<input type="button" onClick="emailPreviewFrame()"  value="Preview" name="preview">
						</h2>	
						</div>
						<div style="clear:both"></div>
						
						<div  style="display:none" id="send" >	
							<div class="lable" style="width:80px;"><h2 style="margin-top:0px;">Step 3 : </h2></div>
							<div class="field">
							<h2 style="margin-top:0px;padding-top:5px;">
								<input type="submit" value="Send"  name="send">
							</h2>	
							</div>
							<div style="clear:both"></div>
						</div>
					</div>
					<!--Display template preview Start-->
						<div id="preview" class="preview-content">
							<iframe id="previewIframe" name="previewIframe"></iframe>
						</div>
					<!--Display template preview End-->
				</div>

				</div>
			</div>
			<br class="clear">
		</div>
	</form>
	
	<!--- Create form with target to iframe: For Preview -->
	
		<form id="frmPreview" name="frmPreview" method="post" target="previewIframe" action="<?= plugins_url('thinkun-remind/emailPreview.php')?>">
			<input type="hidden" name="previewName" id="previewName" />
			<input type="hidden" name="previewMessage" id="previewMessage" />
			<input type="hidden" name="previewTemplateId" id="previewTemplateId" />
			<input type="hidden" name="previewCustomField" id="previewCustomField" />
			<input type="hidden" name="dirPath" value="<?=dirPath?>"/>
		</form>
	<!--- Create form with target to iframe: For Preview -->
	
</div>


