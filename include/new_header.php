<script src="<?php echo siteUrl; ?>js/new/jquery.js"></script>
<style>
    .suggest_link_over:hover{ color:#D93A40 !important; text-decoration:underline; background:#ececec; }
    .signupType{ display:none !important; }
    input#headerSearch:required { box-shadow:none; }
    input#headerSearch:invalid { box-shadow:none; }
    #ui-id-1{ height:auto !important; max-height:350px !important; overflow:auto; }
</style>
<style>
    .success_msg,.successmsg,.alert-success {
        z-index: 999999999;
        padding: 10px;
        width: 100%;
        background: rgba(89,179,89,0.9);
        position: fixed;
        color: #fff;
        text-align: center;
        top: 0px;
        left: 0px;
    }

    .errorJsSummary,.errorSummary,.alert-error{
        z-index: 999999999;
        padding: 10px;
        width: 100%;
        background: brown;
        position: fixed;
        color: #fff;
        text-align: center;
        top: 0px;
        left: 0px;
    }
</style>
<script>
    function hide_msg()
{
   setTimeout(function() {
        $(".alert-success").fadeOut();
    }, 5000);
   setTimeout(function() {
        $(".alert-error").fadeOut();
    }, 5000);
}
    function show_error(error_str)
{
    $(".errorJsSummary").stop().attr("style", "display:none;").fadeIn('slow').html(error_str);
    setTimeout(function() {
        $(".errorJsSummary").fadeOut();
    }, 5000);
}


function show_success(msg)
{

    $(".successmsg").attr("style", "display:none;").fadeIn('slow').html(msg);
    setTimeout(function() {
        $(".successmsg").fadeOut();
    }, 5000);
}
    
    
    
    
    
    function activelogin(type) {
        document.getElementById('loginWrapper').className = 'activelogin';
        activebox(type);
        document.getElementById('blackoverlay').style.display = "block";
        document.getElementById('blackoverlay').className = 'loginWrapper';
    }

    function activebox(type) {
        document.getElementById('loginWrapper').className = 'activelogin ' + type;
    }

    function closebox(name) {
        //document.getElementById('loginWrapper').className='';
        document.getElementById('blackoverlay').style.display = '';
        document.getElementById('blackoverlay').className = '';

        document.getElementById('askQuestionWrapper').className = '';
        document.getElementById('forgetPasswordWrapper').className = '';
        //document.getElementById('cartDialogWrapper').className='';
<?php
/* deatilo page popup */
if (isset($_GET['alias'])) {
    ?>
            document.getElementById('chooseSignUp').className = '';
            document.getElementById('rateProductWrapper').className = '';
<?php } ?>
    }
    function openUserMenu(id) {
        display = document.getElementById(id).style.display;
        if (display == 'block') {
            document.getElementById(id).style.display = 'none';
        }
        else {
            document.getElementById(id).style.display = 'block';
        }
    }

    function signUpChooseDialog(id, classs) {
        document.getElementById('rateProductWrapper').style.display = "block";
        document.getElementById(id).className = classs;
        document.getElementById('blackoverlay').style.display = "block";
        document.getElementById('blackoverlay').className = id;
    }
</script>
<script type="text/javascript">
//window.onclick = function(event) {
//    event.preventDefault();
//  console.log(event.target.id);
//  console.log('---');
//  console.log((event.target).closest('div'));
//  var m=(event.target).closest('div');
//  console.log(m.target.id);
//  
////  var m=(event.target).closest('div#rateProductWrapper').length;
////  console.log(m);
////  var m=(event.target).closest('div#loginBox').length;
////  console.log(m);
//  
//   if(event.target.id!="rateProductWrapper" && event.target.id!="writeareview")
//{ 
//  // console.log(event.target.id);
//    document.getElementById("rateProductWrapper").style.display="none";
//  }
//    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
$('body').click(function(e){
    
    var rateProductWrapper = $("#rateProductWrapper");
        if (!(rateProductWrapper.is(e.target)) && rateProductWrapper.has(e.target).length ===0 && e.target.className != 'writeareview') // ... nor a descendant of the container
        {
            rateProductWrapper.hide();

        }else{
            rateProductWrapper.show();
        }
    });
    });
