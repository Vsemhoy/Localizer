<?php
if (session_status() === PHP_SESSION_NONE) {
  session_name("localizator");
  session_start();
}
define('LOCALIZER', 1);

require_once "core/defines.php";


function EraseQuotes($string) {
    str_replace('"', "", $string);
    str_replace("'", "", $string);
    return $string;
};

# ------------- LOGOUT HANDLE --------------------
if (isset($_GET["action"]) && $_GET["action"] == "logout"){
    session_destroy();
    header("Location: index.php?action=login");
};

# ------------- LOGIN HANDLE --------------------
if (isset($_POST["login"])){
    $username  = strip_tags( $_POST['username'] );
    $username  = htmlentities($username, ENT_QUOTES);
    $username  = preg_replace('/\s+/', ' ', $username);
    $userpass = $_POST['password'];
    require_once PATH_CONFIG;
    $cpas = str_replace("%", "$", Config::$PAS);

    if ((Config::$USR == $username)){
      if (password_verify($userpass, $cpas)) {
        $_SESSION["LC_user"] = $username;
        $_SESSION["LC_pass"] = $userpass;
        $_SESSION["LC_lang"] = Config::$LNG;
        unset($_POST["login"]);
        header("Location: index.php");
      };
    };
};



if (isset($_GET["page"])){
    $pager = $_GET["page"];
    if ($pager == "index"){
        $PAGE = "main";
    } else if ($pager == "localization"){
        $PAGE = "localization";
    } else if ($pager == "langs"){
        $PAGE = "languages";
    } else if ($pager == "autofill"){
        $PAGE = "autofill";
    } else if ($pager == "users"){
        $PAGE = "users";
    } else if ($pager == "dbset"){
        $PAGE = "dbset";
    } else if ($pager == "login"){
        $PAGE = "main";
    } else {
        $PAGE = "login";
    };
} else {
    $PAGE = "EMPTY";
}

if (!file_exists(PATH_CONFIG)){
    $PAGE = "CONFIG OFF";
    $CONNECTED = 0;
} else {
    require_once PATH_CONFIG;
    require_once PATH_DBCONNECT;
    $CONNECTED = 1;
};

if (isset($_POST["sender"])){
    #1 HARVEST INFO
    if (isset($_POST['username'])){
        $username  = strip_tags( $_POST['username'] );
        $username  = htmlentities($username, ENT_QUOTES);
        $username  = preg_replace('/\s+/', ' ', $username);
        $userpass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $userpass  = str_replace("$", "%", $userpass);
        $lang      = EraseQuotes(strip_tags( $_POST["language"] ));
    } else {
        $username = Config::$USR;
        $userpass = Config::$PAS;
        $lang     = Config::$LNG;
    }

    $dbname = EraseQuotes(strip_tags( $_POST["dbname"] ));
    $dbuser = EraseQuotes(strip_tags( $_POST["dbuser"] ));
    $localhost = EraseQuotes(strip_tags( $_POST["localhost"] ));
    $dbpass = EraseQuotes(strip_tags( $_POST["dbpass"] ));
    $ttime = date('l jS \of F Y h:i:s A');
    $configfile = '<' . '?php 
    /* file generated on ' . $ttime . ' */ 
class Config {
    public static $USR = "' . $username . '";' . ' 
    public static $PAS = "' . $userpass . '";' . ' 
    public static $DB =  "' . $dbname . '";' . ' 
    public static $HS =  "' . $localhost . '";' . ' 
    public static $US =  "' . $dbuser . '";' . ' 
    public static $PS =  "' . $dbpass . '";' . '
    public static $LNG = "' . $lang . '";
};
?>';
    #2 CREATE FILE
    //$result = json_encode($RESULT, JSON_UNESCAPED_UNICODE);
    $file   = fopen(PATH_CONFIG, 'w');
    fputs($file,$configfile);
    fclose($file);
    #3 ASSIGN SESSION
    $_SESSION["LC_user"] = $username;
    $_SESSION["LC_pass"] = $userpass;
    $_SESSION["LC_lang"] = $lang;
    unset($_POST["sender"]);
    header("Location: index.php?page=dbset");
};


