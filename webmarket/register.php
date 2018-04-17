  <?php
    if( $_POST && isset( $_POST[ "submitted" ] ) ) {
      try {
        $pdo = new PDO( "mysql:host=localhost;dbname=webmarket_database", "root", "password" );

        if( !strcmp( $_POST[ "submitted" ], "true" ) ) {
          /* var_dump( $_POST ); */
          $stmt = $pdo->prepare( "SELECT count(*) FROM user WHERE STRCMP(user_email,:email)=0" );
          $stmt->execute([ "email" => $_POST[ "email" ] ]);
          $count = $stmt->fetch( )[ "count(*)" ];
          if( $count == 0 ) {
            $stmt = $pdo->prepare( "INSERT INTO user(user_first_name, user_last_name, user_email, user_phone_number, user_password, user_payment_id) "
              ."VALUES ( :fname, :lname, :email, :ph, :passwd, 0 );" );
            $stmt->execute( [
              'fname' => $_POST[ "fname" ],
              'lname' => $_POST[ "lname" ],
              'email' => $_POST[ "email" ],
              'ph' => $_POST[ "phone" ],
              'passwd' => $_POST[ "passwd" ]
            ] );

            header( "location: index.php" );
          } else {
            echo "<p>E-mail already registered</p>";
          }
        }
      } catch ( PDOEXception $ex ) {
        echo "PDOException: " . $ex->getMessage( ) . "<br />";
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
      <li class="pure-menu-item"><a href="login.php" class="pure-menu-link">
        Login
      </a></li>
      <li class="pure-menu-item"><a href="register.php" class="pure-menu-link">
        Register
      </a></li>
    </ul><!-- /.pure-menu-list -->
    </div><!-- /.pure-u-1.pure-u-md-1-3 -->
    </div><!-- /.pure-g -->
  </div><!-- /.pure-menu -->


  <div class="thin-wrapper">
    <form id="regform" name="regform" class="pure-form registration-form" method="post" action="">
      <h1>Registration</h1>
      <div class="pure-g">
        <div class="pure-u-1-4 fieldset-spacing">
          <label>First Name:</label>
        </div><!-- /.pure-1-4 -->
        <div class="pure-u-3-4 fieldset-spacing">
          <input id="fname" name="fname" type="text" class="pure-input-rounded" placeholder="First name" />
        </div><!-- /.pure-3-4 -->
        <div class="pure-u-1-4 fieldset-spacing">
          <label>Last Name:</label>
        </div><!-- /.pure-u-1-4 -->
        <div class="pure-u-3-4 fieldset-spacing">
          <input id="lname" name="lname" type="text" class="pure-input-rounded" placeholder="Last name" />
        </div><!-- /.pure-u-3-4 -->
        <div class="pure-u-1-4 fieldset-spacing">
          <label>E-mail address:</label>
        </div><!-- /.pure-u-1-4 -->
        <div class="pure-u-3-4 fieldset-spacing">
          <input id="email" name="email" type="text" class="pure-input-rounded" placeholder="E-mail Address" />
        </div><!-- /.pure-u-3-4 -->
        <div class="pure-u-1-4 fieldset-spacing">
          <label>Phone Number:</label>
        </div><!-- /.pure-u-1-4 -->
        <div class="pure-u-3-4 fieldset-spacing">
          <input id="phone" name="phone" type="text" class="pure-input-rounded" />
        </div><!-- /.pure-u-3-4 -->
        <div class="pure-u-1-4 fieldset-spacing">
          <label>Password:</label>
        </div><!-- /.pure-u-1-4 -->
        <div class="pure-u-3-4 fieldset-spacing">
          <input id="passwd" name="passwd" type="password" class="pure-input-rounded" />
        </div><!-- /.pure-u-3-4 -->
        <div class="pure-u-1">
          <input type="submit" value="Register" class="pure-button" style="background: rgb(28, 184, 65);color:#fff"/>
          <input type="reset" value="Clear Form" class="pure-button" style="background: rgb(202, 60, 60);color:#fff"/>
        </div><!-- /.pure-u-1 -->
      </div><!-- /.pure-g -->
      <input type="hidden" id="submitted" name="submitted" value="true" />
    </form><!-- /.registration-form -->
  </div><!-- /.thin-wrapper -->

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

  <script type="application/javascript">
    function validate_string( str ) {
      var i;

      for( i = 0; i < str.length; ++i ) {
        if( ( str[i] < 'A' || str[i] > 'Z' ) && ( str[i] < 'a' || str[i] > 'z' ) )
          return false;
      }

      return true;
    }

    function validate( ) {
      var form = document.getElementById( "regform" );

      if( form != null ) {
        var fname = document.getElementById( "fname" ).value;
        var lname = document.getElementById( "lname" ).value;
        var email = document.getElementById( "email" ).value;
        var passwd = document.getElementById( "passwd" ).value;
        var i;

        if( validate_string( fname ) == false ) {
          alert( "Your first name is invalid" );
          return false;
        }

        if( validate_string( lname ) == false ) {
          alert( "Your last name is invalid" );
          return false;
        }

        /* validate e-mail */
        for( i = 0; i < email.length; ++i ) {
          if( ( email[i] >= ' ' && email[i] <= '-' ) ||
              ( email[i] == '/' ) ||
              ( email[i] >= ':' && email[i] <= '?' ) ||
              ( email[i] >= '[' && email[i] <= '`' ) ||
              ( email[i] == '~' ) ) {
            alert( "Invalid e-mail address!" );
            return false;
          }
        }

        if( passwd.length < 8 ) {
          alert( "Weak password entropy" );
          return false;
        }

        return true;
      }

      alert( "Couldn't validate form" );
      return false;
    }

    document.getElementById( "regform" ).onsubmit = validate;
  </script>
</body></html>
<!--
vim: number ts=2 sw=2 expandtab tw=76
-->
