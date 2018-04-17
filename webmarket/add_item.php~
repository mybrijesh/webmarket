<?php
echo '<!--';
var_dump( $_POST );
echo '-->';
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
	if( !isset( $_COOKIE[ "session_id" ] ) ) {
		header( "location: login.php" );
	}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebMarket | Add Product</title>

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
if( isset( $_COOKIE[ "session_id" ] ) &&
    isset($_POST[ "insert" ] ) &&
    !strcmp( $_POST[ "insert" ], "true" ) ) {
  try{
    $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
    $stmt = $pdo->prepare( "INSERT INTO item(item_user_id,"
      ."item_Title,"
      ."item_price,"
      ."item_description,"
      ."item_type) "
      ."VALUE((SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(session_hash,:hash)=0),"
      .":title,"
      .":price,"
      .":description,"
      .":type)" );
    $ret1 = $stmt->execute([
      "hash"        => $_COOKIE[ "session_id" ],
      "title"       => $_POST[ "title" ],
      "price"       => $_POST[ "price" ],
      "description" => $_POST[ "description" ],
      "type"        => $_POST[ "type" ]
    ]);

    $stmt = $pdo->prepare( "INSERT INTO inventory(inventory_item_id,inventory_item_quantity) "
      ."VALUE(LAST_INSERT_ID(),"
      .":qty)" );
    $ret2 = $stmt->execute([
      "qty"   => $_POST[ "quantity" ]
    ]);

    if( $ret1 && $ret2 ) {
      echo "<p>Item inserted successfully</p>";
    } else {
      echo "<p>Item NOT inserted successfully</p>";
    }
  } catch( PDOException $ex ) {
    echo 'PDOException: ' . $ex->getMessage( ) . "\n";
    die( );
  }
}
?>

<h1>Add Product</h1>
<form class="pure-form" method="post">
  <fieldset class="fieldset-spacing">
    <label for="title">Item Title:</label><input class="pure-input-rounded" type="text" name="title"/>
  </fieldset>

  <fieldset class="fieldset-spacing">
    <label for="description">Item Description:</label><textarea name="description" style="box-sizing:border-box;width:100%"></textarea>
  </fieldset>

  <fieldset class="fieldset-spacing">
    <label for="price">Item Price:</label><input class="pure-input-rounded" type="text" name="price"/>
  </fieldset>

  <fieldset class="fieldset-spacing">
    <label for="type">Item Type:</label><input class="pure-input-rounded" type="text" name="type"/>
  </fieldset>

  <fieldset class="fieldset-spacing">
    <label for="quantity">Item Quantity:</label><input class="pure-input-rounded" type="text" name="quantity"/>
  </fieldset>

  <fieldset>
    <input class="pure-button button-success" type="submit" value="Submit"/>
    <input class="pure-button button-reset" type="reset" value="Reset"/>
  </fieldset>
  <input type="hidden" name="insert" value="true" />
</form>
</div><!-- /.text-wrapper -->
</body></html>
<!--
vim: number ts=2 sw=2 expandtab tw=76
-->
