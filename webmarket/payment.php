<?php
	if( !isset( $_COOKIE[ "session_id" ] ) ) {
		header( "location: login.php" );
	}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebMarket | Manage Payment</title>

<!-- normalize -->
<link rel="stylesheet"
href="https://unpkg.com/purecss@1.0.0/build/base-min.css">

<!-- purecss -->
<link rel="stylesheet"
href="https://unpkg.com/purecss@1.0.0/build/pure-min.css">

<!-- css responsive grid -->
<!--[if lte IE 8]>
<link rel="stylesheet"
href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-old-ie-min.css"
>
<![endif]-->
<!--[if gt IE 8]><!-->
<link rel="stylesheet"
href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
<!--<![endif]-->

<!-- slick -->
<link rel="stylesheet" type="text/css"
href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/>

<!-- custom CSS -->
<link rel="stylesheet" href="/css/site.css">

<!-- control panel CSS -->
<link rel="stylesheet" href="/css/control-panel.css">

<!-- font awesome -->
<script src="https://use.fontawesome.com/103f400586.js"></script>
</head><body>
  <div class="pure-menu pure-menu-horizontal pure-menu-scrollable">
    <div class="pure-g content-wrapper">
    <div class="pure-u-2-3 pure-u-md-2-3">
    <a href="/" class="pure-menu-heading pure-menu-link">Web Market&reg;</a>
    <ul class="pure-menu-list">
    <li class="pure-menu-item"><a href="/?p=home-appliances" class="pure-menu-link">
        Home Appliances
    </a></li>
    <li class="pure-menu-item"><a href="/?p=electronics" class="pure-menu-link">
        Electronics
    </a></li>
    <li class="pure-menu-item"><a href="/?p=camping" class="pure-menu-link">
        Camping
    </a></li>
    <li class="pure-menu-item"><a href="/?p=sports" class="pure-menu-link">
        Sports
    </a></li>
    <li class="pure-menu-item"><a href="/?p=school" class="pure-menu-link">
        School
    </a></li>
    <li class="pure-menu-item"><a href="/?p=books" class="pure-menu-link">
        Books
    </a></li>
    </ul><!-- /.pure-menu-list -->
    </div><!-- /.pure-u-1.pure-u-md-2-3 -->
    <div class="pure-u-1-3 pure-u-md-1-3" style="">
    <ul class="pure-menu-list">
<?php
    if( !isset( $_COOKIE[ "session_id" ] ) ) {
?>
      <li class="pure-menu-item"><a href="login.php" class="pure-menu-link">
        Login
      </a></li>
      <li class="pure-menu-item"><a href="register.php" class="pure-menu-link">
        Register
      </a></li>
<?php
    } else {
?>
      <li class="pure-menu-item">
        <a href="myaccount.php" class="pure-menu-link"><i class="fa fa-user"></i> My Account</a>
      </li><li class="pure-menu-item">
        <a href="login.php?action=logout" class="pure-menu-link"><i class="fa fa-sign-out"></i> Logout</a>
      </li>
<?php
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "SELECT count(*) FROM cart WHERE cart_user_id="
        ."(SELECT session_user_id FROM sessions WHERE strcmp(session_hash,:hash)=0)" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );
      $row_count = $stmt->fetch( );

      if( $row_count[ "count(*)" ] > 0 ) {
?>
      <li class="pure-menu-item">
        <a href="checkout.php" class="pure-menu-link"><i class="fa fa-shopping-cart"></i> Checkout</a>
      </li>
<?php
      }
    }
?>
    </ul><!-- /.pure-menu-list -->
    </div><!-- /.pure-u-1.pure-u-md-1-3 -->
    </div><!-- /.pure-g -->
  </div><!-- /.pure-menu -->

<?php
if(isset( $_COOKIE[ "session_id" ] ) && isset($_POST['delete']))
{
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "webmarket_database";

try{
	 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 $card = $_POST["card"];
	 $hash = $_COOKIE["session_id"];
	 $sql = "DELETE FROM payment WHERE payment_user_id = (SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,'$hash')=0) AND payment_id = $card";
	 //var_dump($sql);
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	// var_dump($row);
}
catch(PDOException $e)
{
	echo $sql . "<br" . $e->getMessage();
}

}
?>

