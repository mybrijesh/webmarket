<?php
	if( !isset( $_COOKIE[ "session_id" ] ) ) {
		header( "location: login.php" );
	}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebMarket | My Account</title>

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
  <div class="content-wrapper">
    <div class="account-panel">
    <h1><i class="fa fa-user fa-3x"></i> My Account</h1>
    <ul>
      <li><a href="address.php"><i class="fa fa-address-card"></i> Manage Addresses</a></li>
      <li><a href="orders.php"><i class="fa fa-truck"></i> Manage Orders</a></li>
      <li><a href="password.php"><i class="fa fa-key"></i> Change Password</a></li>
      <li><a href="add_item.php"><i class="fa fa-archive"></i> Add Product</a></li>
      <li><a href="payment.php"><i class="fa fa-credit-card"></i> Manage Payment Method</a></li>
    </ul>
  </div><!-- /.account-panel -->
<?php
  if( $_COOKIE && isset( $_COOKIE[ "session_id" ] ) ) {
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "SELECT * FROM user WHERE user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(session_hash, :session)=0)" );
      $stmt->execute( [ "session" => $_COOKIE[ "session_id" ] ] );
      $result = $stmt->fetch();
?>
  <div class="account-panel">
    <h1><i class="fa fa-info-circle fa-3x"></i> Information</h1>
    <ul>
      <li><?php echo $result[ "user_first_name" ] . " " . $result[ "user_last_name" ]; ?></li>
      <li>Email: <?php echo $result[ "user_email" ]; ?></li>
      <li>Phone #: <?php echo $result[ "user_phone_number" ]; ?></li>
    </ul>
  </div><!-- /.account-panel -->
<?php
    } catch( PDOException $ex ) {
      echo "PDOException" . $ex->getMessage( ) . "\n";
      die( );
    }
  }
?>
</body></html>
<!--
vim: number ts=2 sw=2 tw=76 expandtab
-->
