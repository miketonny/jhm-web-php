<?php ob_start('ob_gzhandler');
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
include 'include/config.php';
include 'include/functions.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';
switch ($action) {
case 'userQuickSignUp':userQuickSignUp($con);
	break;
case 'userRegistration':userRegistration($con);
	break;
case 'userEditProfile':userEditProfile($con);
	break;
case 'userLogin':userLogin($con);
	break;
case 'userchangepassword':userchangepassword($con);
	break;
case 'userProfilePic':userProfilePic($con);
	break;
case 'userForgetPassword':userForgetPassword($con);
	break;
case 'userResetPassword':userResetPassword($con);
	break;

/* signup from detail page */
case 'userSignUpFromDetail':userSignUpFromDetail($con);
	break;
case 'userSignUpFromDetailExpressCheckout':userSignUpFromDetailExpressCheckout($con);
	break;

case 'addToCart':addToCart($con);
	break;
case 'expressCheckout':expressCheckout($con);
	break;

case 'cartUpdate':cartUpdate($con);
	break;
case 'applyPromo':applyPromo($con);
	break;

case 'shipping_detail':shipping_detail($con);
	break;
case 'processPayment':processPayment($con);
	break;
case 'orderCancelRequest':orderCancelRequest($con);
	break;

case 'checkOutNow':checkOutNow($con);
	break;

case 'newsletterSignUp':newsletterSignUp($con);
	break;
case 'productRating':productRating($con);
	break;
case 'askAQuestion':askAQuestion($con);
	break;
case 'contactUs':ContactUs();
	break;

default:redirect(siteUrl);
}

function userResetPassword($con) {
	$user = $_POST['user'];
	$new_password = $_POST['new_password'];
	$confirm_new_password = $_POST['confirm_new_password'];

	if ($new_password == $confirm_new_password) {
		$rs = exec_query("UPDATE tbl_user SET password = '" . md5($new_password) . "' WHERE user_id = '$user'", $con);
		if ($rs) {
			setMessage('Password Successfully Changed.', 'alert alert-success');
			redirect(siteUrl);die();} else {setMessage('Failed, Some error occured.', 'alert alert-error');}
	} else {setMessage('Failed, Password doesnt match.', 'alert alert-error');}
	echo '<script> history.back(); </script>';die();
}

function userForgetPassword($con) {
	$email = $_POST['email'];
	$sql = "select * from `tbl_user` where ( email='" . $email . "' )";
	$rs = mysqli_query($con, $sql);
	if (mysqli_num_rows($rs)) {
		$userData = mysqli_fetch_object($rs);

		$token = fetchRandomToken();
		$link = siteUrl . 'resetpassword/' . $token . $userData->user_id . ''; //adds the randomness to the url
		$rsEmail = exec_query("SELECT * FROM tbl_email_template WHERE type = 'resetPassword'", $con);
		$rowEmail = mysqli_fetch_object($rsEmail);
		$content = $rowEmail->content;

		$contentHTML = html_entity_decode($content);
		$contentHTML = str_replace('{jhm :', '', $contentHTML); // replace all '{jhm : '
		$arraySearch = array(' email}', ' username}', ' link}'); // isko replace krna h
		$arrayReplace = array($userData->email, $userData->username, $link); // isse replace krna h
		$content = str_replace($arraySearch, $arrayReplace, $contentHTML); // yha milega sb
		$subject = 'Password Reset - ' . siteName;
		sendMail($subject, $content, array($userData->email));
		setMessage('Please check your mailbox, we\'ve sent a password reset email to you.', 'alert alert-success');
	} else {setMessage('The email address you entered does not exist.', 'alert alert-error');}
	echo '<script> history.back(); </script>';die();
}



function userSignUpFromDetailExpressCheckout($con) {
	$email = $_POST['email'];
	$data = explode('@', $email);
	$chk_email = mysqli_query($con, "SELECT user_id FROM tbl_user WHERE email = '$email'");
	if (mysqli_num_rows($chk_email)) {
		$row = mysqli_fetch_object($chk_email);
		$userId = $row->user_id;
	} else {
		$q = "INSERT INTO `tbl_user`(username, `email`, register_on, user_type) VALUES ('" . $data[0] . "', '$email', '" . date('c') . "', 1)";
		if (exec_query($q, $con)) {
			$userId = mysqli_insert_id($con);
		}
	}
	if (isset($userId) && $userId != '') {
		$_SESSION['user'] = $userId;
		/*$_SESSION['user_email'] = $email;
		$_SESSION['user_name'] = $data[0];*/

		// cart operations
		if (isset($_POST['isProduct']) && isset($_POST['qty']) && $_POST['qty'] != '') {
			$qty = $_POST['qty'];
			$product_id = $_POST['product_id'];
			$color_id = $_POST['color_id'];
			$price = $_POST['price'];
			$promoPrice = $_POST['promoPrice'];
			$promoId = $_POST['promoId'];

			$rs = mysqli_query($con, "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`) VALUES ('$userId', '$product_id', '$color_id', '$qty', '$price', '$promoPrice', '$promoId', '" . date('c') . "')");
		} else { $rs = true;}
		getTempCartToUserCart($_SESSION['user'], $con);
		if ($rs) {
			setMessage('Sign Up Process Successfully Completed.', 'alert alert-success');
			if (isset($_POST['isProduct'])) {redirect('shipping/');die();} else {echo '<script> history.back(); </script>';die();}
		} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	echo '<script> history.back(); </script>';die();
}

function userSignUpFromDetail($con) {
	// first chk valid email format
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$email = $_POST['email'];
		$type = $_POST['type'];
		// now chk if it exist or not
		$chk_email = mysqli_query($con, "SELECT user_id FROM tbl_user WHERE email = '$email'");
		if (mysqli_num_rows($chk_email)) {
			setMessage('Failed, This email address is already exist. Try another email address.', 'alert alert-error');
			echo '<script> history.back(); </script>';die();
		}
		// now get pass
		$pass = $_POST['pass'];

		// now insertion operation
		$data = explode('@', $email);
		$q = "INSERT INTO `tbl_user`(username, `email`, `password`, register_on) VALUES ('" . $data[0] . "', '$email', '" . md5($pass) . "', '" . date('c') . "')";
		if (exec_query($q, $con)) {
			// set sessions
			$userId = mysqli_insert_id($con);
			sendWelcomeMailOnSignUp($userId, $con);

			$_SESSION['user'] = $userId;
			$_SESSION['user_email'] = $email;
			$_SESSION['user_name'] = $data[0];

			// cart operations
			if (isset($_POST['isProduct'])) {
				$qty = $_POST['qty'];
				$product_id = $_POST['product_id'];
				$color_id = $_POST['color_id'];
				$price = $_POST['price'];
				$promoPrice = $_POST['promoPrice'];
				$promoId = $_POST['promoId'];

				$rs = mysqli_query($con, "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`) VALUES ('$userId', '$product_id', '$color_id', '$qty', '$price', '$promoPrice', '$promoId', '" . date('c') . "')");
			} else { $rs = true;}
			getTempCartToUserCart($_SESSION['user'], $con);
			if ($rs) {
				setMessage('Sign Up Process Successfully Completed.', 'alert alert-success');
				if (isset($_POST['isProduct'])) {redirect('cart/');die();} else {echo '<script> history.back(); </script>';die();}
			} else {setMessage('Error, Some error occured.', 'alert alert-error');}
		} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	} else {setMessage('Failed, Invalid email address.', 'alert alert-error');}
	echo '<script> history.back(); </script>';die();
}

