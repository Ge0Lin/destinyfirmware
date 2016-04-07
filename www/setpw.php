<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$passwd = $setpw = $variablename = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
	$passwd = $_POST['passwd'];
	$setpw = test_input(shell_exec("sudo /usr/bin/passwd root -d '$passwd'"));
        }


?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>New password has been saved</h2>
  </div>";
}
?>


<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Set admin password</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="password" id="passwd" name="passwd" value=''>
    <label class="mdl-textfield__label" for="wifiname">Enter your new administrative password</label>
   </div>
<br /><br />
<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Save settings</button>
        </form>
</div>
 </div>




 </div>
</div>
<?php
include 'tpl/footer.php';
?>
