<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
// Seeing as this one is the First Run wizard, we'll just hide the nav menu if they haven't submitted anything
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include 'tpl/nav.php';
	}

include 'tpl/auth.php';

// Setup Variables
$variablename = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// This is where we are going to do a few things:
	// Allow web access and stop redirecting HTTP traffic now they've set an Admin password
	// Fix up the Error-404 page so it goes to the generic 'Hey you've been redirected here' page
        }


?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Settings have been saved! The menu to your left is now available.<br />
Word of advice: Start with 'Internet Access'</h2>
  </div>";
}
?>


<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Quick router setup</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">	
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	Please set an Admin password for your router to keep any un-wanted guests out.<br />
   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="text" id="adminpw" name="adminpw" value='SuperSecret'>
    <label class="mdl-textfield__label" for="internetpw">Make it easy to remember!</label>
   </div>

<br />Please also set a WiFi password to make your WiFi secure and keep your neighbors out!<br />
   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="text" id="wifipw" name="wifipw" value='EasyToRemember'>
    <label class="mdl-textfield__label" for="internetpw">Make it at least 8-characters long</label>
   </div>
<br />You can change your WiFi name later on once we're finished here.<br />
<br /><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Save settings</button>
  </div>
</div>




 </div>
</div>
<?php
include 'tpl/footer.php';
?>
