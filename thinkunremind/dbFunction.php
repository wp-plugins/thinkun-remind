<?php
function insertMessage($postData)
{
	global $wpdb;		
	if($wpdb->insert($wpdb->prefix."remind_email", array( 'template_id' => $postData['templateId'], 'to' => $postData['to'],'name'=>$postData['name'],'subject'=>$postData['subject'],'message'=>$postData['message'], 'custom_fields'=>$postData['customField'], 'create_date'=> date("Y-m-d h:i:s"))))
	{
		return true;
	}
	else 
	{
		return false;
	}
}
function selectData($sql)
{
	global $wpdb;	
	$result = $wpdb->get_results( $sql);
	return $result;
} 

function updateData($sql)
{
	global $wpdb;	
	if($wpdb->query($sql))
	{
		return true;
	}
	else 
	{
		return false;
	}
}
function insertData($table,$postData)
{
	global $wpdb;	
	if($wpdb->insert($table,$postData))
	{
		return true;
	}
	else 
	{
		return false;
	}
}
function deleteData($sql)
{
	global $wpdb;	
	if($wpdb->query($sql))
	{
		return true;
	}
	else 
	{
		return false;
	}
}
?>