?>

<?php if ($CONNECTED === 1): 
    if (isset($_SESSION["LC_user"]) && isset($_SESSION["LC_pass"])): ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My first Bulma website</title>
    <link rel="stylesheet" href="vendors/bulma/css/bulma-docs.min.css">
    <link rel="stylesheet" href="vendors/bulma/css/main.css">
    <link rel="stylesheet" href="vendors/fontawesome/css/all.css">
    <script src="vendors/jquery/jquery-3.6.0.min.js"></script>
    <style>
.button {
  border-radius: 1px;
}
.bd-navbar-item {
  text-transform: uppercase;
}
.bd-navbar-item {
  -webkit-transition: all 0.3s;
  transition: all 0.3s;
}
.bd-navbar-item:hover {
box-shadow: 0px 0px 2px rgb(0 0 0 / 30%);
}
.bd-navbar-item.is-active {
  border: 1px solid #ebfffc;
  background-color: #ebfffc !important;
}
.navbar {
  box-shadow: 1px 0px 6px grey;
  z-index: 1;
  position: relative;
  padding: 0px;
  margin: 0px;
}
#sidemenu {
  width: 250px;
  min-height: 100vw;
  background: #f2f2f2;
  float: left;
  z-index: 10;
  position: relative;
  box-shadow: 6px 2px 3px rgb(0 0 0 / 30%);
   -webkit-transition: all 0.3s;
  transition: all 0.3s;
}
#sidemenu.sm-small {
  width: 50px;
  position: absolute;
  overflow: hidden;
}
#sidemenu.hovered {
  width: 250px;
  
}
#maincontent {
  width: calc(100% - 250px);
  margin-left: 250px;
 /* padding-left: 22px; */
 padding: 22px;
}
#maincontent .container {
    width: 100% !important;
}
#maincontent.mc-large {
  width: calc(100% - 50px);
  float: left;
  margin-left: 50px;
}

#maincontent.hovered {
}
#sidemenu.sm-small span {
  display: none;
}
#sidemenu.sm-small:hover span {
  display: inline-flex;
}

#sidemenu .shorts {
  float: right;
  display: inline-flex;
}
#sidemenu.sm-small .shorts {
  display: inline-flex;
  float: none;
}
#sidemenu.sm-small:hover .shorts {
  display: inline-flex;
  float: right;
}
.sidebar-trigger {
  width: 50px;
  font-size: larger;
}
.content-body {
  width: 100%;
  padding-top: 22px;
  padding-bottom: 22px;
}
    </style>
  </head>
  <body>
    <nav id="navbar" class="bd-navbar navbar">
      <a class="navbar-icon navbar-item sidebar-trigger" onclick="toggleside()"><i class="fas fa-bars"></i></a>
      <div class="navbar-brand">
        <a class="navbar-item" href="index.php?page=index">
      <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: Free, open source, and modern CSS framework based on Flexbox" width="112" height="28">
        </a>
      </div>
      <div id="topMenu" class="navbar-menu">
        <div class="navbar-start bd-navbar-start bd-is-original">
          <a class="navbar-item bd-navbar-item <?php if ($PAGE == "localization"){ echo "is-active"; };?>" href="index.php?page=localization">Localizations</a>
          <a class="navbar-item bd-navbar-item <?php if ($PAGE == "languages"){ echo "is-active"; };?>" href="index.php?page=langs">Languages</a>
          <a class="navbar-item bd-navbar-item <?php if ($PAGE == "autofill"){ echo "is-active"; };?>" href="index.php?page=autofill">Autofillers</a>
        </div>
      </div>
      <div class="navbar-end">
        <input type="text" class="input is-normal mt-2 mb-2 mr-2" placeholder="search"/>
        <a class="navbar-icon navbar-item" href="lolo.com">IO</a>
        <a class="navbar-icon navbar-item" href="lolo.com">2O</a>
        <a class="navbar-icon navbar-item" href="lolo.com">3O</a>
         <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          AUTH
        </a>

        <div class="navbar-dropdown is-right">
          <a class="navbar-item">
            Manage users
          </a>
          <a class="navbar-item" href="index.php?page=dbset">
          <span class="icon-text">
  <span>Connect SQL  <span class="icon">
    <i class="fas fa-circle <?php
    if (isset($db_errno)){
        echo "has-text-danger";
    } else {
        echo "has-text-primary";
    };  ?>"></i>
  </span></span>
