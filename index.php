<?php
/*
	CONFIG
*/
include_once('config.php');
?>
<?php
/*
	MAIL
*/
if(isset($_POST['ra'])){
	//http://pad.bombenlabor.de/p/<padid>/export/txt
	$name   = $_POST['name'];
	$email  = $_POST['mail'];
	$reason = $_POST['reason'];

	$pad_name = $_POST['ra'];
	$pad_text = file_get_contents($pad_url.$pad_name.'/export/txt');
	$mail_subject = '[pad - abuse] - '.$pad_name;
	$mail_text = $name.'('.$email.') '
	."complained about: ".$pad_url.$pad_name
	."\n\nreason:\n".$reason
	."\n\ntext:\n".$pad_text;
	mail($mail_receiver, $mail_subject, $mail_text, "From: ".$mail_sender."\nReply-To: ".$email);
	//header('Status: 301 Moved Permanently'); 
	//header('Location:'.$_SERVER['PHP_SELF']); 
	//exit; 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>phpmyPadmin</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="custom.css">	
	
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript">
		$(function(){
			// Dialog			
			$('#dialog').dialog({
				autoOpen: false,
				width: 400,
				buttons: {
					"Ok": function() { 
						$("#report_abuse_form").validate();
						$('#report_abuse_form').submit();
						if($("#report_abuse_form").valid())
							$(this).dialog("close");
					}, 
					"Cancel": function() { 
						$(this).dialog("close"); 
					} 
				}
			});
			
			// Dialog Link
			$('.dialog_link').click(function(){
				pad_id = $(this).attr('id');
				$("#report_abuse_id").attr('value',pad_id);
				$("#report_abuse_id_").attr('value',pad_id);
				$('#dialog').dialog('open');
				return false;
			});
			
		});
	</script>

	<style type="text/css">
		label { width: 10em; float: left; }
		label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
		p { clear: both; }
		.submit { margin-left: 12em; }
		em { font-weight: bold; padding-right: 1em; vertical-align: top; }
	</style>

</head>
<body>
<h1>Pads:</h1>
<ul>
<?php
/*
	LOGIC
*/

// connect to Database
$DB = mysql_connect($db_host, $db_user, $db_password, $db_database);
if(!$DB)
	die('can\'t connect to database: '.mysql_error());
/*
 Get the List of Pads
 */
$query = 'SELECT `value` FROM '.$db_database.'.'.$db_table.' WHERE `key` LIKE \'r%\'';
$result = mysql_query($query);
if (!$result)
    die('error in query: ' . mysql_error());
/*
  Print the List of Pads
 */
echo '<table>';
while($row = mysql_fetch_assoc($result)){
	$pad_name = $row['value'];
	$pad_name = substr($pad_name, 1,strlen($pad_name)-2);
	echo '<tr>'."\n\t";
	echo '<td><a href="'.$pad_url.$pad_name.'" target="_blank">'.$pad_name.'</a>&nbsp;'."</td>\n\t";
	echo '<td><a href="#" id="'.$pad_name.'" class="dialog_link">report abuse</a></td>';
	echo '</tr>'."\n";
}
echo '</table>';

//close DB_connection
mysql_close($DB);

?>
</ul>


<!-- ui-dialog -->
<div id="dialog" title="Report Abuse">
	<form id="report_abuse_form" action="index.php" method="post">
		<table>
		<tr>
			<td>id</td>
			<td><input name="ra_" id="report_abuse_id_" type="text" size="30" maxlength="30" disabled></td>
			<input name="ra" id="report_abuse_id" type="hidden">
		</tr>
		<tr>
			<td>name</td>
			<td><input name="name" class="required" type="text" size="30" placeholder="Name" minlength="2" maxlength="30"></td>
		</tr>
		<tr>
			<td>eMail</td>
			<td><input name="mail" class="required email" type="text" size="30" placeholder="eMail" maxlength="30"></td>
		</tr>
		<tr>
			<td>reason</td>
			<td><textarea name="reason" cols="50" rows="10"></textarea></td>
		</tr>
		</table>
	</form>

</div>
</body>
</html>