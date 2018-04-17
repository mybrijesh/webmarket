<!doctype html>
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

  <div id="masthead">
    <div class="slide-1">
      Get the best out of your money by browsing our diverse catalog
    </div><!-- 1st slide -->
    <div class="slide-2">
      Shop with security Web Market&reg; is a safe solution for customers
    </div><!-- 2nd slide -->
    <div class="slide-3">
      Customer support is on your side, moderators can monitor/reverse
      transactions
    </div><!-- 3rd slide -->
    <div class="slide-4">
      Enjoy the fall shopping spree with free shipping to anywhere in the
      U.S
    </div><!-- 4th slide -->
  </div><!-- #masthead -->

  <!-- php code -->
  <?php
    /* get value for page */
    $page = 'front';

    if( $_GET ) {
      $page = $_GET[ 'p' ];
    }

    if( strcmp( $page, 'front' ) == 0 ) {
?>
  <div class="content-wrapper">
    <form class="search-panel pure-form" method="post">
      <h1>Search products..</h1>
      <input type="text" class="pure-input-rounded" style="color:#000"
        placeholder="Enter keywords" name="search_keyword" id="search_keyword" />
      <button type="submit" class="pure-button">
        <i class="fa fa-search"></i> Search
      </button>
      <input type="hidden" id="searched" name="searched" value="true"/>
    </form><!-- /.search-panel -->
  </div><!-- /.content-wrapper -->
<?php
      if( $_POST ) {
        if( strcmp( $_POST[ 'searched' ], "true" ) == 0 ) {
?>
  <div class="content-wrapper">
  <h1>Search Results</h1>
<?php
          /* put search query here */
          try {
            $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
            $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_id = inventory_item_id AND inventory_item_quantity > 0 AND item_Title LIKE concat( '%', :keyword, '%' )" );
            $stmt->execute( [ 'keyword' => $_POST[ 'search_keyword' ] ] );

            while( $row = $stmt->fetch() ) {
              echo '<div class="product">';
              echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
              echo '<p>' . $row[ "item_description" ] . '</p>';
              echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
              echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
              echo '</div>';
            }
          } catch( PDOException $ex ) {
            echo 'PDOException: ' . $ex->getMessage( ) . '\n';
          }

          echo "</div><!-- /.content-wrapper -->";
        }
      }
    } else if( strcmp( $page, 'home-appliances' ) == 0 ) {
      /* query database for home appliances */
      echo "<div class=\"content-wrapper\">";
      echo "<h1>" . $page . "</h1>";
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_type = 'home-appliances' AND inventory_item_id=item_id AND inventory_item_quantity > 0" );
        $stmt->execute( [] );

        while( $row = $stmt->fetch() ) {
          echo '<div class="product">';
          echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
          echo '<p>' . $row[ "item_description" ] . '</p>';
          echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
          echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
          echo '</div>';
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . '\n';
      }
      echo "</div><!-- /.content-wrapper -->";
    } else if( strcmp( $page, 'electronics') == 0 ) {
      /* query database for electronics */
      echo "<div class=\"content-wrapper\">";
      echo "<h1>" . $page . "</h1>";
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_type = 'electronics' AND inventory_item_id=item_id AND inventory_item_quantity > 0" );
        $stmt->execute( [] );

        while( $row = $stmt->fetch() ) {
          echo '<div class="product">';
          echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
          echo '<p>' . $row[ "item_description" ] . '</p>';
          echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
          echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
          echo '</div>';
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . '\n';
      }
      echo "</div><!-- /.content-wrapper -->";
    } else if( strcmp( $page, 'camping' ) == 0 ) {
      /* query database for camping material */
      echo "<div class=\"content-wrapper\">";
      echo "<h1>" . $page . "</h1>";
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_type = 'camping' AND inventory_item_id=item_id AND inventory_item_quantity > 0" );
        $stmt->execute( [] );

        while( $row = $stmt->fetch() ) {
          echo '<div class="product">';
          echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
          echo '<p>' . $row[ "item_description" ] . '</p>';
          echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
          echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
          echo '</div>';
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . '\n';
      }
      echo "</div><!-- /.content-wrapper -->";
    } else if( strcmp( $page, 'sports' ) == 0 ) {
      /* query database for sports material */
      echo "<div class=\"content-wrapper\">";
      echo "<h1>" . $page . "</h1>";
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_type = 'sports' AND inventory_item_id=item_id AND inventory_item_quantity > 0" );
        $stmt->execute( [] );

        while( $row = $stmt->fetch() ) {
          echo '<div class="product">';
          echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
          echo '<p>' . $row[ "item_description" ] . '</p>';
          echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
          echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
          echo '</div>';
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . '\n';
      }
      echo "</div><!-- /.content-wrapper -->";
    } else if( strcmp( $page, 'school' ) == 0 ) {
      /* query database for school stuff */
      echo "<div class=\"content-wrapper\">";
      echo "<h1>" . $page . "</h1>";
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_type = 'school' AND inventory_item_id=item_id AND inventory_item_quantity > 0" );
        $stmt->execute( [] );

        while( $row = $stmt->fetch() ) {
          echo '<div class="product">';
          echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
          echo '<p>' . $row[ "item_description" ] . '</p>';
          echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
          echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
          echo '</div>';
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . '\n';
      }
      echo "</div><!-- /.content-wrapper -->";
    } else if( strcmp( $page, 'books' ) == 0 ) {
      /* query database for books */
      echo "<div class=\"content-wrapper\">";
      echo "<h1>" . $page . "</h1>";
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );
        $stmt = $pdo->prepare( "SELECT * FROM item,inventory WHERE item_type = 'books' AND inventory_item_id=item_id AND inventory_item_quantity > 0" );
        $stmt->execute( [] );

        while( $row = $stmt->fetch() ) {
          echo '<div class="product">';
          echo '<a href="product.php?q=' . $row[ "item_id" ] . '">'. $row[ "item_Title" ] . '</a><br />';
          echo '<p>' . $row[ "item_description" ] . '</p>';
          echo '<small>Quantity: ' . $row[ "inventory_item_quantity" ] . '</small><br />';
          echo '<small>Price: $' . $row[ "item_price" ] . ' USD</small><br />';
          echo '</div>';
        }
      } catch( PDOException $ex ) {
        echo 'PDOException: ' . $ex->getMessage( ) . '\n';
      }
      echo "</div><!-- /.content-wrapper -->";
    }