</span>
            
          </a>
          <a class="navbar-item">
            Contact
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item" href="index.php?action=logout">
            Logout
          </a>
        </div>
      </div>
    </div>
      </div>
    </nav>
    <header></header>
    <section id="sidemenu">
      <input class="input is-small" type="text" placeholder="Small input">
<aside class="menu">
  <p class="menu-label pl-3 pt-3">
    Location
  </p>
  <ul class="menu-list">
    <li><a><span class="icon-text">
  <span class="icon">
    <i class="fas fa-home"></i>
  </span>
  <span>Home</span>
</span><span class="shorts">US</span>
</a></li>
    <li><a>
        <span class="icon">
    <i class="fas fa-home"></i>
  </span>
  <span>Customers</span>
</span><span class="shorts">RU</span>
    </a></li>
        
    <li><a>  
  <span>Site</span>
</span><span class="shorts icon">
    <i class="fas fa-home"></i>
  </span>
    </a></li>
      <li><a>  
  <span>Admin-Panel</span>
</span><span class="shorts icon">
    <i class="fas fa-home"></i>
  </span>
    </a></li>
      
  </ul>
  <p class="menu-label pl-3 pt-3">
    Tag filters
  </p>
<div class="tagflow">
  <button class="button is-primary is-light">Primary</button>
  <button class="button is-link is-light">Link</button>
  <button class="button is-info is-light">Info</button>
  <button class="button is-success is-light">Success</button>
  <button class="button is-warning is-light">Warning</button>
  <button class="button is-danger is-light">Danger</button>
</div>
</aside>
      </section>


    <section id="maincontent" class=" is-large is-primary">
      <div class="content-body ">
        <div class="container">
          <div class="columns">
            <div class="column is-12-desktop">
<?php if (isset($db_errno)): ?>
<article class="message is-danger">
  <div class="message-header">
    <p>Database connection ERROR <?php echo $db_errno; ?>: <?php
    if ($db_errno == 1045){
        echo "'wrong username/password";
    } else if ($db_errno == 2002){
        echo "'wrong localhost'";
    } else if ($db_errno == 1049){
        echo "'wrong database name'";
    }; ?></p>
    <button class="delete" aria-label="delete"></button>
  </div>
  <div class="message-body">
    <?php echo $db_errmsg; ?>
    <br>
    <span class="has-text-link-dark">To solve this problem, try to change your database connection settings <a href="index.php?page=dbset&err=<?php
    echo $db_errno; ?>">here.</a></span>
  </div>
</article>
<?php endif; ?>



            
      <!--        <h2 class="subtitle is-4">
                  Congratulations! You're running the <strong>Bulma start</strong> project.
                  <br>
                  It includes everything you need to <strong>build your own website</strong> with Bulma.
              </h2>

            <div class="content"> -->
<?php #-------------------------------------------------------------#



require_once PATH_COMPONENTS . DS . $PAGE . DS . "index.php";
?>
            </div>
            <div class="content">
              <p>
                If you want to <strong>learn more</strong>, follow these links:
              </p>
            </div>
            <div class="field is-grouped">
              <p class="control">
                <a class="button is-medium is-primary" href="http://bulma.io">
                  <strong class="has-text-weight-semibold">Bulma homepage</strong>
                </a>
              </p>
              <p class="control">
                <a class="button is-medium is-link" href="http://bulma.io/documentation/overview/start/">
                  <strong class="has-text-weight-semibold">Documentation</strong>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
