<div class="container p-6">
<h1 class="title is-2 is-spaced">
    Database Setup
</h1>
<?php if (isset($db_errno)):
  echo '<p class="is-size-4 has-text-danger">Connection status: Not connected</p>';
else:
    echo '<p class="is-size-4 has-text-success">Connection status: OK</p>';
endif;
  ?>
  <div class="notification is-primary">
<form method="POST" location="index.php?page=dbset">


<p class="is-size-4">DataBase</p>
<div class="field">
<label class="label">Localhost</label>
  <p class="control has-icons-left">
    <input name="localhost" class="input 
    <?php if (isset($db_errno) && $db_errno == 2002){
        echo "is-danger is-focused";
    }; ?>
    " type="text" placeholder="" value="<?php
    if (isset(Config::$HS)){echo Config::$HS;}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
<label class="label">Database User</label>
  <p class="control has-icons-left">
    <input name="dbuser" class="input 
    <?php if (isset($db_errno) && $db_errno == 1045){
        echo "is-danger is-focused";
    }; ?>" type="text" placeholder="" value="<?php
    if (isset(Config::$US)){echo Config::$US;}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
<label class="label">Database Name</label>
  <p class="control has-icons-left">
    <input name="dbname" class="input
    <?php if (isset($db_errno) && $db_errno == 1049){
        echo "is-danger is-focused";
    }; ?>" type="text" placeholder="" value="<?php
    if (isset(Config::$DB)){echo Config::$DB;}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>
<div class="field">
<label class="label">Password</label>
  <p class="control has-icons-left">
    <input name="dbpass" class="input
    <?php if (isset($db_errno) && $db_errno == 1045){
        echo "is-danger is-focused";
    }; ?>" type="text" placeholder="" value="<?php
    if (isset(Config::$PS)){echo Config::$PS;}; ?>">
    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
  </p>
</div>


<div class="field is-grouped">
  <div class="control">
    <button type="submit" name="sender" class="button is-link">Connect</button>
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