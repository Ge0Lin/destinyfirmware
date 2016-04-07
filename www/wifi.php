<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$execute = $successmessage = $wificlients = $wifiname = $newwifiname = $wifipw = $newwifipw = $wifienc = $newwifienc = "";

$wificlients = test_input(shell_exec("sudo /root/show_wifi_clients.sh"));

// Get current WiFi details
$wifiname = test_input(shell_exec("sudo /sbin/uci get wireless.@wifi-iface[1].ssid"));
$wifipw = test_input(shell_exec("sudo /sbin/uci get wireless.@wifi-iface[1].key"));
$wifienc = test_input(shell_exec("sudo /sbin/uci get wireless.@wifi-iface[1].encryption"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
	$newwifiname = $_POST['wifiname'];
	$newwifipw = $_POST['wifipw'];
	$newwifienc = $_POST['wifienc'];
	if ($newwifiname != $wifiname) {
		// Change the WiFi name
		$successmessage = $successmessage . " WiFi name updated.";
		$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[1].ssid='$newwifiname'"));
		$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[0].ssid='$newwifiname'"));
		// Set the WiFi name to the new one, so we don't have to manually grab it again later on
		$wifiname = $newwifiname;
		}

	if ($newwifipw != $wifipw) {
		// Change the WiFi Password
		$successmessage = $successmessage . " WiFi password updated.";
		$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[1].key='$newwifipw'"));
		$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[0].key='$newwifipw'"));
		// Set the WiFi pass to the new one, so we don't have to manually grab it again later on
		$wifipw = $newwifipw;
		}
	
	if ($newwifienc != $wifienc) {
		// Change the WiFi Encryption
		if ($newwifienc == "wpa2") {
			$successmessage = $successmessage . " WiFi security turned on (Wise move!).";
			$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[1].encryption='$newwifienc'"));
			$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[0].encryption='$newwifienc'"));
			// Set the WiFi key to the new one, so we don't have to manually grab it again later on
			$wifienc = $newwifienc;
			}
		else {
			$successmessage = $successmessage . " WiFi security turned off (Is this wise?).";
			$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[1].encryption='$newwifienc'"));
			$execute = test_input(shell_exec("sudo /sbin/uci set wireless.@wifi-iface[0].encryption='$newwifienc'"));
			// Set the WiFi key to the new one, so we don't have to manually grab it again later on
			$wifienc = $newwifienc;
			}
		}
	// Finish up by comitting changes:
	$execute = test_input(shell_exec("sudo /sbin/uci commit wireless"));
	$execute = test_input(shell_exec("sudo /sbin/wifi"));
        }


?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Settings have been saved. " . $successmessage . "</h2>
  </div>";
}
?>

<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Basic Details</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="text" id="wifiname" name="wifiname" value='<?php echo $wifiname; ?>'>
    <label class="mdl-textfield__label" for="wifiname">My WiFi Name</label>
   </div>
   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="text" id="wifipw" name="wifipw" value='<?php echo $wifipw; ?>'>
    <label class="mdl-textfield__label" for="internetpw">My Easy WiFi Password</label>
   </div>
<br />
<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
  <input type="radio" id="option-1" class="mdl-radio__button" name="wifienc" value="wpa2" <?php if ($wifienc=='wpa2') { echo 'checked'; } ?>>
  <span class="mdl-radio__label">Very secure - WPA2</span>
</label><br />
<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
  <input type="radio" id="option-2" class="mdl-radio__button" name="wifienc" value="none" <?php if ($wifienc=='none') { echo 'checked'; } ?>>
  <span class="mdl-radio__label">No security - Open</span>
</label>
<br /><br />
<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Save settings</button>
        </form>
</div>
 </div>

<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
	<div class="mdl-card__title mdl-color--teal-300">
	<h2 class="mdl-card__title-text">Connected clients</h2>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--grey-600">
	<?php echo "<pre>" . $wificlients . "</pre>"; ?>
	</div>
</div>

</div>
 </div>
</div>
<?php
include 'tpl/footer.php';
?>