function userQuickSignUp($con) {
	$param = false;
	$type = 'tbl_user';
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$email = $_POST['email'];
		if (!isset($_POST['data3'])) {$pass = rand(1000000000, 9999999999);} else { $pass = $_POST['pass'];}

		$chk_email = mysqli_query($con, "SELECT user_id FROM $type WHERE email = '$email'");
		if (mysqli_num_rows($chk_email)) {
			setMessage('Failed, This email address is already exist. Try another email address.', 'alert alert-error');
			if (isset($_POST['data3'])) {redirect(siteUrl);die();}
			redirect(siteUrl . 'userlogin/');die();
		}
	} else { $email = '';}

	if ($email != '') {
		/* if user req come from home popup, match the pass and confirm password fields  */
		if (isset($_POST['data3']) && $_POST['data3'] != '') {
			if ($_POST['cpass'] == $pass) {$param = true;} else {
				setMessage('Failed, Password and Confirm Password not match.', 'alert alert-error');
				redirect(siteUrl);die();
			}
		} else { $param = true;}

		if ($param) {
			$data = explode('@', $email);
			$q = "INSERT INTO `tbl_user`(username, `email`, `password`, register_on) VALUES ('" . $data[0] . "', '$email', '" . md5($pass) . "', '" . date('c') . "')";
			if (exec_query($q, $con)) {
				$userId = mysqli_insert_id($con);
				sendWelcomeMailOnSignUp($userId, $con); // send mail
				$_SESSION['user'] = $userId;
				$_SESSION['user_email'] = $email;
				$_SESSION['user_name'] = $data[0];
				getTempCartToUserCart($_SESSION['user'], $con);
				setMessage('Sign Up Process Successfully Completed.', 'alert alert-success');
				if (isset($_POST['data3'])) {redirect(siteUrl);die();}
				redirect('shipping/');die();
			} else {setMessage('Error, Some error occured.', 'alert alert-error');}
		} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	if (isset($_POST['data3'])) {redirect(siteUrl);die();}
	redirect('userlogin/');die();
}

function userSignUp($con) {
	$param = false;
	$type = 'tbl_user';
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$email = $_POST['email'];
		$pass = $_POST['pass'];
	} else { $email = '';}

	if ($email != '') {
		/* if user req come from home popup, match the pass and confirm password fields  */
		if (isset($_POST['data3']) && $_POST['data3'] != '') {
			if ($_POST['cpass'] == $pass) {
				$param = true;
			}
		} else { $param = true;}

		if ($param) {
			$data = explode('@', $email);
			$q = "INSERT INTO `tbl_user`(username, `email`, `password`, register_on) VALUES ('" . $data[0] . "', '$email', '" . md5($pass) . "', '" . date('c') . "')";
			if (exec_query($q, $con)) {
				$userId = mysqli_insert_id($con);
				sendWelcomeMailOnSignUp($userId, $con); // send mail
				$_SESSION['user'] = $userId;
				$_SESSION['user_email'] = $email;
				$_SESSION['user_name'] = $data[0];
				getTempCartToUserCart($_SESSION['user'], $con);
				setMessage('Sign Up Process Successfully Completed.', 'alert alert-success');
				redirect('dashboard/');die();
			} else {setMessage('Error, Some error occured.', 'alert alert-error');}
		} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	redirect('loginCheckout/');die();
}