<?php
if(isset( $_COOKIE[ "session_id" ] ) && isset($_POST['Submit']))
{
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "webmarket_database";

try{
	 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 $cardtype = $_POST["cardtype"];
	 $cardnum = $_POST["cardnumber"];
	 $exp =  $_POST["cardexp"];
	 $scode  = $_POST["securitycode"];
	 $add = $_POST["address"];
	 $hash = $_COOKIE["session_id"];
	 $name = $_POST["cname"];
	 $sql = "INSERT INTO payment(payment_user_id,payment_type,payment_card_number,payment_card_expiration,payment_card_security_code,payment_billing_address_id,payment_cardholder_name)
		 VALUE((SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,'$hash')=0),'$cardtype','$cardnum','$exp','$scode',$add,'$name');";
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();

		 
	// var_dump($row);
}
catch(PDOException $e)
{
	echo $sql . "<br" . $e->getMessage();
}

}
?>
<div class="text-wrapper">
<form class="pure-form" method="post">
<fieldset class="fieldset-spacing">
<label for="cname">card holder name:</label><input class="pure-input-rounded" type="text" name="cname">
</fieldset>
<fieldset class="fieldset-spacing">
<label for="cardtype">card type:</label>
<select name="cardtype" id="cardtype">
  <option value="visa">visa</option>
  <option value="american express">american express</option>
  <option value="discover">discover</option>
  <option value="master card">master card</option>
</select> 
</fieldset>
<fieldset class="fieldset-spacing">
<label for="cardnumber">card number:</label><input class="pure-input-rounded"type="text" name="cardnumber" id="cardnumber" >
</fieldset>
<fieldset class="fieldset-spacing">
<label for="cardexp">exp:</label><input class="pure-input-rounded" type="text" name="cardexp" id="cardexp">
</fieldset>
<fieldset class="fieldset-spacing">
<label for="securitycode">security code:</label><input class="pure-input-rounded" type="text" name="securitycode" id="securitycode">
</fieldset>
<fieldset class="fieldset-spacing">
<label for="address">Billing Address:</label>
<?php
if(isset( $_COOKIE[ "session_id" ] ))
{
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "webmarket_database";

try{
	 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 $hash = $_COOKIE["session_id"];
	 $sql = "SELECT * FROM address WHERE address_user_id = (SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,'$hash')=0)";
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 echo "<select name='address'>";
	 while($row = $stmt->fetch())
	 {
		 echo "<option value='".$row["address_id"]."' type='number'>".$row["address_1"]."</option>";
	 }
	 echo "</select>";
	// var_dump($row);
	// var_dump($row);
}
catch(PDOException $e)
{
	echo $sql . "<br" . $e->getMessage();
}

}
?>
</fieldset>
<fieldset class="fieldset-spacing">
<input class="pure-button button-success" type="submit" value="Submit">
<input class="pure-button button-reset" type="reset" value="Reset">
</fieldset>
<input type="hidden" name="Submit" />
</form>

<form class="pure-form" method="post">
<fieldset class="fieldset-spacing">
<label for="card">cards:</lable>
<?php
if(isset( $_COOKIE[ "session_id" ] ))
{
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "webmarket_database";

try{
	 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 $hash = $_COOKIE["session_id"];
	 $sql = "SELECT * FROM payment WHERE payment_user_id = (SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,'$hash')=0)";
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 echo "<select name='card'>";
	 while($row = $stmt->fetch())
	 {
		 echo "<option value='".$row["payment_id"]."' type='number'>".$row["payment_card_number"]."</option>";
	 }
	 echo "</select>";
	// var_dump($row);
	// var_dump($row);
}
catch(PDOException $e)
{
	echo $sql . "<br" . $e->getMessage();
}

}
?>
</fieldset>
<fieldset class="fieldset-spacing">
<input type="hidden" name="delete" />
<input class="pure-button button-reset" type="submit" value="delete">
</fieldset>
</form>
<div><!-- /.text-wrapper -->
</body></html>
