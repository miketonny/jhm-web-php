<?php date_default_timezone_set('Pacific/Auckland');
 
$con = mysqli_connect('localhost', 'root', '');
if(!$con){ die('Could not connect: ' . mysql_error()); }
$db_selected = mysqli_select_db($con,'jhm_db');
if(!$db_selected){ die ("Can\'t use this db : " . mysqli_error($con)); }
/* site name */
define('siteName', 'JHM Shop');
/* site url (may be used in paypal or other api) */
define('siteUrl', '/jhm/'); 
define('siteSecureUrl', '');
/* contact email (may be used in email or contact page) */
define('contactEmail', 'info@pixlbrick.in');
/* paypal email id */
define('paypalId', 'pixl.pawan@gmail.com');
/* site language */
define('siteLang', 'English');
/* no of p[roduct in 1 group , on category page */
define('noOfProductInGroup', 28);
/* function for chk sll login, means secure */
function ssl($securepage){
	/*if ($_SERVER['HTTPS'] == 'on') { // we are on a secure page.
        if (!$securepage) { // but we shouldn't be!
          	$url = str_replace('/', '', siteUrl).$_SERVER['REQUEST_URI'];
          	header('location: '.$url); exit;
        }
  	} else { // we aren't on a secure page.
        if ($securepage) { // but we should be!
          	$url = str_replace('/', '', siteSecureUrl).$_SERVER['REQUEST_URI'];
          	header('location: '.$url); exit;
        }
  	}*/
}
//ssl(1); ?>