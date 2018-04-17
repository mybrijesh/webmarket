<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
  echo '<!--';

  var_dump( $_POST );

  echo '-->';
  if( !isset( $_COOKIE[ "session_id" ] ) ) {
    header( "location: index.php" );
  }

  if( isset( $_COOKIE[ "session_id" ] ) ) {
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "SELECT count(*) FROM cart,payment WHERE cart_user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0) AND payment_user_id=cart_user_id" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );

      $row = $stmt->fetch( );

      if( $row[ "count(*)" ] == 0 )
        header( "location: index.php" );
    } catch( PDOException $ex ) {
      echo 'PDOException: ' . $ex->getMessage( ) . "\n";
    }
  }

  if( $_POST &&
      isset( $_POST[ "remove" ] ) ) {
    try {
      $pdo  = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "DELETE FROM cart WHERE cart_item_id=:id AND cart_user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0)" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ], "id" => $_POST[ "remove" ] ] );
    } catch( PDOException $ex ) {
      echo 'PDOException: ' . $ex->getMessage( ) . "\n";
      die( );
    }
  }

  if( $_POST &&
      isset( $_POST[ "new_address" ] ) &&
      !strcmp( $_POST[ "new_address" ], "true" ) ) {
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "INSERT INTO address(address_user_id,"
        ."address_1,"
        ."address_2,"
        ."address_city,"
        ."address_state,"
        ."address_zipcode) "
        ."VALUE((SELECT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0),"
          . ":address_1,"
          . ":address_2,"
          . ":address_city,"
          . ":address_state,"
          . ":address_zipcode)" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ], 
        "address_1" => $_POST[ "address_1" ],
        "address_2" => $_POST[ "address_2" ],
        "address_city" => $_POST[ "address_city" ],
        "address_state" => $_POST[ "address_state" ],
        "address_zipcode" => $_POST[ "address_zipcode" ] ] );
    } catch( PDOException $ex ) {
      echo 'PDOException: ' . $ex->getMessage( ) . "\n";
      die( );
    }
  } 

  if( $_POST &&
      isset( $_POST[ "checkout" ] ) &&
      !strcmp( $_POST[ "checkout" ], "true" ) ) {
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt1 = $pdo->prepare( "SELECT * FROM cart,item WHERE cart_user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0) AND item_id=cart_item_id" );
      $stmt1->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );
      while( $row = $stmt1->fetch( ) ) {
        $total = $row[ "cart_item_quantity" ] * $row[ "item_price" ];

        if( !isset( $_POST[ "new_address" ] ) ) {
          $stmt = $pdo->prepare( "INSERT INTO orders("
            ."order_date,"
            ."order_user_id,"
            ."order_seller_id,"
            ."order_total,"
            ."order_payment_id,"
            ."order_shipping_address_id,"
            ."order_item_id,"
            ."order_item_quantity) "
          ."VALUE( CURDATE(),"
            ."(SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0),"
            ."(SELECT item_user_id FROM item WHERE item_id=:item_id),"
            .":total,"
            ."(SELECT payment_id FROM payment WHERE payment_user_id=(SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0)),"
            ."(SELECT address_id FROM address WHERE address_user_id=(SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0)),"
            .":item_id,"
            .":item_qty)"
          );

          $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ],
            "item_id" => $row[ "cart_item_id" ],
            "total" => $total,
            "item_qty" => $row[ "cart_item_quantity" ] ] );
        } else {
          $stmt = $pdo->prepare( "INSERT INTO orders("
            ."order_date,"
            ."order_user_id,"
            ."order_seller_id,"
            ."order_total,"
            ."order_payment_id,"
            ."order_shipping_address_id,"
            ."order_item_id,"
            ."order_item_quantity) "
          ."VALUE( CURDATE(),"
            ."(SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0),"
            ."(SELECT item_user_id FROM item WHERE item_id=:item_id),"
            .":total,"
            ."(SELECT payment_id FROM payment WHERE payment_user_id=(SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0)),"
            ."(SELECT address_id FROM address WHERE STRCMP(address_1,:placeholder)=0),"
            .":item_id,"
            .":item_qty)"
          );
          $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ],
            "item_id" => $row[ "cart_item_id" ],
            "total" => $total,
            "placeholder" => $_POST[ "address_1" ],
            "item_qty" => $row[ "cart_item_quantity" ] ] );
        }
      }

      $stmt = $pdo->prepare( "SELECT inventory_item_quantity,cart_item_quantity FROM inventory,cart WHERE inventory_item_id=cart_item_id" );
      $stmt->execute( [] );

      while( $row = $stmt->fetch( ) ) {
        $stmt2 = $pdo->prepare( "UPDATE inventory,cart SET inventory_item_quantity=:qty WHERE inventory_item_id=cart_item_id" );
        $stmt2->execute( [ "qty" => ( $row[ "inventory_item_quantity" ] - $row[ "cart_item_quantity" ] ) ] );
      }

      $stmt = $pdo->prepare( "DELETE FROM cart WHERE cart_user_id=(SELECT session_user_id FROM sessions WHERE STRCMP(:hash, session_hash)=0)" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );

      header( "location: index.php" );
    } catch( PDOException $ex ) {
      echo "PDOException: " . $ex->getMessage( ) . "\n";
    }
  }
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebMarket | Buy, Sell, Resell</title>

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
    </div><!-- /.pure-u-1.pure-u-md-2-3 -->
    <div class="pure-u-1-3 pure-u-md-1-3" style="text-align:right">
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
        <a href="myaccount.php" class="pure-menu-link"><i class="fa fa-user"></i></a>
      </li><li class="pure-menu-item">
        <a href="login.php?action=logout" class="pure-menu-link"><i class="fa fa-sign-out"></i></a>
      </li>
