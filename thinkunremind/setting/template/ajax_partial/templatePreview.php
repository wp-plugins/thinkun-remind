<?php 
error_reporting(E_WARNING | E_PARSE);
if(isset($_GET['dirPath']))
{
	$dirPath = $_GET['dirPath'];
}
else 
{
	$dirPath = $_POST['dirPath'];
}
require_once($dirPath."wp-config.php") ;
global $wpdb;

if(isset($_GET['previewFor']) && $_GET['previewFor'] == "list")
{
	if(isset($_GET['tempId']) && is_numeric($_GET['tempId']))
	{
		$templateId = $_GET['tempId'];
		$sql ="SELECT template_text,render_as FROM ".$wpdb->prefix."remind_template WHERE template_id=".$templateId;
		$templateData =  selectData($sql);
		$render_as = $templateData[0]->render_as;
		$templateText = $templateData[0]->template_text;
	}
}
else
{
	if(isset($_POST['previewRenderAs']) && isset($_POST['previewTemplateText']))
	{
		if(!empty($_POST['previewRenderAs']) && !empty($_POST['previewTemplateText']))
		{
			$render_as = $_POST['previewRenderAs'];
			$templateText = $_POST['previewTemplateText'];
		}
		else
		{
			die("Mandatory fields are empty!!!");
		}
	}
	else
	{
		die("Sorry some parameters are missing!!!");
	}
}

if($render_as == "T")
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
elseif($render_as == "H")
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
