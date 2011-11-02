<?php 
function getEmailMessage($postData)
{
	global $wpdb;
	$templateData = array();
	$customField = array();
	
	if(!empty($postData['customField']))
	{		
		if(array_key_exists('customFieldArray',$postData))
		{
			$customField[]  = $postData['customFieldArray'];	
		}
		else
		{
			$customField[]  = json_decode(stripslashes($postData['customField']));	
		}
		 $data = object_to_array($customField);
	}
	
	$name = $postData['name']; 
	$messageText = $postData['message'];
	$sql = "SELECT template_text,render_as FROM ".$wpdb->prefix."remind_template WHERE template_id=".$postData['templateId']; 	
	$result = $wpdb->get_row( $sql);
	$templateText = $result->template_text;
	$renderAs = $result->render_as;
	if(count($customField)>0)
	{
		foreach($customField as $key=>$cf)
		{
			$data = object_to_array($cf);
			
			if(count($data)>0)
			{
				foreach($data as $key=>$f)
				{
					$templateText = str_replace('['.stripslashes($key).']',$f,$templateText);	
				}
			}			
		}
	}
	
	$templateText = str_replace('[name]',$name,$templateText);	
	$templateData['message'] =  str_replace('[message]',$messageText,$templateText);
	$templateData['renderAs'] = $renderAs;
	return $templateData;
	//return $templateText = str_replace('[message]',$messageText,$templateText);
	
}


function object_to_array($data) 
{
  if(is_array($data) || is_object($data))
  {
    $result = array(); 
    foreach($data as $key => $value)
    { 
      $result[$key] = object_to_array($value); 
    }
    return $result;
  }
  return $data;
}

?>