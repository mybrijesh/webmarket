<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebMarket | Manage Password</title>

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

<div class="text-wrapper">
<?php
if( isset( $_COOKIE[ "session_id" ] ) && isset( $_POST[ "current_password" ] ) ) {
  if( $_POST[ "new1_password" ] == $_POST[ "new2_password" ] ) {
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "SELECT count(*) FROM user WHERE user_password=:pwd AND user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,:hash)=0)" );
      $stmt->execute([ "pwd" => $_POST[ "current_password" ],
        "hash" => $_COOKIE[ "session_id" ] ]);

      $row = $stmt->fetch( );

      if( $row[ "count(*)" ] > 0 ) {
        $stmt = $pdo->prepare( "UPDATE user SET user_password=:pwd WHERE user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,:hash)=0)");
        $stmt->execute([ "pwd" => $_POST[ "new1_password" ],
          "hash" => $_COOKIE[ "session_id" ] ]);
      } else {
        echo "<p>Current password does not match!</p>";
      }
    } catch( PDOException $ex ) {
      echo 'PDOException: ' . $ex->getMessage( ) . "\n";
      die( );
    }
  } else {
    echo "<p>New passwords don't match!</p>";
  }
}
?>
  <form class="pure-form" method="post">
    <fieldset class="fieldset-spacing">
      <label for="current_password">Current Password:</label><input class="pure-input-rounded" type="password" name="current_password"/>
    </fieldset>
    <fieldset class="fieldset-spacing">
      <label for="new1_password">New Password:</label><input class="pure-input-rounded" type="password" name="new1_password"/>
    </fieldset>
    <fieldset class="fieldset-spacing">
      <label for="new2_password">New Password (repeat):</label><input class="pure-input-rounded" type="password" name="new2_password"/>
    </fieldset>
    <fieldset class="fieldset-spacing">
      <input class="pure-button button-success" type="submit" value="Change"/>
      <input class="pure-button button-reset" type="reset" value="Reset"/>
    </fieldset>
  </form><!-- /.pure-form -->
</div><!-- /.text-wrapper -->

</body></html>
<!--
vim: number ts=2 sw=2 expandtab tw=76
-->
