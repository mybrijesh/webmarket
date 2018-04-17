<?php
  if( !isset( $_GET[ "q" ] ) ) {
    header( "location: index.php" );
  }

  if( $_POST &&
      isset( $_COOKIE[ "session_id" ] ) &&
      isset( $_GET[ "q" ] ) &&
      isset( $_POST[ "action" ] )  &&
      !strcmp( $_POST[ "action" ], "add_to_cart" ) ) {
    try{
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "INSERT INTO cart(cart_user_id,cart_item_id,cart_item_quantity) "
        ."VALUES( ( SELECT DISTINCT session_user_id FROM sessions WHERE strcmp(session_hash,:hash)=0 ), :item_id, :item_qty )" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ],
        "item_id" => $_GET[ "q" ],
        "item_qty" => $_POST[ "qty" ] ] );
    } catch( PDOException $ex ) {
      echo "PDOException: " . $ex->getMessage( ) . "\n";
      die( );
    }
  } else if( $_POST &&
      isset( $_POST[ "submitted" ] ) &&
      isset( $_POST[ "rating" ] ) &&
      isset( $_COOKIE[ "session_id" ] ) &&
      !strcmp( $_POST[ "submitted" ], "true" ) ) {
    try {
      $id = $_GET[ 'q' ];
      $post = $_POST[ "rating" ];
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "INSERT INTO rating(rating_user_id,rating_stars,rating_item_id,rating_desc) "
        ."VALUE((SELECT session_user_id FROM sessions WHERE strcmp(session_hash,:hash)=0),5,$id,'$post')" );
      $stmt->execute( [ "hash" => $_COOKIE[ "session_id" ] ] );
    } catch( PDOException $ex ) {
      echo "PDOException: " . $ex->getMessage( ) . "\n";
      die( );
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
    <a href="#" class="pure-menu-heading pure-menu-link">Web Market&reg;</a>
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

      if( $row_count[0] > 0 ) {
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
	try {
    $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
    $stmt = $pdo->prepare( "SELECT DISTINCT item_Title,item_description,item_price,item_type,inventory_item_quantity,user_first_name,user_last_name "
      ."FROM user,item,inventory "
      ."WHERE item_id=:id AND user_id=item_user_id AND item_id=inventory_item_id AND inventory_item_quantity > 0" );
    $stmt->execute( [ "id" => $_GET[ "q" ] ] );

    $result = $stmt->fetch( );
	} catch( PDOException $ex ) {
    echo "PDOException: " . $ex->getMessage( ) . "\n";
    die( );
	}
?>
  <div class="content-wrapper">
    <h1><?php echo $result[ "item_Title" ]." in ".$result[ "item_type" ]; ?></h1>
    <div class="product-panel">
      <h1><?php echo $result[ "item_Title" ]; ?></h1>
      <h3><?php echo $result[ "item_type" ]; ?></h3>
      <h4>Sold by <?php echo $result[ "user_first_name" ]." ".$result[ "user_last_name" ]; ?></h4>
      <h2>Price: $<?php echo $result[ "item_price" ]; ?> USD</h2>
      <p><?php echo $result[ "item_Title" ] . ": " . $result[ "item_description" ]; ?></p>
      <form action="checkout.php" method="post">
        <button class="pure-button button-success"><i class="fa fa-usd"></i> Buy now!</button>
        <input type="hidden" name="checkout_type" value="instant" />
      </form><form method="post">
        <input type="number" name="qty" value="1" style="color:#000" min="1" max="<?php echo $result["inventory_item_quantity"]; ?>"/> / <?php echo $result[ "inventory_item_quantity" ]; ?><br />
        <button class="pure-button button-gold"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
        <input type="hidden" name="action" value="add_to_cart" />
      </form>
    </div><!-- /.product-panel -->

    <h3>Product Reviews</h3>
<?php
  try {
    $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
    $stmt = $pdo->prepare( "SELECT user_first_name,user_last_name,rating_stars,rating_desc "
      ."FROM rating,user "
      ."WHERE user_id=rating_user_id AND rating_item_id=:id" );
    $stmt->execute( [ "id" => $_GET[ "q" ] ] );

    while( $row = $stmt->fetch( ) ) {
      echo "<div class=\"rating-panel\"><h3><h2>"
        .$row[ 'user_first_name' ]
        ." "
        .$row[ 'user_last_name' ]
        ."</h2> ("
        .$row[ 'rating_stars' ]
        ." stars):</h3><p>"
        .$row[ 'rating_desc' ]
        ."</p></div>";
    }
  } catch( PDOException $ex ) {
    echo 'PDOException: ' . $ex->getMessage( ) . "\n";
    die( );
  }
?>
<?php
  if( isset( $_COOKIE[ "session_id" ] ) ) {
?>
    <form class="pure-form rating-panel" method="post">
      <h3><h2>Product Review</h2> form:</h3>
      <textarea style="display:block;border-sizing:border-box;width:100%" id="rating" name="rating"></textarea>
      <fieldset>
      <input class="pure-button button-success" type="submit" value="submit" />
      <input class="pure-button button-reset" type="reset" value="reset" />
      </fieldset>
      <input type="hidden" name="submitted" value="true" />
    </form><!--/.rating-panel -->
<?php
  } else {
?>
  <div class="rating-panel">
    <p>Please <a href="login.php">login</a> into your account.</p>
  </div><!-- /.rating-panel -->
<?php
  }
?>
  </div><!-- /.content-wrapper -->
</body></html>
<!--
vim: number ts=2 sw=2 expandtab tw=76
-->
