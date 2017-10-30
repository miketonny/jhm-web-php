<?php session_start();
include("include/config.php");
include 'include/functions.php';
session_unset();
session_destroy();
redirect(siteUrl); ?>