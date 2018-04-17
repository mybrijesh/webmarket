<?php
	if( !isset( $_COOKIE[ "session_id" ] ) ) {
		header( "location: login.php" );
	}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebMarket | Manage Addresses</title>

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

<!-- custom CSS -->
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
  if( isset( $_COOKIE[ "session_id" ] ) &&
      isset( $_POST[ "delete" ] ) &&
      !strcmp( $_POST[ "delete" ], "true" ) ) {
    try {
       $pdo = new PDO("mysql:host=localhost;dbname=webmarket_database", "root", "password");
       $stmt = $pdo->prepare( "DELETE FROM address WHERE address_user_id = (SELECT session_user_id FROM sessions WHERE STRCMP(session_hash,:hash)=0) AND address_id = :address_id" );
       $stmt->execute([ "hash" => $_COOKIE[ "session_id" ], "address_id" => $_POST[ "address_id" ] ]);
    } catch(PDOException $ex) {
      echo "PDOException: " . $ex->getMessage( ) . "\n";
      die( );
    }
  }

  if( isset( $_COOKIE[ "session_id" ] ) &&
      isset( $_POST[ "insert" ] ) &&
      !strcmp( $_POST[ "insert" ], "true" ) ) {
    try {
      $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
      $stmt = $pdo->prepare( "INSERT INTO address(address_user_id, address_1, address_2, address_city, address_state, address_zipcode) "
              ."VALUES ( (SELECT session_user_id FROM sessions WHERE STRCMP(:hash,session_hash)=0 ), :address_1, :address_2, :city, :state, :zipcode )" );

      $stmt->execute( [
        "hash"      => $_COOKIE[ "session_id" ],
        "address_1" => $_POST[ "address_1" ],
        "address_2" => $_POST[ "address_2" ],
        "city"      => $_POST[ "city" ],
        "state"     => $_POST[ "state" ],
        "zipcode"   => $_POST[ "zipcode" ]
      ] );
    } catch(PDOException $ex) {
      echo 'PDOException: ' . $ex->getMessage( ) . "\n";
      die( );
    }
  }
?>

<div class="text-wrapper">
  <h1>Your Address(es)</h1>
  <form method="post">
  <?php
    if( isset( $_COOKIE[ "session_id" ] ) ) {
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM address WHERE address_user_id = (SELECT session_user_id FROM sessions WHERE STRCMP(session_hash, :session)=0)" );
        $stmt->execute( [ "session" => $_COOKIE[ "session_id" ] ] );

        $i = 0;
        while( $row = $stmt->fetch( ) ) {
?>
        <div class="address-panel">
          <ul>
            <li><input type="radio" name="address_id" value="<?php echo $row[ "address_id" ]; ?>" <?php if( $i == 0 ) { echo "checked"; ++$i; } ?>/><?php echo $row[ "address_1" ]; ?></li>
            <li><?php echo $row[ "address_2" ]; ?></li>
            <li><?php echo $row[ "address_city" ]; ?></li>
            <li><?php echo $row[ "address_state" ]; ?></li>
            <li><?php echo $row[ "address_zipcode" ]; ?></li>
          </ul>
        </div><!-- /.address-panel -->
<?php
        }

        $stmt = $pdo->prepare( "SELECT count(*) FROM address WHERE address_user_id = (SELECT session_user_id FROM sessions WHERE STRCMP(session_hash, :session)=0)" );
        $stmt->execute( [ "session" => $_COOKIE[ "session_id" ] ] );
        $row = $stmt->fetch( );

        if( $row[ "count(*)" ] > 0 ) {
?>
    <input type="hidden" name="delete" value="true"/>
    <input class="pure-button button-reset" type="submit" value="Delete"/>
<?php
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . "\n";
        die( );
      }
    }
?>
  </form><!-- /.pure-form -->
  <form class="pure-form" method="post">
    <fieldset class="fieldset-spacing">
    <label for="address_1">Address line 1:</label><input class="pure-input-rounded" type="text" name="address_1" id="address_1"/>
    </fieldset>

    <fieldset class="fieldset-spacing">
    <label for="address_2">Address line 2:</label><input class="pure-input-rounded" type="text" name="address_2" id="address_2"/>
    </fieldset>

    <fieldset class="fieldset-spacing">
    <label for="city">City:</label><input class="pure-input-rounded" type="text" name="city" id="city"/>
    </fieldset>

    <fieldset class="fieldset-spacing">
    <label for="state">State:</label><input class="pure-input-rounded" type="text" name="state" id="state"/>
    </fieldset>

    <fieldset class="fieldset-spacing">
    <label for="zipcode">Zipcode:</label><input class="pure-input-rounded" type="text" name="zipcode" id="zipcode">
    </fieldset>


    <fieldset class="fieldset-spacing">
      <input class="pure-button button-success" type="submit" value="Submit">
      <input class="pure-button button-reset" type="reset" value="Reset">
    </fieldset>
    <input type="hidden" name="insert" value="true" />
  </form><!-- /.pure-form -->


</div><!-- /.text-wrapper -->
</body></html>
<!--
vim: number ts=2 sw=2 expandtab tw=76
-->
