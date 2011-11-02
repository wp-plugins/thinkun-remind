<?php
define("dirPath",ABSPATH);
require_once(dirPath. "wp-config.php") ;
require_once('dbFunction.php');
require_once("commonFunction.php");
/*
Plugin Name: Thinkun Remind
Plugin URI: http://www.thinkun.net/blog/thinkun-remind-wordpress-plugin/
Description: Send a branded and personalised html or text email to anyone, using your own custom email templates and custom fields.
Version: 1.1.3
Author:  Thinkun
Author URI: http://www.thinkun.net
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

/*----- Class code start -----*/

class Remind {

	var $mail_content_type = "text/plain";
	
	
// install database start
	function dbInstall () {
	
	global $wpdb;
	$table_name = $wpdb->prefix."remind_email";
	
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {  
  
      $sql = "CREATE TABLE ".$table_name." (
	  `email_id` int(11) NOT NULL AUTO_INCREMENT,
	  `template_id` int(11) NOT NULL,
	  `to` varchar(255) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `subject` varchar(255) NOT NULL,
	  `message` text,
	  `custom_fields` text,
	  `create_date` datetime NOT NULL,
	  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`email_id`)
	)";	
		if(!$wpdb->query($sql))
		{
			$this->createLog('error',mysql_error());
			echo mysql_error();
			die;
		}
		 
	 }
	 
	$table_name = $wpdb->prefix."remind_field";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {      
      $sql = "CREATE TABLE ". $table_name." (
	  `field_id` int(11) NOT NULL AUTO_INCREMENT,
	  `field_name` varchar(255) NOT NULL,
	  `field_code` varchar(255) DEFAULT NULL,
	  `create_date` datetime NOT NULL,
	  PRIMARY KEY (`field_id`)
	)";	
	if(!$wpdb->query($sql))
			{
				$this->createLog('error',mysql_error());
				echo mysql_error();
				die;
			}
	 }
	$table_name = $wpdb->prefix."remind_setting";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {      
      $sql = "CREATE TABLE ".$table_name." (
	  `from_name` varchar(255) NOT NULL,
	  `from_email` varchar(255) NOT NULL
	)";		
		if(!$wpdb->query($sql))
			{
				$this->createLog('error',mysql_error());
				echo mysql_error();
				die;
			}
	 }
	 $table_name = $wpdb->prefix."remind_template";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {      
      $sql = "CREATE TABLE ".$table_name." (
	  `template_id` int(11) NOT NULL AUTO_INCREMENT,
	  `template_name` varchar(255) NOT NULL,
	  `template_text` text NOT NULL,
	  `render_as` enum('T','H') NOT NULL,
	  `create_date` datetime NOT NULL,
	  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`template_id`)
	) ";		
		if(!$wpdb->query($sql))
			{
				$this->createLog('error',mysql_error());
				echo mysql_error();
				die;
			}
	}	
}	

function example_deinstall() 
{
	 global $wpdb;
  $thetable = $wpdb->prefix."remind_email";  
  $wpdb->query("DROP TABLE IF EXISTS $thetable");
}
	