</div>
    </section>
    <footer class="footer has-text-centered">
      <div class="container">
         <div class="columns">
          <div class="column is-8-desktop is-offset-2-desktop">
            <p>
              <strong class="has-text-weight-semibold">
                <a href="https://www.npmjs.com/package/bulma-start">bulma-start@0.0.4</a>
              </strong>
            </p>
            <p>
              <small>
                Source code licensed <a href="http://opensource.org/licenses/mit-license.php">MIT</a>
              </small>
            </p>
            <p style="margin-top: 1rem;">
              <a href="http://bulma.io">
                <img src="made-with-bulma.png" alt="Made with Bulma" width="128" height="24">
              </a>
            </p>
          </div>
        </div>
      </div>
    </footer>
    <script type="text/javascript" src="lib/main.js"></script>
    <script>

function sideHeight(){
    let navHeight = $( "#navbar" ).height();
  let docHeight = $( document ).height();
  let heihtTo = docHeight - navHeight;
  $( "#sidemenu" ).css("height", heihtTo + "px");
}

$( window ).resize( function(){
  sideHeight()
});
$( document ).ready( function(){
  sideHeight()
});

function toggleside(){
  $( "#sidemenu" ).toggleClass("sm-small");
    $( "#maincontent" ).toggleClass("mc-large");
};

$( "#sidemenu" ).hover(function(){ if($(this).hasClass("sm-small")){
  $( "#sidemenu" ).addClass("hovered");
    $( "#maincontent" ).addClass("hovered");
  } else {
    $( "#sidemenu" ).removeClass("hovered");   
    $( "#maincontent" ).removeClass("hovered");
  };
});

$( "#sidemenu" ).mouseleave(function(){
      $( "#sidemenu" ).removeClass("hovered");
  $( "#maincontent" ).removeClass("hovered");
});
    </script>
  </body>
</html>
<?php 
// ---------------- if session empty -------------
elseif (isset($_GET["action"]) && ($_GET["action"] == "login")):
    unset($_GET["action"]);
?>
    <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My first Bulma website</title>
    <link rel="stylesheet" href="vendors/bulma/css/bulma-docs.min.css">
    <link rel="stylesheet" href="vendors/bulma/css/main.css">
    <link rel="stylesheet" href="vendors/fontawesome/css/all.css">
    <script src="vendors/jquery/jquery-3.6.0.min.js"></script>
    <style>
.button {
  border-radius: 6px;
}
    </style>
  </head>
  <body>
  <div class="container is-min-desktop">
  <div class="columns">
  <div class="column"></div>
  <div class="column is-half mt-6">
  <div class="notification has-background-primary-light ">
    <p class='is-size-4'>Hello <?php if (isset($username)): echo $username; else: echo "Stranger"; endif; ?>!</p>

    <form method="POST" location="index.php">
    <div class="card">
  <div class="card-content">
    <div class="content">

    <div class="field">
  <label class="label">Usename</label>
  <div class="control">
    <input name="username" class="input" type="text" placeholder="SuperUser" value="<?php
    if (isset($_POST["username"])){echo $_POST["username"];}; ?>">
  </div>
</div>

<div class="field">
<label class="label">Password</label>
  <p class="control has-icons-left">
    <input class="input" name="password" type="password" placeholder="Password" value="<?php
    if (isset($_POST["password"])){echo $_POST["password"];}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>

<div class="control">
    <button type="submit" name="login" class="button is-link">Submit</button>
  </div>

    </div>
  </div>
</div>
</form>
  </div></div>
  <div class="column"></div>
</div>
</div>

  </body>
</html>
<?php
endif;
// ---------------- if config file don't exists --    
else: ?>

    <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My first Bulma website</title>
    <link rel="stylesheet" href="vendors/bulma/css/bulma-docs.min.css">
    <link rel="stylesheet" href="vendors/bulma/css/main.css">
    <link rel="stylesheet" href="vendors/fontawesome/css/all.css">
    <script src="vendors/jquery/jquery-3.6.0.min.js"></script>
    <style>