?>
  <div id="footer" class="pure-g">
    <div class="pure-u-1-3">
      <a href="#" class="footer-link">Customer Relations</a>
      <a href="#" class="footer-link">Enterprise Solutions</a>
      <a href="#" class="footer-link">Trade Agreements</a>
      <a href="#" class="footer-link">Press Releases</a>
    </div><!-- /.pure-u-1-3 -->
    <div class="pure-u-1-3">
      <a href="#" class="footer-link">About Us</a>
      <a href="#" class="footer-link">Contact</a>
      <a href="#" class="footer-link">Privacy Policy</a>
      <a href="#" class="footer-link">Terms of Service</a>
      <a href="#" class="footer-link">End-user License Agreement</a>
    </div><!-- /.pure-u-1-3 -->
    <div class="pure-u-1-3">
      <a href="#" class="footer-link">
        <i class="fa fa-facebook-official"></i> Facebook
      </a><a href="#" class="footer-link">
        <i class="fa fa-twitter"></i> Twitter
      </a>
    </div><!-- /.pure-u-1-3 -->
  </div><!-- /#footer -->

  <!-- JQuery -->
  <script type="text/javascript"
  src="https://code.jquery.com/jquery-1.7.2.min.js">
  </script>
  <script type="text/javascript"
  src="https://code.jquery.com/jquery-migrate-1.2.1.min.js">
  </script>

  <!-- Slick Carousel -->
  <script type="text/javascript"
src="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js">
  </script>

  <script type="text/javascript">
    $( document ).on( 'ready', function( ) {
      $( '#masthead' ).slick( {
        arrows: false,
        autoplay: true
      } );
    } );
  </script>
</body></html>
<!--
vim: number ts=2 sw=2 expandtab tw=76
-->
