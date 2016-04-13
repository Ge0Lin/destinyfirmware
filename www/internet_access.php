<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$trimpingtime = $pingtime = $configfilestatus = $networkconfigfile = $networkconfigfilecontents = $lanip = $lanmac = $wanmac = $isconnected = $wanifname = $wanip = $internetusername = $internetpw = $internetvlan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $internetusername = test_input($_POST["internetusername"]);
        $internetpw = test_input($_POST["internetpw"]);
        $internetvlan = test_input($_POST["internetvlan"]);

	if (strlen($internetusername) > 1) {
		// Username is set so we're gonna do PPPoE for the WAN
		$command = test_input(shell_exec("sudo /sbin/uci set network.wan.proto=pppoe"));
		$command = test_input(shell_exec("sudo /sbin/uci set network.wan.username='$internetusername'"));
		$command = test_input(shell_exec("sudo /sbin/uci set network.wan.password='$internetpw'"));
		$command = test_input(shell_exec("sudo /sbin/uci commit network"));
		$command = test_input(shell_exec("sudo /etc/init.d/network restart"));
		$alertmessage = "Set internet username and password.";
		}

	if (strlen($internetusername) <= 1) {
		// Username is not set so we'll use DHCP for the WAN
		$command = test_input(shell_exec("sudo /sbin/uci set network.wan.proto=dhcp"));
		$command = test_input(shell_exec("sudo /sbin/uci commit network"));
		$command = test_input(shell_exec("sudo /etc/init.d/network restart"));
		$alertmessage = "Set internet to automatic settings.";
		}

	// This is the _old_ way which currently doesn't actually do anything.
	// Setting up PPPoE to work for now, can revisit VLANs later
	// We change it from "on" to mean "10" coz eventually we'll support VLAN numbers
	// It can probably be scrapped / deleted by leaving it in here for now coz it's not _actually_ doing anything permanent
	if ($internetvlan == "on") {
		$internetvlan = "10";
	}
        // This is where we'll do additional config file generation
        // No PPPoE Username means we'll use DHCP
        // The VLAN can either be unset or VLAN10 for UFB in NZ

        // We start by getting the MACs for WAN and LAN coz we need that for the file generation
        $lanmac = test_input(shell_exec("uci show network.lan.macaddr | cut -d \"'\" -f 2"));
        $wanmac = test_input(shell_exec("uci show network.wan.macaddr | cut -d \"'\" -f 2"));
        // Now the LAN IP
        $lanip = test_input(shell_exec("sudo /sbin/uci show network.lan.ipaddr |  cut -d\"'\" -f 2"));

        // Start creating the network config file
        $networkconfigfile = "/tmp/networkconfig";
        $networkconfigfilecontents = "
config interface 'loopback'
        option ifname 'lo'
        option proto 'static'
        option ipaddr '127.0.0.1'
        option netmask '255.0.0.0'

config globals 'globals'
        option ula_prefix 'fd7d:8f80:038a::/48'

config interface 'lan'
        option type 'bridge'
        option ifname 'eth0.1'
        option macaddr '" . $lanmac . "'
        option proto 'static'
        option netmask '255.255.255.0'
        option ip6assign '60'
        option dns '8.8.4.4 8.8.8.8'
        option ipaddr '" . $lanip . "'

config interface 'wan'
        option ifname 'eth0.2'
        option macaddr '" . $wanmac . "'
        option proto 'dhcp'
        option dns '8.8.4.4 8.8.8.8'

config interface 'wan6'
        option ifname 'eth0.2'
        option proto 'dhcpv6'

config switch
        option name 'switch0'
        option reset '1'
        option enable_vlan '1'

config switch_vlan
        option device 'switch0'
        option vlan '1'
        option ports '0 1 2 3 6t'

