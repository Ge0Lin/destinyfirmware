<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$successmessage = $originaldhcpstate = $dhcponoff = $currentrouterip = $routerip = $commituci = $commitlan = $commitcommand = $setrouterip = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
	$originaldhcpstate = test_input(shell_exec("sudo /sbin/uci get dhcp.lan.ignore"));
        $routerip = test_input($_POST["routerip"]);
	$currentrouterip = test_input(shell_exec("/sbin/uci get network.lan.ipaddr"));
	if ($currentrouterip != $routerip) {
	        $setrouterip = shell_exec("sudo /sbin/uci set network.lan.ipaddr='$routerip'");
        	$commituci = shell_exec("sudo /sbin/uci commit");
	        $commitlan = shell_exec("sudo /etc/init.d/network reload");
		$successmessage = $successmessage . " LAN IP address set to " . $routerip . ".";
		}
	if (test_input($_POST['dhcponoff']) == 'on') {
		$dhcponoff = 'on';
		$dhcptest = $_POST['dhcponoff'];
		$commitcommand = shell_exec("sudo /sbin/uci set dhcp.lan.ignore=0");
		$commitcommand = shell_exec("sudo /sbin/uci commit dhcp");
		$commitcommand = shell_exec("sudo /etc/init.d/dnsmasq restart");
		if ($originaldhcpstate == '1') {
			$successmessage = $successmessage . " DHCP is now enabled.";
			}
		}
	if (test_input($_POST['dhcponoff']) != 'on') {
		$dhcponoff = 'off';
		$dhcptest = $_POST['dhcponoff'];
		$commitcommand = shell_exec("sudo /sbin/uci set dhcp.lan.ignore=1");
		$commitcommand = shell_exec("sudo /sbin/uci commit dhcp");
		$commitcommand = shell_exec("sudo /etc/init.d/dnsmasq restart");
		if ($originaldhcpstate == '0') {
			$successmessage = $successmessage . " DHCP is now disabled.";
			}
		}
        }





// Load existing network details in to PHP variables for displaying
$routerip = test_input(shell_exec("sudo /sbin/uci get network.lan.ipaddr"));
$dhcponoff = test_input(shell_exec("sudo /sbin/uci get dhcp.lan.ignore"));
if ($dhcponoff == "1") {
	// We're turning DHCP Off
	$dhcponoff = "off";
	}
	else {
	$dhcponoff = "on";
	}


?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Settings have been saved!" . $successmessage . "</h2>
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
    <input class="mdl-textfield__input" type="text" id="routerip" name="routerip" value='<?php echo $routerip; ?>'>
    <label class="mdl-textfield__label" for="wifiname">Router LAN IP address</label>
   </div>
<br />
 <label for="dhcponoff" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
   <input type="checkbox" id="dhcponoff" name="dhcponoff" class="mdl-switch__input" <?php if ($dhcponoff!='off') { echo 'checked'; } ?>>
   <span class="mdl-switch__label">Enable DHCP Server</span>
 </label>

 <br /><br />
 <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Save settings</button>
         </form>

</div>
 </div>
</div>




 </div>
</div>
<?php
include 'tpl/footer.php';
?>