</script>
<script type="text/javascript">
//   var settings = document.getElementById('rateProductWrapper');
//console.log('dddd');
//document.onclick = function(e){
//
//    var target = (e && e.target) || (event && event.srcElement);
//    var display = 'none';
//    if(event.target.id!="rateProductWrapper" && event.target.id!="writeareview"){
//        display ='block';
//    }
//console.log(target.parentNode);
//    while (target.parentNode) {
//
//        if (target == settings) {
//            display ='block';
//            break;
//        }
//        target = target.parentNode;
//    }
//
//settings.style.display = display;
//
//}
//    
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(function () {
        $("#headerSearch").autocomplete({
            minLength: 1,
            //source: 'https://<?php //echo $_SERVER['SERVER_NAME'];      ?>/jhm/ajaxSearch.php',
            source: '<?php echo siteUrl; ?>ajaxSearch.php',
            focus: function (event, ui) {
                name = ui.item.name;
                name = name.replace("<b>", '');
                name = name.replace("</b>", '');
                $("#headerSearch").val(name);
                return false;
            },
            select: function (event, ui) {
                type = ui.item.type;

                name = ui.item.name;
                name = name.replace("<b>", '');
                name = name.replace("</b>", '');

                $("#headerSearch").val(name);
                $("#headerSearchId").val(ui.item.id);
                $("#headerSearchType").val(type);

                if (type == 'product') {
                    window.location = ui.item.url;
                }
                else if (type == 'mainCat' || type == 'subCat' || type == 'subsubCat') {
                    document.getElementById('headerSearchForm').submit();
                }
                else if (type == 'brand') {
                    $("#headerSearchData").val(ui.item.url);
                    document.getElementById('headerSearchForm').submit();
                }
                return false;
            }

        }).autocomplete("instance")._renderItem = function (ul, item) {
            document.getElementById('headerSearch').className = 'ui-autocomplete-input';
            if (item.name != 'No') {
                return $("<li>").append("<a>" + item.name + "</a>").appendTo(ul);
            }
            else {
                document.getElementById('headerSearch').className = 'ui-autocomplete-input';
            }
        };
    });