.button {
  border-radius: 1px;
}
#terms {
  
}
.hide {
    display: none;
}
    </style>
  </head>
  <body>
  <div class="container p-6">
  <p class="is-size-4">Main Configuration Setup</p>
  <div class="notification is-primary">
<form method="POST" location="index.php">
  <div class="field">
  <label class="label">Language</label>
  <div class="control">
    <div class="select">
      <select id="langselect" name="language">
        <option value="1">EN</option>
        <option value="2">ES</option>
      </select>
    </div>
  </div>
</div>

<p class="is-size-4">UserData</p>

<div class="field">
  <label class="label">Usename</label>
  <div class="control">
    <input name="username" class="input" type="text" placeholder="SuperUser" value="<?php
    if (isset($_POST["username"])){echo $_POST["username"];}; ?>">
  </div>
</div>

<div class="field">
<label class="label">Password</label>
  <p class="control has-icons-left">
    <input class="input" name="password" type="password" placeholder="Password" value="<?php
    if (isset($_POST["password"])){echo $_POST["password"];}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<hr>

<p class="is-size-4">DataBase</p>
<div class="field">
<label class="label">Localhost</label>
  <p class="control has-icons-left">
    <input name="localhost" class="input" type="text" placeholder="" value="<?php
    if (isset($_POST["localhost"])){echo $_POST["localhost"];}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
<label class="label">Database User</label>
  <p class="control has-icons-left">
    <input name="dbuser" class="input" type="text" placeholder="" value="<?php
    if (isset($_POST["dbuser"])){echo $_POST["dbuser"];}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
<label class="label">Database Name</label>
  <p class="control has-icons-left">
    <input name="dbname" class="input" type="text" placeholder="" value="<?php
    if (isset($_POST["dbname"])){echo $_POST["dbname"];}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
<label class="label">Password</label>
  <p class="control has-icons-left">
    <input name="dbpass" class="input" type="text" placeholder="" value="<?php
    if (isset($_POST["dbpass"])){echo $_POST["dbpass"];}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>


<div class="field">
  <div class="control">
    <label class="checkbox">
      <input type="checkbox">
      I agree to the <a onclick="showterms();" href="#terms">terms and conditions</a>
    </label>
  </div>
</div>

<div class="field hide has-background-info-light has-text-black-bis p-3" id="terms">
<h1>Terms and conditions</h1>
<p>
?????????????????? ?????????????????????? ?????????????????????? ?????????????????????? ???? ???????????? ???????????????? GNU GPL.V3, ?????????? ??????????????????????, ?????????????????? 
?? ???????????????? ???????????? ?????????? ??????????????????????.
???????????????????????? ???????????????????? ?????????????????????????? ?????????????????? ???? ???????? ?????????? ?? ????????, ?????????????????????? ???? ?????????? ??????????????????????????????
 ???? ?????????? ??????????, ???????????????????? ?????????????????? ?????? ???????????????????? ???????????????????????? ???? ?????????????? ???????????????????? ?????????????????????????? 
 ??????????????????.
</p>
</div>
<script>
function showterms(){
    $( "#terms" ).toggleClass("hide");
};
</script>
<div class="field is-grouped">
  <div class="control">
    <button type="submit" name="sender" class="button is-link">Submit</button>
  </div>
  <div class="control">
    <button onclick="clearinputs(); return false;" class="button is-link is-light">Clear</button>
  </div>
</div>
</form>
<script>
function clearinputs(){
    $( ".input" ).val("");
    return false;
};
</script>

  </div>
</div>

<script>
$("#langselect").change(function(){
    let lang = $("#langselect").val();
    alert(lang);
});
<?php if (isset($dbpass)): ?>
alert("<?php echo $configfile; ?>");
<?php endif; ?>
</script>
  </body>
</html>

<?php
endif;

//unset($_SESSION["LC_user"]);
?>
