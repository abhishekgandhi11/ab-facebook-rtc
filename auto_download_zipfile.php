<?php
session_start();
ob_start();
	
$zip_path = 'download/'.$_SESSION['user_name'].'.zip';
if(file_exists($zip_path))
{	
$zip_name =$_SESSION['user_name'].".zip";
header( "Pragma: public" );
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Cache-Control: public" );
header( "Content-Description: File Transfer" );
header( "Content-type: application/zip" );
header( "Content-Disposition: attachment; filename=\"" . $zip_name . "\"" );
header( "Content-Transfer-Encoding: binary" );
header( "Content-Length: " . filesize( $zip_path ) );
readfile( $zip_path );
	
	
}
?>