</script>
<?php
/* cart product count */
$cartNo = 0;
if (isset($_SESSION['user'])) {
    $userID = $_SESSION['user'];
} elseif (isset($_SESSION['tempUser'])) {
    $userID = $_SESSION['tempUser'];
}
//echo "SELECT COUNT(cart_id) AS cartNo FROM tbl_cart WHERE user_id = '".$userID."'";
if (isset($userID) && $userID != '') {
    $cartCount = mysqli_fetch_object(exec_query("SELECT COUNT(cart_id) AS cartNo FROM tbl_cart WHERE user_id = '" . $userID . "'", $con));
    if (isset($cartCount->cartNo) && $cartCount->cartNo != '') {
        $cartNo = $cartCount->cartNo;
    }
}
?>
<?php 
//echo "<pre>";
//print_r($_SESSION);exit;
if(@$_SESSION['message']!=""){
    echo $_SESSION['message'];
     echo "<script>";
   echo "hide_msg();";
   echo "</script>";
   unset($_SESSION['message']);
}
?>
<header class="navbar transparent navbar-inverse navbar-fixed-top top-bg">


    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand" href="<?php echo siteUrl; ?>"><img src="<?php echo siteUrl; ?>images/new/logo.png" alt="logo"></a> 
        </div>
        <div class="soc-box">
            <div class="row">
                <div class="col-md-8"> 
                    <div class="top-search-form">


                        <form action="<?php echo siteUrl; ?>product-search" method="get" id="headerSearchForm"  >
                            <input type="hidden" name="searchTextId" id="headerSearchId" value="" />
                            <input type="hidden" name="searchTextType" id="headerSearchType" value="" />
                            <input type="hidden" name="searchTextData" id="headerSearchData" value="" />
                            <div class="input-group"> 
                                <input class="form-control" type="text"  required name="searchText" id="headerSearch"  value="<?php echo (isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != '') ? $_REQUEST['searchText'] : ''; ?>"  placeholder="Glowing Nail Effect Ajax Faceoil Agave Glow" autocomplete="off"/>
                                <span class="input-group-addon"><button type="submit" name="searchButton" class="ser-butt"><i class="fa fa-search"></i></button></span> </div> </form>  
                    </div>
                </div>
                <div class="col-md-4 pull-right text-right ">
                    <ul class="nav navbar-nav navbar-right">

                        <?php if (isset($_SESSION['user']) && isset($_SESSION['user_email'])) { ?>
                            <?php include 'include/userHeaderMenu.php'; ?>
                        <?php } else { ?>
                            <li><a href="<?php echo siteUrl; ?>userlogin/"> LOGIN / SIGNUP</a> </li>

                        <?php } ?>
                        <li class="dropdown mycart">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <img src="<?php echo siteUrl; ?>images/list.png" > MY CART (<span class="cartno" id="cart_count"><?php echo (isset($cartNo)) ? $cartNo : '0'; ?></span>) </a>
                            <!-- shopping-cart-->
                            <ul class="dropdown-menu dropdown-cart" role="menu">
                                <span id="shopingbag">
                                    <h3> your bag</h3>


                                    <li class="total-price"> <span class="item-left"><strong>Sub Total</strong></span> <span class="item-right"> $10.50</span></li>
                                </span>
                                <li class="text-center"><a  href="<?php echo siteUrl; ?>cart/" class="btn btn-chk ">CHECK OUT</a></li>
                            </ul>
                            <!-- shopping-cart-->

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
 <div class="mainmenu-area"> 
        <div class="container"> 
            <div class="collapse navbar-collapse"> 
                <ul class="nav navbar-nav navbar-left"> 
                    <li><a href="<?php echo siteUrl; ?>">HOME</a></li> 
                    <?php 
                    $feaTagRs = exec_query("SELECT title FROM tbl_tag WHERE is_menu = 1", $con); 
                    while ($feaTagRow = mysqli_fetch_object($feaTagRs)) { 
                        ?> 
                        <li> <a href="<?php echo siteUrl . 'online-sale/' . str_replace(' ', '-', $feaTagRow->title); ?>/"> <?php echo $feaTagRow->title; ?> </a> </li> 
                    <?php } ?> 
                        <li class="dropdown mega-dropdown"> 
                        <a href="<?php echo siteUrl; ?>deals/" class="dropdown-toggle" data-toggle="dropdown">DEALS </a>     
                        <?php 
                        $time=date('c'); 
                     
                    $r=exec_query("SELECT  
  tbl_category.category_name 
FROM tbl_category  
  INNER JOIN tbl_promotion_detail ON tbl_category.category_id = tbl_promotion_detail.ids 
  INNER JOIN tbl_promotion ON tbl_promotion.promo_id = tbl_promotion_detail.promo_id 
where tbl_promotion.start_date <='$time' and tbl_promotion.end_date>='$time' and tbl_promotion.is_publish='1'",$con); 
                     while ($r1 = mysqli_fetch_object($r)) { 
//                         echo $r1->category_name ; 
                     } 
                        ?> 
                        <div class="dropdown-menu mega-dropdown-menu" > 
                            <div class="menu-row"> 
                                <ul> 
                                    <li> <a href="<?php echo siteUrl; ?>all/0-0FACE//">FACE MAKEUP</a></li> 
                                    <li> <a href="#">EYE MAKEUP</a></li> 
                                    <li> <a href="#">LIPS</a></li> 
                                    <li> <a href="<?php echo siteUrl; ?>all/0-0NAILS//">NAILS</a></li> 
                                </ul> 
                                <ul> 
                                    <li> <a href="#">BODY &nbsp; COMAUFLAGE </a></li> 
                                    <li> <a href="#">MAKEUP</a></li> 
                                    <li> <a href="#">Makeup Sets</a></li> 
                                    <li> <a href="#">See All</a></li> 
                                </ul> 
                                <ul> 
                                    <li> <a href="#"><img src="<?php echo siteUrl; ?>images/new/c-logo1.png" alt=""></a></li> 
                                    <li> <a href="#"><img src="<?php echo siteUrl; ?>images/new/c-logo2.png" alt=""></a></li> 
                                </ul> 
                                <ul> 
                                    <li> <a href="#"><img src="<?php echo siteUrl; ?>images/new/c-logo3.png" alt=""></a></li> 
                                    <li> <a href="#"><img src="<?php echo siteUrl; ?>images/new/c-logo4.png" alt=""></a></li> 
                                </ul> 

                                <ul> 
                                    <li> <a href="#"><img src="<?php echo siteUrl; ?>images/new/c-logo5.png" alt=""></a></li> 
                                </ul> 
                            </div> 
                        </div> 


                    </li> 
                </ul> 


            </div> 

        </div> 
    </div>  
</header>

<!--/header-->
<script>
    $("#headerSearchForm").keyup(function (e) {
        // only small abcd /////////////////////////// only capital ABCD /////////////////////// only 1234
        if ((e.keyCode >= 97 && e.keyCode <= 122) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode == 32 || e.keyCode == 8) {
            if ($('#headerSearch').val().length >= 1) {
                document.getElementById('headerSearch').className = 'ui-autocomplete-input loading';
            }
        }
    });


    function closequickcart() {
        document.getElementById('cartbox').style.display = '';
        document.getElementById('quickkartclose').style.display = '';
    }
// cart operation
    function getCartDataInDialog(opendialog) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById('shopingbag').innerHTML = xmlhttp.responseText;
                if (opendialog != 0) {
                    $('.cartno').text($('#cartCounter').val());
                    $("li.mycart").addClass("open");
                }
            }
        }
        xmlhttp.open("GET", "<?php echo siteUrl; ?>ajax.php?action=getCartDataInDialog&dataTempId=sz7xa1g262d0xq316ld3fhu1.cloud.uk", true);
        xmlhttp.send();
    }

    getCartDataInDialog(0);
</script>