<?php
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "SELECT count(*) FROM cart WHERE cart_user_id="
        ."(SELECT session_user_id FROM sessions WHERE strcmp(session_hash,:hash)=0)" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );
      $row_count = $stmt->fetch( );

      if( $row_count[0] > 0 ) {
?>
      <li class="pure-menu-item">
        <a href="checkout.php" class="pure-menu-link"><i class="fa fa-shopping-cart"></i></a>
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
<?php
	try {
    $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
    $stmt = $pdo->prepare( "SELECT * FROM cart,item WHERE cart_user_id=(SELECT session_user_id FROM sessions WHERE strcmp(:hash, session_hash)=0) AND item_id=cart_item_id" );
    $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );

    while( $row = $stmt->fetch( ) ) {
?>
    <form class="cart-item" method="post">
      <h3><?php echo $row[ "item_Title" ]; ?></h3>
      <p><?php echo $row[ "item_description" ]; ?></p>
      <p>Quantity: <?php echo $row[ "cart_item_quantity" ]; ?> UOM</p>
      <p>Price: $<?php echo $row[ "item_price" ]; ?> USD</p>
      <input type="hidden" name="remove" value="<?php echo $row[ "item_id" ]; ?>" />
      <input class="pure-button button-reset" type="submit" value="Remove" />
    </form><!-- /.cart-item -->
<?php
    }
	} catch( PDOException $ex ) {
    echo "PDOException: " . $ex->getMessage( ) . "\n";
    die( );
	}
?>

  <form class="pure-form" method="post">
<?php
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "SELECT * FROM payment,address WHERE payment_user_id="
        ."(SELECT DISTINCT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0) AND "
        ."payment_billing_address_id=address_id" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );
      $i = 0;
      while( $row = $stmt->fetch( ) ) {
?>
    <div style="display:block">
      <ul style="list-style:none">
        <li><input type="radio" name="payment" value="<?php echo $row[ "payment_id" ]; ?>" <?php if( $i == 0 ){ echo 'checked'; ++$i; } ?>> 
        Payment Type: <?php echo $row[ "payment_type" ]; ?></li>
        <li>Card#: <?php echo "#### #### #### ".substr($row[ "payment_card_number" ], 12, 4 ); ?></li>
        <li>Expiration: <?php echo $row[ "payment_card_expiration" ]; ?></li>
        <li>CVC: <?php echo $row[ "payment_card_security_code" ]; ?></li>
      </ul>
      <ul style="list-style:none">
        <li>Address Line 1: <?php echo $row[ "address_1" ]; ?></li>
        <li>Address Line 2: <?php echo $row[ "address_2" ]; ?></li>
        <li>City: <?php echo $row[ "address_city" ]; ?></li>
        <li>State: <?php echo $row[ "address_state" ]; ?></li>
        <li>Zipcode: <?php echo $row[ "address_zipcode" ]; ?></li>
      </ul>
    </div>
<?php
      }
    } catch( PDOException $ex ) {
      echo 'PDOException: ' . $ex->getMessage( ) . "\n";
      die( );
    }
?>
    <input type="checkbox" id="new_address" name="new_address" value="true"/> I wish to ship elsewhere
    <fieldset class="fieldset-spacing">
      Name: <input type="text" id="address_name" name="address_name" disabled />
    </fieldset>
    <fieldset class="fieldset-spacing">
      Address 1: <input type="text" id="address_1" name="address_1" disabled />
    </fieldset>
    <fieldset class="fieldset-spacing">
      Address 2: <input type="text" id="address_2" name="address_2" disabled />
    </fieldset>
    <fieldset class="fieldset-spacing">
      City: <input type="text" id="address_city" name="address_city" disabled />
    </fieldset>
    <fieldset class="fieldset-spacing">
      State: <input type="text" id="address_state" name="address_state" disabled />
    </fieldset>
    <fieldset class="fieldset-spacing">
      Zipcode: <input type="text" id="address_zipcode" name="address_zipcode" disabled />
    </fieldset>

    <fieldset>
      <button type="submit" class="pure-button button-success"><i class="fa fa-money"></i> Checkout</button>
      <input type="hidden" name="checkout" value="true" />
    </fieldset>
  </form>
  </div><!-- /.content-wrapper -->

  <script type="text/javascript">
    function toggle_new_address() {
      document.getElementById( "address_name"    ).disabled = !( document.getElementById( "address_name"    ).disabled );
      document.getElementById( "address_1"       ).disabled = !( document.getElementById( "address_1"       ).disabled );
      document.getElementById( "address_2"       ).disabled = !( document.getElementById( "address_2"       ).disabled );
      document.getElementById( "address_city"    ).disabled = !( document.getElementById( "address_city"    ).disabled );
      document.getElementById( "address_state"   ).disabled = !( document.getElementById( "address_state"   ).disabled );
      document.getElementById( "address_zipcode" ).disabled = !( document.getElementById( "address_zipcode" ).disabled );
    }

    document.getElementById( "new_address" ).onchange = toggle_new_address;
  </script>
</body></html>
<!--
vim: number ts=2 sw=2 tw=76 expandtab
-->
