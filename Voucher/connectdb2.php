 <?php 
	//connect db
$dbhost='polybird.net';
$dbuser ='polybird_kai';
$dbpass='123456';
$dbname= 'polybird_voucher';
 $db=mysql_connect($dbhost,$dbuser,$dbpass) or die ("ต่อไม่ติดนะต๊ะ");
 mysql_select_db($dbname) or die("ไม่สามารถต่อ $dbname ได้");
 mysql_set_charset('utf8');

 ?>