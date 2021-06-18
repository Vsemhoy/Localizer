<?php
if (session_status() === PHP_SESSION_NONE) {
  session_name("localizator");
  session_start();
}
$configpath = "connect/mainconfig.php";

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
    require_once $configpath;
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
        $PAGE = "index";
    } else if ($pager == "localization"){
        $PAGE = "localization";
    } else if ($pager == "langs"){
        $PAGE = "languages";
    } else if ($pager == "autofill"){
        $PAGE = "autofill";
    } else if ($pager == "users"){
        $PAGE = "users";
    } else if ($pager == "sqlconnect"){
        $PAGE = "connect";
    } else if ($pager == "login"){
        $PAGE = "login";
    } else {
        $PAGE = "login";
    };
} else {
    $PAGE = "EMPTY";
}

if (!file_exists($configpath)){
    $PAGE = "CONFIG OFF";
    $CONNECTED = 0;
} else {
    $CONNECTED = 1;
};

if (isset($_POST["sender"])){
    #1 HARVEST INFO
    $username  = strip_tags( $_POST['username'] );
    $username  = htmlentities($username, ENT_QUOTES);
    $username  = preg_replace('/\s+/', ' ', $username);
    $userpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userpass = str_replace("$", "%", $userpass);
    $dbname = EraseQuotes(strip_tags( $_POST["dbname"] ));
    $dbuser = EraseQuotes(strip_tags( $_POST["dbuser"] ));
    $localhost = EraseQuotes(strip_tags( $_POST["localhost"] ));
    $dbpass = EraseQuotes(strip_tags( $_POST["dbpass"] ));
    $lang = EraseQuotes(strip_tags( $_POST["language"] ));
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
    $file   = fopen($configpath, 'w');
    fputs($file,$configfile);
    fclose($file);
    #3 ASSIGN SESSION
    $_SESSION["LC_user"] = $username;
    $_SESSION["LC_pass"] = $userpass;
    $_SESSION["LC_lang"] = $lang;
    unset($_POST["sender"]);
    header("Location: index.php");
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
  width: calc(100%-250px);
  padding-left: 22px;
}
#maincontent.mc-large {
  width: calc(100%-50px);
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
        <a class="navbar-item" href="https://bulma.io">
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
          <a class="navbar-item">
            Connect SQL
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
              <h1 class="title is-2 is-spaced">
                <?php echo $PAGE; ?>
              </h1>
              <h2 class="subtitle is-4">
                  Congratulations! You're running the <strong>Bulma start</strong> project.
                  <br>
                  It includes everything you need to <strong>build your own website</strong> with Bulma.
              </h2>
            </div>
          </div>
        </div>
      

      <div class="container">
        <div class="columns">
          <div class="column column is-12-desktop">
            <div class="content">
              <h3>What's included</h3>
              <p>
                The <code>npm</code> dependencies included in <code>package.json</code> are:
              </p>
              <ul>
                <li>
                  <code><a href="https://github.com/jgthms/bulma">bulma</a></code>
                </li>
                <li>
                  <code><a href="https://github.com/sass/node-sass">node-sass</a></code> to compile your own Sass file
                </li>
                <li>
                  <code><a href="https://github.com/postcss/postcss-cli">postcss-cli</a></code> and <code><a href="https://github.com/postcss/autoprefixer">autoprefixer</a></code> to add support for older browsers
                </li>
                <li>
                  <code><a href="https://babeljs.io/docs/usage/cli/">babel-cli</a></code>,
                  <code><a href="https://github.com/babel/babel-preset-env">babel-preset-env</a></code>
                  and
                  <code><a href="https://github.com/jmcriffey/babel-preset-es2015-ie">babel-preset-es2015-ie</a></code>
                  for compiling ES6 JavaScript files
                </li>
              </ul>
              <p>
                Apart from <code>package.json</code>, the following files are included:
              </p>
              <ul>
                <li>
                  <code>.babelrc</code> configuration file for <a href="https://babeljs.io/">Babel</a>
                </li>
                <li>
                  <code>.gitignore</code> common <a href="https://git-scm.com/">Git</a> ignored files
                </li>
                <li>
                  <code>index.html</code> this HTML5 file
                </li>
                <li>
                  <code>_sass/main.scss</code> a basic SCSS file that <strong>imports Bulma</strong> and explains how to <strong>customize</strong> your styles, and compiles to <code>css/main.css</code>
                </li>
                <li>
                  <code>_javascript/main.js</code> an ES6 JavaScript that compiles to <code>lib/main.js</code>
                </li>
              </ul>
              <h3>Try it out!</h3>
              <p>
                To see if your setup works, follow these steps:
              </p>
              <ol>
                <li>
                  <p>
                    <strong>Move</strong> to this directory:
                    <br>
                    <pre><code>cd bulma-start</code></pre>
                  </p>
                </li>
                <li>
                  <p>
                    <strong>Install</strong> all dependencies:
                    <br>
                    <pre><code>npm install</code></pre>
                  </p>
                </li>
                <li>
                  <p>
                    <strong>Start</strong> the CSS and JS watchers:
                    <br>
                    <pre><code>npm start</code></pre>
                  </p>
                </li>
                <li>
                  <p>
                    <strong>Edit</strong> <code>_sass/main.scss</code> by adding the following rule at the <strong>end</strong> of the file:
                    <br>
                    <pre><span style="color: #22863a;">html</span> {
  <span style="color: #005cc5;"><span style="color: #005cc5;">background-color</span></span>: <span style="color: #24292e">$green</span>;
}</pre>
                  </p>
                </li>
              </ol>
              <p>
                The page background should turn <strong class="has-text-success">green</strong>!
              </p>
              <h3>Get started</h3>
              <p>
                You're <strong>ready</strong> to create your own designs with Bulma. Just edit or duplicate this page, or simply create a new one!
                <br>
                For example, this page is <strong>only</strong> built with the following <strong>Bulma elements</strong>:
              </p>
            </div>
            <table class="table is-bordered is-fullwidth">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>CSS class</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>Columns</th>
                  <td>
                    <code><a href="http://bulma.io/documentation/columns/basics">columns</a></code>
                    <code><a href="http://bulma.io/documentation/columns/basics">column</a></code>
                  </td>
                </tr>
                <tr>
                  <th>Layout</th>
                  <td>
                    <code><a href="http://bulma.io/documentation/layout/section">section</a></code>
                    <code><a href="http://bulma.io/documentation/layout/container">container</a></code>
                    <code><a href="http://bulma.io/documentation/layout/footer">footer</a></code>
                  </td>
                </tr>
                <tr>
                  <th>Elements</th>
                  <td>
                    <code><a href="http://bulma.io/documentation/elements/button">button</a></code>
                    <code><a href="http://bulma.io/documentation/elements/content">content</a></code>
                    <code><a href="http://bulma.io/documentation/elements/title">title</a></code>
                    <code><a href="http://bulma.io/documentation/elements/title">subtitle</a></code>
                  </td>
                </tr>
                <tr>
                  <th>Form</th>
                  <td>
                    <code><a href="http://bulma.io/documentation/form/general">field</a></code>
                    <code><a href="http://bulma.io/documentation/form/general">control</a></code>
                  </td>
                </tr>
                <tr>
                  <th>Helpers</th>
                  <td>
                    <code><a href="http://bulma.io/documentation/modifiers/typography-helpers/">has-text-centered</a></code>
                    <code><a href="http://bulma.io/documentation/modifiers/typography-helpers/">has-text-weight-semibold</a></code>
                  </td>
                </tr>
              </tbody>
            </table>
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
  <div class="column is-half">
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
Настоящее программное обеспечение разработано на основе лицензии GNU GPL.V3, любое копирование, изменение 
и передача другим лицам допускаются.
Пользователь производит использование программы на свой страх и риск, разработчик не несёт ответственность
 за любой ущерб, нанесённый имуществу или информации пользователя по причине нецелевого использования 
 программы.
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
    <button class="button is-link is-light">Cancel</button>
  </div>
</div>
</form>

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