config switch_vlan
        option device 'switch0'
        option vlan '2'
        option ports '4 6t'" . PHP_EOL;

        // End of networking config file
        //file_put_contents($networkconfigfile, $networkconfigfilecontents);

        // After we've saved it in /tmp we'll then move it to /etc/config/network
        //shell_exec("sudo /bin/mv $networkconfigfile /etc/config/network");

        // Now we restart networking

	
        }





// Get the details from UCI and populate the variables
// Find what interface the WAN is - Sometimes its eth0, others its eth0.2 etc
$wanifname = test_input(shell_exec("uci show network.wan.ifname | cut -d \"'\" -f 2"));

// Now we know the WAN IF we can find its IP
$wanip = test_input(shell_exec("ifconfig $wanifname | grep addr | grep inet | grep -v inet6 | cut -d ':' -f 2"));

// Remove the 'Bcast' at the end of the string
$wanip = str_replace('Bcast', "", $wanip);

// Then remove the whitespace
$wanip = $str = trim($wanip, '"');


// $linkup = test_input(shell_exec("/usr/sbin/ethtool eth0 | grep 'Link detected' | cut -d ':' -f 2"));
$linkup = test_input(shell_exec("/usr/sbin/ethtool eth0 | grep 'Link detected'"));

if (strlen($wanip > 7)) {
        $isconnected = 'yes';
        }




?>
<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Settings have been saved! " . $alertmessage . "</h2>
  </div>";
}
?>
<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Online Status</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
<?php
if ($isconnected == 'yes') {
	echo "Congratulations, you're online!<br /><br />";
	$pingtime = test_input(shell_exec("sudo /bin/ping -c 1 -w 1 -q google.com | grep round-trip | cut -d \"/\" -f 5"));
	$trimpingtime = substr($pingtime, 0,4);
	echo "This means you shouldn't need to make any further changes to your settings, but they're below if you need to.<br /><br />";
	echo "The current respond time to Google is: " . $pingtime . "<br />";
	echo "Based on the response time of Google, it looks like your internet connect 'health' is: ";
	if ($trimpingtime < 20) {
                echo "<font color='green'>Amazing!</font>";
                }
	else if ($trimpingtime >= 20 and $trimpingtime < 40) {
		echo "<font color='green'>Good!</font>";
		}
	else if ($trimpingtime >= 40 and $trimpingtime < 60) {
		echo "<font color='orange'>Acceptable!</font>";
		}
	else {
		echo "<font color='red'>Not good! Consider contacting your ISP for technical support.</font>";
		}
	// echo "<br /><br />Now that you're online we would also recommend you check your WiFi is secure, you probably don't want your neighbors to be using all your data.";
	}
	else {
	// Not online, show them suggestions
	echo "Unfortuantely you're not online :-(<br /><br />";
	echo "Not to worry, here's a few things that you can check:<br />";
	echo "<ul><li>Is the router cabled correctly?</li><li>Does your Internet Provider require a username / password? <ul><li>If so, are your details correct?</li><li>If not, have you completed emptied out both the username and password?</li></ul></li><li>Ask your internet provider if they require you to use 'VLAN 10' or not.</li></ul>";
	}
?>
		</div>
	</div>
<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                <h2 class="mdl-card__title-text">Internet Access</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
   <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<strong>Note:</strong> These settings may be left blank.<br />
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
     <input class="mdl-textfield__input" type="text" id="internetusername" name="internetusername" value='<?php echo $internetusername; ?>'>
     <label class="mdl-textfield__label" for="sample3">Internet Username (user@xtrabb.co.nz)</label>
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
     <input class="mdl-textfield__input" type="text" id="internetpw" name="internetpw" value='<?php echo $internetpw; ?>'>
     <label class="mdl-textfield__label" for="sample3">Internet Password</label>
    </div>
 <label for="internetvlan" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
   <input type="checkbox" id="internetvlan" name="internetvlan" class="mdl-switch__input" <?php if ($internetvlan=='10') { echo 'checked'; } ?>>
   <span class="mdl-switch__label">Enable VLAN 10</span>
 </label>
 
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
