<?php 
error_reporting(E_WARNING | E_PARSE);
$dirPath = $_POST['dirPath'];
require_once($dirPath."wp-config.php") ;
require_once("commonFunction.php");
global $wpdb;

if(isset($_POST['previewTemplateId']))
{
	$postData =  array();
	$postData['customField'] = $_POST['previewCustomField'];
	$postData['name'] = $_POST['previewName'];
	$postData['message'] = $_POST['previewMessage'];
	$postData['templateId'] = $_POST['previewTemplateId'];
	$templateData =  getEmailMessage($postData);	
	$templateText = $templateData['message'];
	$renderAs = $templateData['renderAs'];
}
else
{
	die("Parameters missing!!!");
}

if($renderAs == "T")
{
	//echo htmlentities(stripslashes($templateText));
	if(strlen($templateText) != strlen(strip_tags($templateText)))
	{
		//echo stripslashes($templateText);
		echo htmlentities(stripslashes($templateText));
	}
	else
	{
		echo stripslashes(nl2br($templateText));
	}
}
elseif($renderAs == "H")
{
		if(strlen($templateText) != strlen(strip_tags($templateText)))
		{
			echo stripslashes($templateText);
		}
		else
		{
			echo stripslashes(nl2br($templateText));
		}
}
else
{
	die("Sorry!! There seems to be some error with the paramters. ");
}

?>