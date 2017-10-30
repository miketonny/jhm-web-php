<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("include/config.php");
include("include/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Discover the latest Japanese beauty products at JHM. Explore our unrivaled selection of makeup, skin care, fragrance and more from classic and emerging brands.">
<meta name="author" content="">
<title>:: <?php echo siteName; ?> ::</title>
<link href="<?php echo siteUrl; ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo siteUrl; ?>css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo siteUrl; ?>css/bootstrap-select.css">
<link href="<?php echo siteUrl; ?>/css/animate.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600' rel='stylesheet' type='text/css'>
<link href="<?php echo siteUrl; ?>css/main.css" rel="stylesheet">
<link href="<?php echo siteUrl; ?>css/other-page-style.css" rel="stylesheet">
<link href="<?php echo siteUrl; ?>css/responsive.css" rel="stylesheet">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-92909202-2', 'auto');
  ga('send', 'pageview');

</script>
<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
<?php getIcon(); ?>
</head>
<!--/head-->
<body>