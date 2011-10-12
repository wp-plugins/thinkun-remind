<?php
error_reporting(E_WARNING | E_PARSE);
require_once( $_GET['dirPath']. "wp-config.php") ;
global $wpdb;

$file = "email";

$select = "
			SELECT 
			e.create_date, 
			e.email_id, 
			e.template_id, 
			t.template_name, 
			e.to, 
			e.name, 
			e.subject, 
			e.message, 
			e.custom_fields
			FROM 
			".$wpdb->prefix."remind_email as e,
			".$wpdb->prefix."remind_template as t 
			WHERE 
			e.template_id = t.template_id order by e.email_id ASC
		";

		
$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );
$fields = mysql_num_fields ( $export );

for ( $i = 0; $i < $fields; $i++ )
{	
    $header .= mysql_field_name( $export , $i ) . ",";
}

$data="";
while( $row = mysql_fetch_row( $export ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = ",";
        }
        else
        {
			$value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . ",";
        }
        $line .= $value;
    }
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );
if ( $data == "" )
{
    $data = "\n(0) Records Found!\n";                        
}

$filename = $file."_".date("Y-m-d_H-i",time());
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
print "$header\n$data";

exit;
?>