function userRegistration($con) {
	$type = 'tbl_user';
	$uname = $_POST['uname'];
	$email = $_POST['email'];
	$pass = md5($_POST['pass']);
	$cpass = md5($_POST['cpass']);
	$chk_email = mysqli_query($con, "SELECT user_id FROM $type WHERE email = '$email'");
	$chk_uname = mysqli_query($con, "SELECT user_id FROM $type WHERE username = '$uname'");
	if (!isset($_POST['email']) || $_POST['email'] == '' || !isset($_POST['uname']) || $_POST['uname'] == '') {
		setMessage('Failed, Please provide valid email id and username.', 'alert alert-error');
		redirect('userregister/');die();
	} elseif (mysqli_num_rows($chk_email)) {
		setMessage('Failed, This email address is already exist. Try another email address.', 'alert alert-error');
		redirect('userregister/');die();
	} elseif (mysqli_num_rows($chk_uname)) {
		setMessage('Failed, This username address is already exist. Try another username.', 'alert alert-error');
		redirect('userregister/');die();
	} elseif ($pass != $cpass) {
		setMessage('Failed, Please match the password and confirm password.', 'alert alert-error');
		redirect('userregister/');die();
	}
	$title = $_POST['title'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phone = $_POST['phone'];
	$address = addslashes($_POST['address']);
	$city = $_POST['city'];
	$state = $_POST['state'];
	$country = $_POST['country'];
	$zip = $_POST['zip'];

	define('LIMIT_PRODUCT_WIDTH', true);
	define('MAX_PRODUCT_IMAGE_WIDTH', 450);
	define('THUMBNAIL_WIDTH', 150);
	$col = '';
	$colVal = '';
	if (isset($_FILES['img']) && $_FILES['img']['name'] != '' && !empty($_FILES['img'])) {
		$thumbimg = uploadProductImage('img', 'site_image/profile_pic/');
		$img = $thumbimg['image'];
		$thumb = $thumbimg['thumbnail'];
		$col = ', img';
		$colVal = ", '$thumb'";
	}
	$q = "INSERT INTO `tbl_user`(`title`, `last_name`, `first_name`, `email`, `username`, `password`, `phone_1`, `address_1`, `city`, `state`, `country_id`, `zip`, `register_on` $col) VALUES ('$title', '$lname', '$fname', '$email', '$uname', '$pass', '$phone', '$address', '$city', '$state', '$country', '$zip', '" . date('c') . "' $colVal)";
	if (exec_query($q, $con)) {
		$userId = mysqli_insert_id($con);
		sendWelcomeMailOnSignUp($userId, $con); // send mail
		getTempCartToUserCart($userId, $con);
		setMessage('Sign Up Process Successfully Completed.', 'alert alert-success');
		redirect(siteUrl);die();
	} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	redirect('userregister/');die();
}

/* send welcome mail, fetch template, edit it, and then send mail */
function sendWelcomeMailOnSignUp($user_id, $con) {
	$userData = getUser($user_id, $con);
	if (isset($userData->email)) {
		$rsEmail = exec_query("SELECT * FROM tbl_email_template WHERE type = 'registration'", $con);
		$rowEmail = mysqli_fetch_object($rsEmail);
		$content = $rowEmail->content;

		$contentHTML = html_entity_decode($content);
		$contentHTML = str_replace('{jhm :', '', $contentHTML); // replace all '{jhm : '
		$arraySearch = array(' email}', ' username}'); // isko replace krna h
		$arrayReplace = array($userData->email, $userData->username); // isse replace krna h
		$content = str_replace($arraySearch, $arrayReplace, $contentHTML); // yha milega sb
		$subject = 'Welcome on JHM';
		sendMail($subject, $content, array($userData->email));

	}
}

function userEditProfile($con) {
	$user_id = $_SESSION['user'];
	$uname = $_POST['uname'];
	$email = $_POST['email'];
	$title = $_POST['title'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phone = $_POST['phone'];
	$address = addslashes($_POST['address']);
	$city = $_POST['city'];
	$state = $_POST['state'];
	$country = $_POST['country'];
	$zip = $_POST['zip'];

	define('LIMIT_PRODUCT_WIDTH', true);
	define('MAX_PRODUCT_IMAGE_WIDTH', 450);
	define('THUMBNAIL_WIDTH', 150);
	$col = '';
	/*if(isset($_FILES['img']) && $_FILES['img']['name'] != '' && !empty($_FILES['img'])){
		$thumbimg = uploadProductImage('img', 'site_image/profile_pic/');
		$img = $thumbimg['image'];
		$thumb = $thumbimg['thumbnail'];
		$col = ", img = '$thumb'";
	}*/
	$q = "UPDATE `tbl_user` SET `title` = '$title', `last_name` = '$lname', `first_name` = '$fname', `email` = '$email', `username` = '$uname', `phone_1` = '$phone', `address_1` = '$address', `city` = '$city', `state` = '$state', `country_id` = '$country', `zip` = '$zip' $col WHERE user_id = '$user_id'";
	if (exec_query($q, $con)) {
		setMessage('Profile Successfully Edited.', 'alert alert-success');
	} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	redirect(siteUrl . 'usereditprofile/');die();
}

function userLogin($con) {
	$email = tres($_POST['email'], $con);
	$password = md5(tres($_POST['pass'], $con));
	$rs = exec_query("SELECT user_id, email, first_name FROM tbl_user WHERE (email = '$email' AND password = '$password') OR (username = '$email' AND password = '$password')", $con);
	if (mysqli_num_rows($rs) && mysqli_num_rows($rs) == 1) {
		$row = mysqli_fetch_object($rs);
		$_SESSION['user'] = $row->user_id;
		$_SESSION['user_email'] = $row->email;
		$data = explode('@', $row->email);
		$_SESSION['user_name'] = $data[0];
		/*$_SESSION['user_name'] = $row->first_name;*/
		setMessage("Welcome", 'alert alert-success');
		getTempCartToUserCart($_SESSION['user'], $con);
		redirect('cart.php');
	} else {
		setMessage('Login attempt failed.', 'alert alert-error');
		redirect('userlogin.php');
	}

	//echo '<script> history.back(); </script>';
	die();
}

function userchangepassword($con) {
	$user_id = $_SESSION['user'];
	$old_password = md5($_POST['old_password']);
	$new_password = md5($_POST['new_password']);
	$confirm_new_password = md5($_POST['confirm_new_password']);

	$rs = exec_query("SELECT password FROM tbl_user WHERE user_id = '$user_id'", $con);
	if (mysqli_num_rows($rs)) {
		$row = mysqli_fetch_object($rs);
		$old_db_password = $row->password;

		if ($old_db_password == $old_password) {
			if ($new_password == $confirm_new_password) {
				exec_query("UPDATE tbl_user set password = '$new_password' WHERE user_id = '$user_id'", $con);
				setMessage('Password successfully Changed.', 'alert alert-success');
			} else {setMessage('Error, New password not matching with confirm new password.', 'alert alert-error');}
		} else {setMessage('Error, Incorrect current password.', 'alert alert-error');}
	} else {setMessage('Failed, Some error occured. Try again later.', 'alert alert-error');}
	redirect(siteUrl . 'userchangepassword/');die();
}

function userProfilePic($con) {
	$user_id = $_SESSION['user'];
	define('LIMIT_PRODUCT_WIDTH', true);
	define('MAX_PRODUCT_IMAGE_WIDTH', 450);
	define('THUMBNAIL_WIDTH', 150);
	$col = '';
	$colVal = '';
	if (isset($_FILES['img']) && $_FILES['img']['name'] != '' && !empty($_FILES['img'])) {
		$thumbimg = uploadProductImage('img', 'site_image/profile_pic/');
		$img = $thumbimg['image'];
		$thumb = $thumbimg['thumbnail'];

		$q = "UPDATE `tbl_user` SET img = '$thumb' WHERE user_id = '$user_id'";
		if (exec_query($q, $con)) {
			setMessage('Profile Successfully Edited.', 'alert alert-success');
		} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	} else {setMessage('Error, Some error occured.', 'alert alert-error');}
	echo '<script> history.back(); </script>';
	die();
}

function addToCart($con) {
	if (isset($_POST['selectQty']) && $_POST['selectQty'] != '') {
		$qty = $_POST['selectQty'];
	} elseif (isset($_POST['inputQty']) && $_POST['inputQty'] != '') {
		$qty = $_POST['inputQty'];
	} else {
		setMessage("Some error occured in qty selection, Try Again", 'alert alert-error');
		echo '<script>history.back();</script>';die();
	}

	$backord = $_REQUEST['isbackord'];
	$product_id = $_POST['product_id'];
	$color_id = $_POST['color_id'];
	$price = $_POST['price'];
	$promoPrice = $_POST['promoPrice'];
	$promoId = $_POST['promoId'];

	/* if user is login */
	if (isset($_SESSION['user']) && $_SESSION['user'] != '') {$user_id = $_SESSION['user'];} else {
		/* if user is not login - cart will be manage in session, so include function related file */
		if (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {$user_id = $_SESSION['tempUser'];} else {
			$_SESSION['tempUser'] = rand(10000000, 99999999);
			$user_id = $_SESSION['tempUser'];}
	}

	$rs_chk = mysqli_query($con, "SELECT qty FROM tbl_cart WHERE product_id = '$product_id' AND user_id = '$user_id' AND `color_id` = '$color_id'");
	if (mysqli_num_rows($rs_chk)) {
		$rs = mysqli_query($con, "UPDATE tbl_cart SET `qty` = qty+$qty, product_price = '$price', product_promo_price = '$promoPrice', datetime = '" . date('c') . "' WHERE user_id = '$user_id' AND `product_id` = '$product_id' AND `color_id` = '$color_id'");
	} else {
//		echo "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`, `isbackOrd`) VALUES ('$user_id', '$product_id', '$color_id', '$qty', '$price', '$promoPrice', '$promoId', '".date('c')."', '$backord')";
		//
		//		die();
		$rs = mysqli_query($con, "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`, `isbackOrd`) VALUES ('$user_id', '$product_id', '$color_id', '$qty', '$price', '$promoPrice', '$promoId', '" . date('c') . "', '$backord')");
	}

	if (isset($rs)) {
		/*  if req come from wishlist, we move product in cart and delete from wishlist  */

		if (isset($_POST['redirect']) && $_POST['redirect'] != '') {redirect(siteUrl . 'checkout/');die();} // express checkout

		setMessage("Product added to cart Successfully", 'alert alert-success');
		if (isset($_POST['comeFromWishlist']) && $_POST['comeFromWishlist'] != '') {
			exec_query("DELETE FROM tbl_user_wishlist WHERE recid = " . $_POST['comeFromWishlist'], $con);
			redirect('cart/');die();
		} else {
			$_SESSION['openCartDialog'] = true;
			echo '<script> history.back(); </script>';die();
		}
	} else {
		setMessage("Some error Occured, Try Again", 'alert alert-error');
		redirect('product-category/');die();
	}
}

function expressCheckout($con) {
	if (isset($_POST['inputQty']) && $_POST['inputQty'] != '') {
		$qty = $_POST['inputQty'];
	} elseif (isset($_POST['selectQty']) && $_POST['selectQty'] != '') {
		$qty = $_POST['selectQty'];
	} else {
		setMessage("Some error occured in qty selection, Try Again", 'alert alert-error');
		echo '<script>history.back();</script>';die();
	}

	$product_id = $_POST['product_id'];
	$color_id = $_POST['color_id'];
	$price = $_POST['price'];
	$promoPrice = $_POST['promoPrice'];
	$promoId = $_POST['promoId'];

	/* if user is login */
	if (isset($_SESSION['user']) && $_SESSION['user'] != '') {$user_id = $_SESSION['user'];} else {
		/* if user is not login - cart will be manage in session, so include function related file */
		if (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {$user_id = $_SESSION['tempUser'];} else {
			$_SESSION['tempUser'] = rand(10000000, 99999999);
			$user_id = $_SESSION['tempUser'];}
	}

	//$delete = mysql_query("DELETE FROM tbl_cart WHERE user_id = '$user_id'", $con);

	$rs = mysqli_query($con, "INSERT INTO tbl_cart(`user_id`, `product_id`, `color_id`, `qty`, `product_price`, `product_promo_price`, `promo_id`, `datetime`) VALUES ('$user_id', '$product_id', '$color_id', '$qty', '$price', '$promoPrice', '$promoId', '" . date('c') . "')");
	if ($rs) {
		setMessage("Product Added to Cart,", 'alert alert-success');
		redirect('checkout/');die();
	} else {
		setMessage("Some error Occured, Try Again", 'alert alert-error');
		redirect('product-category/');die();
	}
}

function cartUpdate($con) {
	$cartId = $_POST['cartId'];
	$qty = $_POST['qty'];
	foreach ($qty AS $key => $value) {
		$rs = exec_query("UPDATE tbl_cart SET qty = '" . $qty[$key] . "' WHERE cart_id = '" . $cartId[$key] . "'", $con);
	}
	if (isset($rs)) {setMessage("Cart Successfully Updated", 'alert alert-success');} else {setMessage("Some error Occured, Try Again", 'alert alert-error');}
	redirect('cart/');die();
}

function applyPromo($con) {
	if (isset($_SESSION['user']) && $_SESSION['user'] != '') {$user_id = $_SESSION['user'];} elseif (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {$user_id = $_SESSION['tempUser'];} else {redirect(siteUrl);}

	$cdate = date('Y-m-d H:i:s');
	$promoCode = $_POST['promoCode'];
	$cartValue = $_POST['cartValue'];

	/* first chk that promo code is valid or not, and it is expire or valid in current period */
	$promoChkQ = "SELECT recid, percent_or_amount, promo_type, min_cart_value, promo_value FROM tbl_promo_code WHERE
	promo_code = '$promoCode' AND
	(DATE_FORMAT(start_date, '%Y-%m-%d %H:%i:%s') <= '$cdate' AND DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:%s') >= '$cdate')";
	$promoChkRs = exec_query($promoChkQ, $con);
	if (mysqli_num_rows($promoChkRs) > 0) {
		$row = mysqli_fetch_object($promoChkRs);
		if (isset($row)) {
			if ($cartValue >= $row->min_cart_value) {
				/* chk min cart value */
				if ($row->promo_type == 'allPro') {
					/* chk that if promo code for all product */
					/* update promo code details in cart table $promoArr['allPro'] = $row->recid.'|'.$row->percent_or_amount.'|'.$row->promo_value;*/
					/* first fetch cart product original price and update discount value */
					$cartProductQ = "SELECT cart_id ,product_price, product_promo_price, promo_id FROM tbl_cart WHERE user_id = $user_id";
					$cartProductRs = exec_query($cartProductQ, $con);
					if (mysqli_num_rows($cartProductRs) > 0) {
						while ($cartProRow = mysqli_fetch_object($cartProductRs)) {
							$proPrice = checkPromoValidity($cartProRow, $con); /* get pro price */
							$discount = ($row->percent_or_amount == 'percent') ? (($proPrice * $row->promo_value) / 100) : $row->promo_value;
							$discountPrice = $proPrice - $discount;
							exec_query("UPDATE tbl_cart SET promo_code_id = $row->recid, product_promo_code_price = $discountPrice WHERE cart_id = $cartProRow->cart_id", $con);
							setMessage("Promo Code Successfully Applied", 'alert alert-success');
						}

					}
				} else {
					/* if promo is for some pro, some brand, some cat */
					$cartProductQ = "SELECT cart.cart_id, cart.promo_id, cart.product_promo_price, cart.`product_id`, tp.brand_id, GROUP_CONCAT(DISTINCT tpcat.category_id) AS all_cat
					FROM `tbl_cart` cart
					LEFT JOIN tbl_product tp ON tp.product_id = cart.product_id
					LEFT JOIN tbl_product_category tpcat ON tpcat.product_id = tp.product_id
					WHERE cart.user_id = $user_id GROUP BY cart.product_id";
					$cartProductRs = exec_query($cartProductQ, $con);
					if (mysqli_num_rows($cartProductRs) > 0) {
						while ($cartProRow = mysqli_fetch_object($cartProductRs)) {
							$promoData = getPromoCodeForCartProduct($cartProRow->product_id, $cartProRow->brand_id, $cartProRow->all_cat, $con);
							if (!empty($promoData)) {
								$proPrice = checkPromoValidity($cartProRow, $con); /* get pro price */
								$discount = ($promoData[1] == 'percent') ? (($proPrice * $promoData[2]) / 100) : $promoData[2];
								$discountPrice = $proPrice - $discount;
								exec_query("UPDATE tbl_cart SET promo_code_id = '" . $promoData[0] . "', product_promo_code_price = $discountPrice WHERE cart_id = $cartProRow->cart_id", $con);
								setMessage("Promo Code Successfully Applied", 'alert alert-success');
							}
						}
					}
				}
			} else {setMessage("Your total cart value is insufficient.", 'alert alert-error');}
		} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	} else {setMessage("Error, Invalid Promo Code!!!", 'alert alert-error');}
	if (isset($_POST['redirect']) && $_POST['redirect'] != '') {
		$arr = explode('.', $_POST['redirect']);
		$page = $arr[0] . '/';
	} else { $page = 'cart/';}
	redirect($page);die();
}

function shipping_detail($con) {
	chkParam($_SESSION['user'], siteUrl);
	$user_id = $_SESSION['user'];
	$fname = tres($_POST['fname'], $con);
	$lname = tres($_POST['lname'], $con);
	$phone = tres($_POST['phone'], $con);
	$address = tres($_POST['aaddress1'], $con);
	$addType = tres($_POST['addType'], $con);
	$city = tres($_POST['locality'], $con);
	$state = tres($_POST['administrative_area_level_1'], $con);
	$pin = tres($_POST['postal_code'], $con);
	$locality = tres($_POST['route1'], $con);
	$aphone = (isset($_POST['aphone'])) ? tres($_POST['aphone'], $con) : '';
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];

	$order_q = "INSERT INTO `tbl_order` (`od_date`, `payment_status`, `od_shipping_first_name`, `od_shipping_last_name`, `od_shipping_locality`, `od_shipping_address`, `od_shipping_address_type`, `od_shipping_phone`, `od_shipping_alt_phone`, `od_shipping_city`, `od_shipping_state`, `od_shipping_postal_code`, od_shipping_lat, od_shipping_lng, `od_shipping_cost`, `amount`, `user_id`)
	VALUES ('" . date('c') . "', 'Unpaid', '$fname', '$lname', '$locality', '$address', '$addType', '$phone', '$aphone', '$city', '$state', '$pin', '$lat', '$lng', '0.00', '', '$user_id')";
	if (exec_query($order_q, $con)) {
		$oId = mysqli_insert_id($con);

		$cartQuery = "SELECT tbl_cart.* FROM tbl_cart WHERE tbl_cart.user_id = " . $user_id;
		$pro_rs = exec_query($cartQuery, $con);
		$numCart = mysqli_num_rows($pro_rs);
		if ($numCart > 0) {
			$grandTotalOriginalPrice = 0;
			$promotionDiscount = 0;
			$promoCodeDiscount = 0;
			$promoCodeChk = 'no';
			$amGst = 0;
			$sumOfCart = getCartValue($user_id, $con);
			while ($pro_row = mysqli_fetch_object($pro_rs)) {
				include 'include/promotionCalc.php';
				$promoPrice = 0;
				$promoid = 0;
				if ($isPromotion) {
					$promoPrice = $pro_row->product_promo_price;
					$promoid = $pro_row->promo_id;
				}

				$promoCodePrice = 0;
				$promoCodeid = 0;
				if ($isPromoCode) {
					$promoCodePrice = $pro_row->product_promo_code_price;
					$promoCodeid = $pro_row->promo_code_id;
				}

//				$oi_q = "INSERT INTO `tbl_order_item` (`order_id`, `product_id`, `color_id`, `od_qty`, `product_price`, `product_promo_price`, `promo_id`, `product_promo_code_price`, `promo_code_id`)
				//				VALUES ('$oId', '$pro_row->product_id', '$pro_row->color_id', '$pro_row->qty', '$pro_row->product_price', '$promoPrice', '$promoid', '$promoCodePrice', '$promoCodeid')";
				//				$oi_rs = exec_query($oi_q, $con);
				$oi_q = "INSERT INTO `tbl_order_item` (`order_id`, `product_id`, `color_id`, `od_qty`, `product_price`, `product_promo_price`, `promo_id`, `product_promo_code_price`, `promo_code_id`)
				VALUES ('$oId', '$pro_row->product_id', '$pro_row->color_id', '$pro_row->qty', '$pro_row->product_price', '$pro_row->product_promo_price', '$promoid', '$promoCodePrice', '$promoCodeid')";
				$oi_rs = exec_query($oi_q, $con);
			}
			if ($oi_rs) {
				echo $_SESSION['order_id'] = $oId;
				echo $grandPlusPromotion = $grandTotalOriginalPrice - $promotionDiscount;
				$delCharge = 0;
				$gst = 15;
				echo $amGst = round(($grandPlusPromotion) * 0.15, 2);
				echo $amDel = round(($grandPlusPromotion * $delCharge) / 100, 2);
				echo $finalAmount = round(($grandPlusPromotion + $amGst + $amDel) - $promoCodeDiscount, 2);
				if ($finalAmount > 0) {exec_query("UPDATE tbl_order SET amount = '$finalAmount' WHERE order_id = '$oId'", $con);}
				setMessage("Shipping Details Successfully Saved.", 'alert alert-success');

				//redirect(siteUrl.'paymentProcess/'); die();
			} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
		} else {setMessage("Error, Your Cart is Empty.", 'alert alert-error');}
	} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	redirect(siteUrl . 'cart/');
	die();
}

function processPayment($con) {
	$user_id = $_SESSION['user'];
	// delete cart product
	mysqli_query($con, "DELETE FROM tbl_cart WHERE user_id = '$user_id'");

	$name = $_POST['fname'];
	$cardNum = $_REQUEST["card_no"];
	$ExMnth = $_REQUEST["month"];
	$ExYear = $_REQUEST["year"];
	$ex = $ExMnth . $ExYear;
	$cvv = $_POST['cvv'];
	$MerchRef = '';
	if (isset($_SESSION['order_id']) && $_SESSION['order_id'] != '' && isset($_POST['fname']) && $_POST['fname'] != '' && isset($_POST['card_no']) && $_POST['card_no'] != '' && isset($_POST['month']) && $_POST['month'] != '' && isset($_POST['year']) && $_POST['year'] != '' && isset($_POST['cvv']) && $_POST['cvv'] != '') {

		$orderId = $_SESSION['order_id'];
		$rs1 = mysqli_query($con, "SELECT * FROM tbl_order WHERE order_id = '$orderId'");
		$row1 = mysqli_fetch_object($rs1);
		if (mysqli_num_rows($rs1) && isset($row1->amount) && $row1->amount > 0) {
			$amount = $row1->amount;
			include 'include/functionPayment.php';
			process_request($name, $cardNum, $ex, $cvv, $orderId, $amount);
		} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	redirect(siteUrl);die();
}

// new order function
function checkOutNow($con) {
	// first take user id if user login, then set $user_id,
	if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
		$chkOutDb = 'login';
		$user_id = $_SESSION['user'];
	} else {
		// if not login chk, what user select, guest checkout, register, or want to login
		if (isset($_POST['isLogin']) && $_POST['isLogin'] != '' && $_POST['bill_email'] != '') {
			$checkout_method = $_POST['isLogin'];

			$email = $_POST['bill_email'];
			$data = explode('@', $email);
			$chk_email = mysqli_query($con, "SELECT user_id, user_type FROM tbl_user WHERE email = '$email'");
			if (mysqli_num_rows($chk_email)) {
				$row = mysqli_fetch_object($chk_email);
				$user_id = $row->user_id;
				$user_type_db = $row->user_type;
				$chkOutDb = 'login';
			} else {
				if ($checkout_method == 'guest') {
					$chkOutDb = 'guest';
					$user_type_db = 2;
					$pass = '';
					$q = "INSERT INTO `tbl_user`(username, password, `email`, register_on, user_type) VALUES ('" . $data[0] . "', '$pass', '$email', '" . date('c') . "', '$user_type_db')";
				} elseif ($checkout_method == 'register' && $_POST['bill_password'] != '') {
					$chkOutDb = 'register';
					$user_type_db = 1;
					$pass = md5($_POST['bill_password']);
					$q = "INSERT INTO `tbl_user`(username, password, `email`, register_on, user_type) VALUES ('" . $data[0] . "', '$pass', '$email', '" . date('c') . "', '$user_type_db')";
				}

				if (exec_query($q, $con)) {
					$user_id = mysqli_insert_id($con);
				}
			}
			if (isset($user_id) && $user_id != '') {
				$_SESSION['user'] = $user_id;
				if ($checkout_method == 'register') {
					$_SESSION['user_email'] = $email;
					$_SESSION['user_name'] = $data[0];
				}
				getTempCartToUserCart($_SESSION['user'], $con);
			} else {
				setMessage('Failed, Some error occured.', 'alert alert-error');
				echo '<script> history.back(); </script>';die();
			}
		} else {
			setMessage("Please choose a checkout method !", 'alert alert-error');
			echo '<script> history.back(); </script>';die();
		}
	}

	// we got user id, now next step, get billing info /////////////////////////////////////////////////////////////
	$billingInformation = false;
	$bill_first_name = $_POST['bill_first_name'];
	$bill_last_name = $_POST['bill_last_name'];
	$bill_phone = $_POST['bill_phone'];
	$billAddress = $_POST['billAddress'];
	$bill_postal_code = $_POST['bill_postal_code'];
	$bill_lat = $_POST['bill_lat'];
	$bill_lng = $_POST['bill_lng'];
	$bill_locality = $_POST['bill_locality'];
	$bill_city = $_POST['bill_city'];
	$bill_alt_phone = '';
	if (isset($_POST['bill_alt_phone'])) {$bill_alt_phone = $_POST['bill_alt_phone'];}

	if ($bill_first_name != '' && $bill_last_name != '' && $bill_phone != '' && $billAddress != '' && $bill_postal_code != '' && $bill_locality != '' && $bill_city != '') {
		// if all is ok, then set true
		$billingInformation = true;
	} else {
		setMessage("Something is missing in billing information !", 'alert alert-error');
		echo '<script> history.back(); </script>';die();
	}

	// now next step, get shipping info /////////////////////////////////////////////////////////////
	$shippingInformation = false;
	$ship_first_name = $_POST['ship_first_name'];
	$ship_last_name = $_POST['ship_last_name'];
	$ship_phone = $_POST['ship_phone'];
	$shipAddress = $_POST['shipAddress'];
	$ship_postal_code = $_POST['ship_postal_code'];
	$ship_lat = $_POST['ship_lat'];
	$ship_lng = $_POST['ship_lng'];
	$ship_locality = $_POST['ship_locality'];
	$ship_city = $_POST['ship_city'];
	$ship_alt_phone = '';
	if (isset($_POST['ship_alt_phone'])) {$ship_alt_phone = $_POST['ship_alt_phone'];}

	if ($ship_first_name != '' && $ship_last_name != '' && $ship_phone != '' && $shipAddress != '' && $ship_postal_code != '' && $ship_locality != '' && $ship_city != '') {
		// if all is ok, then set true
		$shippingInformation = true;
	} else {
		setMessage("Something is missing in shiping information !", 'alert alert-error');
		echo '<script> history.back(); </script>';die();
	}

	// get shipping charges
	$shippingCharge = 0;
	$validShipChargeStatus = false;
	$postcode1 = ltrim($ship_postal_code, '0');
	$chkCity = mysqli_query($con, "SELECT sector_code, is_rural FROM tbl_shipping_sector WHERE postcode = '$ship_postal_code' OR postcode = '$postcode1'");
	if (mysqli_num_rows($chkCity)) {
		$rowCity = mysqli_fetch_object($chkCity);
		if (isset($rowCity->sector_code) && $rowCity->sector_code != '') {
			$sector = $rowCity->sector_code;
			$chkShip = mysqli_query($con, "SELECT price FROM tbl_shipping_price WHERE sector_code = '$sector'");
			if ($chkShip) {
				$rowPrice = mysqli_fetch_object($chkShip);
				if (isset($rowPrice->price) && $rowPrice->price != '') {
					$shippingCharge = $rowPrice->price;
					$validShipChargeStatus = true;
				}
			}
		}
	}

	// reward point
	$isPoint = false;
	$userPoint = 0;
	$userPointDeduct = 0;
	if (isset($_POST['pointInput']) && $_POST['pointInput'] != '') {
		$userPoint = $_POST['pointInput'];
		$point = getUserPoint($user_id, $con); //user total point
		if ($userPoint > 0 && $userPoint <= $point) {
			$userPointDeduct = $userPoint;
			$isPoint = true;
		}
	}

	// now chk if bill n shipping status true , process, or redirect
	if (($shippingInformation) && ($billingInformation) && ($validShipChargeStatus) && isset($shippingCharge) && isset($_POST['shippingType']) && $_POST['shippingType'] != '') {
		//$payType = $_POST['paymentType'];
		$shipType = $_POST['shippingType'];

		if ($shipType == 'Saturday') {$shippingCharge = $shippingCharge + 4;} elseif ($shipType == 'Rural') {$shippingCharge = $shippingCharge + 4;} elseif ($shipType == 'Overnight') {$shippingCharge = $shippingCharge + 20;}

		$orderInformation = false;
		$boCheck = mysqli_query($con, "SELECT count(isbackOrd) as backCount FROM tbl_cart WHERE isbackOrd = 1 and user_id = '" . $user_id . "'");
		$isbackorder = mysqli_fetch_object($boCheck)->backCount;
		$orderQ = "INSERT INTO `tbl_order` (
			`od_date`, `payment_status`, `od_shipping_first_name`, `od_shipping_last_name`,
			`od_shipping_locality`, `od_shipping_address`, `od_shipping_phone`, `od_shipping_alt_phone`,
			`od_shipping_city`, `od_shipping_postal_code`, `od_shipping_lat`, `od_shipping_lng`,
			`od_billing_first_name`, `od_billing_last_name`, `od_billing_locality`, `od_billing_address`,
			`od_billing_phone`, `od_billing_alt_phone`, `od_billing_city`, `od_billing_postal_code`,
			`od_billing_lat`, `od_billing_lng`, `od_shipping_cost`, `od_shipping_type`,
			`user_id`, od_checkout_type, payment_type,
			od_point_spend, od_point_deduct
		)
		VALUES (
			'" . date('c') . "', 'Unpaid', '$ship_first_name', '$ship_last_name',
			'$ship_locality', '$shipAddress', '$ship_phone', '$ship_alt_phone',
			'$ship_city', '$ship_postal_code', '$ship_lat', '$ship_lng',
			'$bill_first_name', '$bill_last_name', '$bill_locality', '$billAddress',
			'$bill_phone', '$bill_alt_phone', '$bill_city', '$bill_postal_code',
			'$bill_lat', '$bill_lng', '$shippingCharge', '$shipType',
			'$user_id',	'$chkOutDb', 'Credit Card',
			$userPoint, $userPointDeduct
		)";
		$backOid = 0;
		$BackOrderQ = '';
		if ($isbackorder != 0) {
			$BackOrderQ = mysqli_query($con, "INSERT INTO `tbl_order` (
				`od_date`, `payment_status`, `od_shipping_first_name`, `od_shipping_last_name`,
				`od_shipping_locality`, `od_shipping_address`, `od_shipping_phone`, `od_shipping_alt_phone`,
				`od_shipping_city`, `od_shipping_postal_code`, `od_shipping_lat`, `od_shipping_lng`,
				`od_billing_first_name`, `od_billing_last_name`, `od_billing_locality`, `od_billing_address`,
				`od_billing_phone`, `od_billing_alt_phone`, `od_billing_city`, `od_billing_postal_code`,
				`od_billing_lat`, `od_billing_lng`, `od_shipping_cost`, `od_shipping_type`,
				`user_id`, od_checkout_type, payment_type,
				od_point_spend, od_point_deduct, isbackOrd
			)
			VALUES (
				'" . date('c') . "', 'Unpaid', '$ship_first_name', '$ship_last_name',
				'$ship_locality', '$shipAddress', '$ship_phone', '$ship_alt_phone',
				'$ship_city', '$ship_postal_code', '$ship_lat', '$ship_lng',
				'$bill_first_name', '$bill_last_name', '$bill_locality', '$billAddress',
				'$bill_phone', '$bill_alt_phone', '$bill_city', '$bill_postal_code',
				'$bill_lat', '$bill_lng', '0', '$shipType',
				'$user_id',	'$chkOutDb', 'Credit Card',
				$userPoint, $userPointDeduct, 1
			)"); //backorder cost 0 for shipping
			$backOid = mysqli_insert_id($con);
		}
		if (exec_query($orderQ, $con)) {
			$oId = mysqli_insert_id($con);
			$cartQuery = "SELECT tbl_cart.* FROM tbl_cart WHERE user_id = '" . $user_id . "'";
			$pro_rs = exec_query($cartQuery, $con);
			$numCart = mysqli_num_rows($pro_rs);
			if ($numCart > 0) {
				$grandTotalOriginalPrice = 0;
				$promotionDiscount = 0;
				$promoCodeDiscount = 0;
				$promoCodeChk = 'no';
				$amGst = 0;
				$sumOfCart = getCartValue($user_id, $con);
				//taslim//
				$subTotalAllProducts = 0;
				$isPromotion = '';
				$isPromoCode = '';
				$priceAfterDiscount = 0;
				//taslim//
				while ($pro_row = mysqli_fetch_object($pro_rs)) {
					include 'include/promotionCalc.php';
					$promoType = '';
					$promoval = '';
					$promoCodeType = '';
					$promoCodeval = '';

					$promoPrice = 0;
					$promoid = 0;
					if ($isPromotion) {
						$promoPrice = $pro_row->product_promo_price;
						$promoid = $pro_row->promo_id;
						$promoType = $promotion['percent_or_amount'];
						$promoval = $promotion['promo_value'];
					}

					$promoCodePrice = 0;
					$promoCodeid = 0;
					if ($isPromoCode) {
						$promoCodePrice = $pro_row->product_promo_code_price;
						$promoCodeid = $pro_row->promo_code_id;
						$promoCodeType = $promoCodeData[1];
						$promoCodeval = $promoCodeData[2];
					}

					if (($pro_row->isbackOrd) == 1) {
						$oi_qbc = "INSERT INTO `tbl_order_item` (`order_id`, `product_id`, `color_id`, `od_qty`, `product_price`, `product_promo_price`, `promo_id`, product_promo_type, product_promo_value, `product_promo_code_price`, `promo_code_id`, product_promo_code_type, product_promo_code_value, isbackOrd)
					VALUES ('$backOid', '$pro_row->product_id', '$pro_row->color_id', '$pro_row->qty', '$pro_row->product_price', '$pro_row->product_promo_price', '$promoid', '$promoType', '$promoval', '$promoCodePrice', '$promoCodeid', '$promoCodeType', '$promoCodeval', '" . $pro_row->isbackOrd . "')";
						$oi_rs = exec_query($oi_qbc, $con);
					} else {

						$oi_q = "INSERT INTO `tbl_order_item` (`order_id`, `product_id`, `color_id`, `od_qty`, `product_price`, `product_promo_price`, `promo_id`, product_promo_type, product_promo_value, `product_promo_code_price`, `promo_code_id`, product_promo_code_type, product_promo_code_value, isbackOrd)
					VALUES ('$oId', '$pro_row->product_id', '$pro_row->color_id', '$pro_row->qty', '$pro_row->product_price', '$pro_row->product_promo_price', '$promoid', '$promoType', '$promoval', '$promoCodePrice', '$promoCodeid', '$promoCodeType', '$promoCodeval', '" . $pro_row->isbackOrd . "')";
						$oi_rs = exec_query($oi_q, $con);
					}
				}
				if ($oi_rs) {
					$_SESSION['order_id'] = $oId;
//					$grandPlusPromotion = $grandTotalOriginalPrice - $promotionDiscount;
					$grandPlusPromotion = $priceAfterDiscount;
					$amGst = $priceAfterDiscount * .15;
					$amDel = $shippingCharge;
					$finalAmount = $grandPlusPromotion + $amDel;

					if ($finalAmount > 0) {
						if (exec_query("UPDATE tbl_order SET amount = '$finalAmount' WHERE order_id = '$oId'", $con)) {
							$orderInformation = true;
						} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
					} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
				} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
			} else {setMessage("Error, Your Cart is Empty.", 'alert alert-error');}
		} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	} else {setMessage('Failed, Some error occured.', 'alert alert-error');}

	// now chk if everything is alright
	if (isset($orderInformation) && $orderInformation && isset($_SESSION['order_id']) && $_SESSION['order_id'] != '' && $_SESSION['order_id'] != 0) {
		// get order details
		$orderId = $_SESSION['order_id'];
		$rs1 = mysqli_query($con, "SELECT * FROM tbl_order WHERE order_id = '$orderId'");
		$row1 = mysqli_fetch_object($rs1);

		// die();
		//if($_POST['paymentType'] == 'credit_card'){
		//    $name = $_POST['fname'];
		//    $cardNum = $_REQUEST["card_no"];
		//    $ExMnth = $_REQUEST["month"];
		//    $ExYear = $_REQUEST["year"];
		//    $ex = $ExMnth.$ExYear;
		//    $cvv = $_POST['cvv'];
		//    $MerchRef = '';
		//if(isset($_POST['fname']) && $_POST['fname'] != '' && isset($_POST['card_no']) && $_POST['card_no'] != '' && isset($_POST['month']) && $_POST['month'] != '' && isset($_POST['year']) && $_POST['year'] != '' && isset($_POST['cvv']) && $_POST['cvv'] != ''){

		if (mysqli_num_rows($rs1) && isset($row1->amount) && $row1->amount > 0) {
			//$amount = ($row1->amount + $row1->od_shipping_cost - $row1->od_point_deduct);
			$amount = round($finalAmount, 2);
			$_REQUEST['orderId'] = $orderId; //set the global vars
			$_REQUEST['totalAmt'] = $amount;
			$_REQUEST['backordId'] = $backOid;
			include 'include/PXPayJHM.php';
			//process_request($orderId, $amount);
			//        if (!$paymentResult)
			//        {
			//            setMessage("Payment failed, please try again.", 'alert alert-error');
			//            redirect(siteUrl.'failure.php'); die();
			//        }
			////successful payment, deduct stock from order/backorder,update qty in product price
			//        $ordUpdate = mysql_query("UPDATE tbl_product_price tpp INNER JOIN `tbl_order_item` toi ON toi.product_id = tpp.product_id AND toi.color_id = tpp.color_id SET qty = qty - toi.od_qty WHERE order_id = '$orderId'");
			//        if(isset($backOid) && $backOid > 0){
			//            $boUpdate = mysql_query("UPDATE tbl_product_price tpp INNER JOIN `tbl_order_item` toi ON toi.product_id = tpp.product_id AND toi.color_id = tpp.color_id SET backorder_qty = backorder_qty - toi.od_qty WHERE order_id = '$backOid'");
			//        };
			//        // delete cart product at last
			//        $delCart = mysql_query("DELETE FROM tbl_cart WHERE user_id = '".$user_id."'");
			//        redirect(siteUrl.'success/orderId4xip'.$orderId); die(); //now return to order success page and finish
			//}
			//else{ setMessage("Some error Occured, Try Again.", 'alert alert-error'); }
			//}
			//else{ setMessage("Some error Occured, Try Again.", 'alert alert-error'); }
		}
		// paypal is selected
		elseif ($_POST['paymentType'] == 'paypal') {
			if (mysqli_num_rows($rs1) && isset($row1->amount) && $row1->amount > 0) {
				//$amount = ($row1->amount + $row1->od_shipping_cost - $row1->od_point_deduct);
				$amount = $finalAmount;
				?>
                <form id="payp" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="accounts@jhm.co.nz">
                    <input type="hidden" name="lc" value="IN">
                    <input type="hidden" name="item_name" value="JHM Order Checkout">
                    <input type="hidden" name="item_number" value="Item Id">
                    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="button_subtype" value="services">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="return" value="<?php echo siteUrl; ?>paypalSucc/<?php echo $orderId; ?>/">
                    <input type="hidden" name="cancel_return" value="<?php echo siteUrl; ?>paypalFail/<?php echo $orderId; ?>/">

                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
                    <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal ??The safer, easier way to pay online.">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                </form>
            	<script> document.getElementById('payp').submit(); </script>

			<?php die();
			} else {setMessage('Failed, Some error occured.', 'alert alert-error');}
		} else {setMessage('Failed, Some error occured.', 'alert alert-error');}
	} else {setMessage('Failed, Some error occured.', 'alert alert-error');}
	//echo '<script> history.back(); </script>'; die();
}

function orderCancelRequest($con) {
	if (!isset($_SESSION['user']) || $_SESSION['user'] == '' || empty($_SESSION['user'])) {redirect(siteUrl);die();}
	if (!isset($_POST['data1']) || $_POST['data1'] == '' || empty($_POST['data1'])) {redirect('userorder/');die();}
	$user_id = $_SESSION['user'];
	$orderData = $_POST['data1'];
	$odId = substr($orderData, 7);
	$reason = addslashes($_POST['reason']);
	$exp = addslashes($_POST['exp']);
	$rs = exec_query("INSERT INTO `tbl_order_cancel` (`order_id`, `reason`, `description`, `datetime`) VALUES ('$odId', '$reason', '$exp', '" . date('c') . "')", $con);
	if ($rs) {
		exec_query("UPDATE `tbl_order` set status = '3' WHERE `order_id` = '$odId'", $con);
		setMessage("Return Request Successfully Sent.", 'alert alert-success');
	} else {
		setMessage("Some error Occured, Try Again.", 'alert alert-error');
	}
	redirect(siteUrl . 'userorder/');die();
}

function newsletterSignUp($con) {
	$email = $_POST['email'];
	$chk_rs = exec_query("SELECT recid FROM tbl_user_newsletter WHERE email = '$email'", $con);
	if (mysqli_num_rows($chk_rs) > 0) {
		setMessage("Thank you for subscribe with us, we'll keep you updated with our hottest deals.", 'alert alert-success');
	} else {
		$rs = exec_query("INSERT INTO tbl_user_newsletter(email) VALUES('$email')", $con);
		if ($rs) {setMessage("Thank you for subscribe with us, we'll keep you updated with our hottest deals.", 'alert alert-success');} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	}
	redirect(siteUrl);die();
}

function productRating($con) {
	if (!isset($_POST['rating']) || $_POST['rating'] == 0) {echo '<script> history.back(); </script>';die();}
	$user_id = $_SESSION['user'];
	$pid = $_POST['pid'];
	$title = addslashes($_POST['title']);
	$desc = addslashes($_POST['desc']);
	$rating = $_POST['rating'];

	$rs = exec_query("INSERT INTO tbl_review(user_id, product_id, rating, title, review, datetime) VALUES('$user_id', '$pid', '$rating', '$title', '$desc', '" . date('c') . "')", $con);
	if ($rs) {setMessage("Your Review successfully saved.", 'alert alert-success');} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	echo '<script> history.back(); </script>';die();
}

function askAQuestion($con) {
	$user_id = '';
	if (isset($_SESSION['user']) && $_SESSION['user'] != '') {$user_id = $_SESSION['user'];}
	$email = $_POST['email'];
	$title = addslashes($_POST['title']);
	$desc = addslashes($_POST['desc']);
	$type = $_POST['type'];

	$rs = exec_query("INSERT INTO `tbl_question` (`uid`, `email`, `type`, `title`, `description`, `datetime`) VALUES ('$user_id', '$email', '$type', '$title', '$desc', '" . date('c') . "')", $con);
	if ($rs) {setMessage("Your question successfully submitted.", 'alert alert-success');} else {setMessage("Some error Occured, Try Again.", 'alert alert-error');}
	echo '<script> history.back(); </script>';die();
}

function uploadProductImage($inputName, $uploadDir) {
	/* img ////////////// function //////////////////////// start /////////////////////////// */
	$image = $_FILES[$inputName];
	$imagePath = '';
	$thumbnailPath = '';
	if (trim($image['tmp_name']) != '') {
		$ext = substr(strrchr($image['name'], "."), 1); //$extensions[$image['type']];
		// generate a random new file name to avoid name conflict
		$imagePath = md5(rand() * time()) . ".$ext";
		list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);
		// make sure the image width does not exceed the
		// maximum allowed width
		if (LIMIT_PRODUCT_WIDTH && $width > MAX_PRODUCT_IMAGE_WIDTH) {
			$result = createThumbnail($image['tmp_name'], $uploadDir . $imagePath, MAX_PRODUCT_IMAGE_WIDTH);
			$imagePath = $result;
		} else { $result = move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath);}
		if ($result) {
			// create thumbnail
			$thumbnailPath = md5(rand() * time()) . ".$ext";
			$result = createThumbnail($uploadDir . $imagePath, $uploadDir . $thumbnailPath, THUMBNAIL_WIDTH);

			// create thumbnail failed, delete the image
			if (!$result) {
				unlink($uploadDir . $imagePath);
				$imagePath = $thumbnailPath = '';
			} else { $thumbnailPath = $result;}
		} else {
			// the product cannot be upload / resized
			$imagePath = $thumbnailPath = '';
		}
	}
	return array('image' => $imagePath, 'thumbnail' => $thumbnailPath);
}

function createThumbnail($srcFile, $destFile, $width, $quality = 75) {
	$thumbnail = '';
	if (file_exists($srcFile) && isset($destFile)) {
		$size = getimagesize($srcFile);
		$w = number_format($width, 0, ',', '');
		$h = number_format(($size[1] / $size[0]) * $width, 0, ',', '');
		$thumbnail = copyImage($srcFile, $destFile, $w, $h, $quality);
	} // return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}

/* Copy an image to a destination file. The destination image size will be $w X $h pixels */
function copyImage($srcFile, $destFile, $w, $h, $quality = 75) {
	$tmpSrc = pathinfo(strtolower($srcFile));
	$tmpDest = pathinfo(strtolower($destFile));
	$size = getimagesize($srcFile);
	if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg" || $tmpDest['extension'] == "jpeg") {
		$destFile = substr_replace($destFile, 'jpg', -3);
		$dest = imagecreatetruecolor($w, $h);
		imageantialias($dest, TRUE);
	} elseif ($tmpDest['extension'] == "png") {
		$dest = imagecreatetruecolor($w, $h);
		imageantialias($dest, TRUE);
	} else {return false;}
	switch ($size[2]) {
	//GIF
	case 1:$src = imagecreatefromgif($srcFile);
		break;
	//JPEG
	case 2:$src = imagecreatefromjpeg($srcFile);
		break;
	//PNG
	case 3:$src = imagecreatefrompng($srcFile);
		break;
	default:return false;
		break;
	}
	imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
	switch ($size[2]) {
	case 1:
	case 2:imagejpeg($dest, $destFile, $quality);
		break;
	case 3:imagepng($dest, $destFile);
	}return $destFile;
}
function getTempCartToUserCart($uid, $con) {
	if (isset($_SESSION['tempUser']) && $_SESSION['tempUser'] != '') {
		$tempUser = $_SESSION['tempUser'];
		$allRs = exec_query("SELECT * FROM tbl_cart WHERE user_id = $tempUser", $con);
		while ($row = mysqli_fetch_object($allRs)) {
			$rs_chk = mysqli_query($con, "SELECT qty FROM tbl_cart WHERE product_id = '$row->product_id' AND user_id = '$uid' AND `color_id` = '$row->color_id'");
			if (!mysqli_num_rows($rs_chk)) {
				$upDate = exec_query("UPDATE tbl_cart SET user_id = $uid WHERE cart_id = $row->cart_id ", $con);
			} else {
				$upDate = exec_query("DELETE FROM tbl_cart WHERE cart_id = $row->cart_id ", $con);
			}
		}
		unset($_SESSION['tempUser']);
	}
}

function ContactUs(){
	$fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $type = $_POST['qtype'];
    $desc = $_POST['desc'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {	 
	 	setMessage('Invalid email address.', 'alert alert-error');
	 	redirect(siteUrl);
	 	die();
    }

    $subject = 'User Enquiry from JHM Website';
    $content = '<html>
	<head>
		<style>table tr td{ background:#ececec; padding: 7px; }</style>
	</head>
	<body>
	<table cellpadding="5" cellspacing="5">
		<tr>
			<td colspan="2">User Enquiry</td>
		</tr>
		<tr>
			<td><b>First Name</b></td>
			<td>' . $fname . '</td>
		</tr>
		<tr>
			<td><b>Last Name</b></td>
			<td>' . $lname . '</td>
		</tr>
		<tr>
			<td><b>Email Address</b></td>
			<td>' . $email . '</td>
		</tr>
		<tr>
			<td><b>Query Type</b></td>
			<td>' . $type . '</td>
		</tr>
		<tr>
			<td><b>Query</b></td>
			<td>' . $desc . '</td>
		</tr>
	</table>
	</body>
	</body>';
    sendMail($subject, $content, array('info@jhm.co.nz'));
  	setMessage('Thanks for contacting us. We will get back to you soon.', 'alert alert-success');
	redirect(siteUrl);die();
}
?>