/*-----Function is used to create admin menu Start-----*/
	function addMenu() { 
		add_menu_page(
			"Thinkun-Remind", // page title
			"Thinkun Remind", // menu title
			5,		// capability
			__FILE__, // menu_slug
			array(&$this, 'sendreminder'), // function name
			get_bloginfo('url')."/wp-content/plugins/thinkun-remind/image/email-icon.gif", // icon url
			"30" // menu position 
		); 		
	}
	
	function adminSettingTab() { 
	
		if(current_user_can('administrator'))
		{
			add_options_page("Thinkun-Remind", "Thinkun Remind", 1,"templatesetting", array(&$this, 'setting'));
		}		
	}
	
	/*-----Function is used to create admin menu End-----*/
	
	function setting()
	{
		if(current_user_can('administrator'))
		{
			if(isset($_REQUEST['function']) && $_REQUEST['function']!='')
			{
				$function = $_REQUEST['function'];
				
			} else {
				$function = "mailsetting";
			}			
			switch ($function) {
				case "templateSetting":
					$this->templateSetting();
					break;
				case "customFieldSetting":
					$this->customFieldSetting();
					break;
				case "deletefield":
					$this->deletefield();
					break;
				case "mailsetting":
				$this->mailSetting();
				break;
				case "deleteTemplate":
				$this->deleteTemplate();
				break;
				}
		}
		else 
		{
			echo "You do not have sufficient permissions to access this page.";
			die;
		}
	}	
	
	
	
	/*** Function to check render email template ******/
	
	function templateSendAs($templateId=null)
	{
		$render_as = "T";
		if($templateId!=null)
		{	
			global $wpdb;
			$render_as_obj = $wpdb->get_row("SELECT render_as FROM ".$wpdb->prefix."remind_template WHERE template_id='$templateId'");
			$render_as = $render_as_obj->render_as;
		}
		return $render_as;
	}
	
	/*******************************************/
	/*-----Function is used to send email Start-----*/

	function sendreminder()
	{
		
		/*-----Used to send email Start-----*/
			if(isset($_POST['send']))
			{	
				$message = "";
				// Check if from name and from email exists or not
				$fromName =  $this->fb_mail_from_name();
				$fromEmail = $this->fb_mail_from();
				if($fromName == '' || $fromEmail == '')
				{
					$message = "Please update plugin settings to specify a From name and From email";
				}
				else 
					{
					if($_POST['template']!='' && $_POST['name']!='' && $_POST['to']!='' && $_POST['subject']!='')
					{
					
						$postData = array();
						/*-----Prepare post data Start-----*/
						
							$postData['templateId'] = $_POST['template'];
							$render_as = $this->templateSendAs($postData['templateId']);
							$postData['name'] = $_POST['name'];
							$postData['subject'] = $_POST['subject'];
							$postData['to'] = $_POST['to'];
							$postData['message'] = $_POST['message'];
							
							$postData['customFieldArray'] = $_POST['customField'];	
							$postData['customField'] = json_encode($_POST['customField']);
							
							 $templateData =  getEmailMessage($postData);
							
							//$postData['message'] = $templateData['message'];
							
						/*-----Prepare post data End-----*/			
						if($this->isValidEmail($postData['to'])) // check valid email address		
						{
							$result = insertMessage($postData);
							if($result)
							{	
								if($this->sendEmail($postData['name'],$postData['to'],$postData['subject'],$templateData['message'],$render_as))
								{
									/*---Used to create log Start---*/
									$logData = array(
										"date" => date("d-m-Y"),
										"template used"	=> $postData['templateId'],
										"name" =>$postData['name'],
										"email" =>$postData['to'],
										"message" =>$postData['message'],
									);
									
									$this->createLog(date("Y-m-d"),json_encode($logData));
									/*---Used to create log End---*/
									
									$message = "Email successfully sent to $name (".$postData['to'].")";
								}
								else 
								{
									$message = "Unable to send email: SMTP error occurs";
								}	
							}
							else 
							{
								$message = "Unable to procced database error occurs";
							}
						}
						else 
						{
							$message = "Invalid email address";
						}	
					}
					else // If post data not found 
					{
						$message = "Mandatory fields are required";
					}
				}
				$message = urlencode($message);
				
				echo "<script>location = '".get_bloginfo('url')."/wp-admin/admin.php?page=thinkun-remind/remind.php&msg=".$message."';</script>";
				//wp_redirect('admin.php?page=remind/reminder.php&msg='.$message);
			}
		/*-----Used to send email End-----*/
		require 'sendReminder.php';
	}
	/*-----Function is used to create admin menu End-----*/

	/*-----Function is used to set thinkun setting Start-----*/
	function templateSetting() 
	{
		global $wpdb;
		if(isset($_REQUEST['task']) && isset($_REQUEST['template']) && is_numeric($_REQUEST['template']))
		{
			
			$task = $_REQUEST['task'];
			$templateId = $_REQUEST['template'];
			 $sql ="SELECT template_id,template_name,template_text,render_as FROM ".$wpdb->prefix."remind_template WHERE template_id=".$templateId;
			$templateData =  selectData($sql);
			if($task == 'preview')
			{
				$disabled = "disabled";
				require 'setting/template/templatePreview.php';
			}
			else if($task == 'edit')
			{
				$disabled = "";
				require 'setting/template/templateEdit.php';
			}	
			else if($task == 'save')
			{
				
				 $sql = "update ".$wpdb->prefix."remind_template 
						set template_name='".$_REQUEST['templateName']."',
						template_text = '".$_REQUEST['templateText']."',
						render_as     = '".$_REQUEST['renderAs']."'
						where template_id = $templateId
						";
				//echo $sql; die;
				if(updateData($sql))
				{
					$message = "Template updated successfully";
				}
				else 
				{
					$message = "No changes has been made in the template";
				}
				$message = urlencode($message);
				echo "<script>location = '".get_bloginfo('url')."/wp-admin/options-general.php?page=templatesetting&function=templateSetting&msg=".$message."';</script>";
				//wp_redirect('options-general.php?page=templatesetting&msg='.$message);
			}
		}
		else if(isset($_REQUEST['task'])  && $_REQUEST['task'] == 'add') // used to add template
		{
			
			if(isset($_REQUEST['save']))
			{
				if($_REQUEST['templateName']!='' && $_REQUEST['templateText']!='')
				{
					$templateName = $_REQUEST['templateName'];
					$templateText = $_REQUEST['templateText'];
					$renderAs = $_REQUEST['renderAs'];
					$templateData = array(
						'template_name' => $templateName,
						'template_text' => $templateText,
						'render_as'     => $renderAs,
						'create_date' => date("Y-m-d h:i:s")
					);
					if(insertData($wpdb->prefix.'remind_template',$templateData))
					{
						$message = "Template added successfully";
					}
					else 
					{
						$this->createLog('error',"Unable to insert template - ".mysql_error());
						$message = "Unable to insert template data mysql error occurs";
					}	
				}
				else 
				{
					$message = "Mandatory fields are required";
				}
				$message = urlencode($message);
				echo "<script>location = '".get_bloginfo('url')."/wp-admin/options-general.php?page=templatesetting&function=templateSetting&msg=".$message."';</script>";
				//wp_redirect('options-general.php?page=templatesetting&msg='.$message);
			}
			require 'setting/template/templateAdd.php';
		}
		else 
		{
			require 'setting/template/templateListing.php';
		}	
	}	
	/*-----Function is used to set thinkun setting End-----*/
	
	/*-----Function is used to add custom field Start-----*/
	
	function customFieldSetting()
	{
		global $wpdb;
		if(isset($_GET['save']))
		{
			if($_GET['fieldName']!='')
			{
				$fieldName = $_GET['fieldName'];
				$fieldCode = strtolower(str_replace(' ','',$fieldName));
				$sql = "select field_id from ".$wpdb->prefix."remind_field where field_code = '".$fieldCode."'";
				$checkFieldName = selectData($sql);
				if($fieldCode == 'name' || $fieldCode == 'message')
				{
					$checkFieldName[] = 'exists';
				}
				if(count($checkFieldName)>0)
				{
					$message = "Field name already exists";
				}				
				else 
				{
					$fieldData = array(
					"field_name" => $fieldName,
					"field_code" => $fieldCode
					);
					if(insertData($wpdb->prefix.'remind_field',$fieldData))
					{
						$message = "Field added successfully";
					}
					else 
					{
						$this->createLog('error',"Unable to insert custom field - ".mysql_error());
						$message = "Unable to insert custom field data mysql error occurs";
					}
				}
				
			}
			else 
			{
				$message = "Field name is required";
			}
			$message = urlencode($message);
			echo "<script>location = '".get_bloginfo('url')."/wp-admin/options-general.php?page=templatesetting&function=customFieldSetting&msg=".$message."';</script>";
			//wp_redirect('options-general.php?page=templatesetting&function=customFieldSetting&msg='.$message);
		}
		$sql = "select * from ".$wpdb->prefix."remind_field";
		$customFiled = selectData($sql); 
		require 'setting/addCustomField.php';
	}
	
	/*-----Function is used to add custom field End-----*/
	
	/*-----Function is used to delete template Start-----*/
	
		function deleteTemplate()
		{
			global $wpdb;
			if(isset($_GET['template']) && $_GET['template']!='' && is_numeric($_GET['template']))
			{
			$sql = "delete from ".$wpdb->prefix."remind_email where template_id = ".$_GET['template'];
			deleteData($sql);
			
				$sql = "delete from ".$wpdb->prefix."remind_template where template_id = ".$_GET['template'];
				if(deleteData($sql))
				{
					$message = "Template deleted successfully";
				}
				else 
				{
				$message = "Unable to delete template mysql error occurs";
				}
			$message = urlencode($message);
			echo "<script>location = '".get_bloginfo('url')."/wp-admin/options-general.php?page=templatesetting&function=templateSetting&msg=".$message."';</script>";
			//wp_redirect('options-general.php?page=templatesetting&function=templateSetting&msg='.$message);
			}
		}
		
	/*-----Function is used to delete template End-----*/
	
	/*-----Function is used to delete custom field Start-----*/
	
	function deletefield()
	{
		global $wpdb;
		if(isset($_GET['field']) && $_GET['field']!='' && is_numeric($_GET['field']))
		{
			$sql = "delete from ".$wpdb->prefix."remind_field where field_id = ".$_GET['field'];
			if(deleteData($sql))
			{
				$message = "Field deleted successfully";
			}
			else 
			{
				$message = "Unable to delete custom field data mysql error occurs";
			}
			$message = urlencode($message);
			echo "<script>location = '".get_bloginfo('url')."/wp-admin/options-general.php?page=templatesetting&function=customFieldSetting&msg=".$message."';</script>";
			//wp_redirect('options-general.php?page=templatesetting&function=customFieldSetting&msg='.$message);
		}
	}	
	
	/*-----Function is used to delete custom field End-----*/
	
	/*-----Function is used to for mail setting Start-----*/
	function mailSetting()
	{
		global $wpdb;
		if(isset($_GET['save']))
		{
			$fromName = $_GET['fromName'];
			$fromEmail = $_GET['fromEmail'];
			if($fromName!='' && $fromEmail!='')
			{
				if($this->isValidEmail($fromEmail))
				{
					$sql = "select from_name from ".$wpdb->prefix."remind_setting";
					$count  = count(selectData($sql));
					if($count > 0 )
					{
						$sql = "update ".$wpdb->prefix."remind_setting set from_name='".$fromName."',from_email='".$fromEmail."' where 1";
					
						if(updateData($sql))
						{
							$message = "Record updated successfully";
						}
						else 
						{
						$message = "No changes have been made to the settings.";
						}
					}
					else 
					{
						$postData = array(
							"from_name" =>$fromName,
							"from_email" => $fromEmail
						);
						$table_name = $wpdb->prefix.'remind_setting';
						if(insertData($table_name,$postData))
						{
							$message = "Record inserted successfully";
						}
						else 
						{
						$message = "No changes have been made to the settings.";
						}
						
					}
					
				}
				else 
				{
					$message = "Invalid email address";
				}
			}
			else 
			{
				$message = "Mandatory fields are required";
			}
			$message = urlencode($message);
			echo "<script>location = '".get_bloginfo('url')."/wp-admin/options-general.php?page=templatesetting&function=mailsetting&msg=".$message."';</script>";
			//wp_redirect('options-general.php?page=templatesetting&function=mailsetting&msg='.$message);
		}
		$sql = "select * from ".$wpdb->prefix."remind_setting";
		$mailSetting = selectData($sql);
		require 'setting/mailSetting.php';
	}
	/*-----Function is used to for mail setting End-----*/
	
	/*-----Function is used to send email Start-----*/
	function sendEmail($name,$to,$subject,$message,$render="")
	{
		$to = $to;
		$subject = stripslashes($subject);
		$message = stripslashes($message);
		
		if($render!="" && $render=="H")
		{
			$this->mail_content_type = "text/html";
		}
				
		if(wp_mail($to, $subject, $message))
		{
			return true;
		}
		else 
		{
			return false;
		}
		
	}
	/*-----Function is used to send email End-----*/
	
	/* ------- Used to create sms log Start ------- */
		function createLog($fileName,$msg)
		{
			if(!is_dir(ABSPATH.'log'))
			mkdir(ABSPATH.'log', 0777);
			$myFile = ABSPATH."log/$fileName.txt";			
			$fh = fopen($myFile, 'a') or die("can't open file");
			$stringData = "################## ". date("Y-m-d h:i:s")." ##########################\n";
			$stringData .= $msg."\n";				
			fwrite($fh, $stringData);
			fclose($fh);
		}
	/* ------- Used to create sms log End ------- */



	/* ------- Used to validate email Start ------- */
	function isValidEmail($email)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
	}
	/* ------- Used to validate email End ------- */

	/*-----Function used to get template Start-----*/
	function getTemplate()
	{	
		global $wpdb;
		 $sql = "select template_id,template_name,render_as from ".$wpdb->prefix."remind_template order by DATE_FORMAT(create_date,'%Y-%m-%d') ASC";
		
		return selectData($sql);		
	}
	/*-----Function used to get template End-----*/

	/*-----Function used to get template Start-----*/
	function getCustomField()
	{	
		global $wpdb;
		$sql = "select field_id,field_name,field_code from ".$wpdb->prefix."remind_field";
		return selectData($sql);			
	}
	/*-----Function used to get template End-----*/
	
	/*-----Function used Change Mail Setting Start -----*/
	
	function fb_mail_from_name() {
		global $wpdb;
		$sql = "select from_name from ".$wpdb->prefix."remind_setting";
		$mailSetting = selectData($sql);
		$fromName       = $mailSetting[0]->from_name;		
		return $fromName;
		}
	
	function fb_mail_from() 
	{
		global $wpdb;
		$sql = "select from_email from ".$wpdb->prefix."remind_setting";
		$mailSetting = selectData($sql);
		$fromEmail       = $mailSetting[0]->from_email;			
		return $fromEmail;
	}
		 
	function set_mail_header()
	{
		return $this->mail_content_type;
	}
	/*-----Function used Change Mail Setting End-----*/	 
}

/*----- Class code End -----*/

/*-----Used to intialize class object Start-----*/

	$obj = new Remind();

/*-----Used to intialize class object End-----*/

// function install db start
register_activation_hook(__FILE__,array(&$obj, 'dbInstall'));
//register_deactivation_hook(__FILE__, array(&$obj, 'example_deinstall'));
/*-----Call create menu function Start-----*/

	add_action('admin_menu', array(&$obj, 'addMenu'));
	add_action('admin_menu', array(&$obj, 'adminSettingTab'));

/*-----Call create menu function End-----*/

 add_filter( 'wp_mail_from', array(&$obj, 'fb_mail_from') );
 add_filter( 'wp_mail_from_name', array(&$obj, 'fb_mail_from_name') );
 add_filter( 'wp_mail_content_type', array(&$obj, 'set_mail_header') );
 
 
?>