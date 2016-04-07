<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$isconnected = $wifichannel5ghz = $wifichannel24ghz = $clientip = $platformversion = $routerfwversion = $hardwareversion = $wanifname = $wangw = $wanip = $lanip = $uptime = $currenttime = $wifiname = $routermodel = $routerversion = $memfree = $memused = $memtotal = $connections = $pingtime = $gwpingtime = $yourpingtime = $dnsstatus = $loadaverage = $load1min = $load5mins = $load15mins = "";

// More variables for connections / stats
$clientcountsinceboot = $clientcount5ghz = $clientcount24ghz = $connectionsmax = $connectionscurrent = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
        }


// Load system details into PHP variables for displaying
// Find what interface the WAN is - Sometimes its eth0, others its eth0.2 etc
$wanifname = test_input(shell_exec("uci get network.wan.ifname"));

// Then get the WAN IP from there
$wanip = test_input(shell_exec("ifconfig $wanifname | grep addr | grep inet | grep -v inet6 | cut -d ':' -f 2"));
$wanip = str_replace('Bcast', "", $wanip);
$wanip = $str = trim($wanip, '"');

$wangw = test_input(shell_exec("sudo /sbin/ip ro get 8.8.8.8 | grep 8.8.8.8 | cut -d ' ' -f 3"));

// Now the LAN IP
$lanip = test_input(shell_exec("/sbin/uci get network.lan.ipaddr"));

// General system info
// Uptime is weird to get coz it has current time first so we cut based on the word 'up', then the 'l' from 'load average'
// Then we remove the last comma after the uptime
$uptime = trim(test_input(shell_exec("/usr/bin/uptime | cut -d 'u' -f 2 | cut -d 'l' -f 1 | cut -d 'p' -f 2")),',');
// Current time is nice and easy
$currenttime = test_input(shell_exec("/bin/date"));

// Get the clients IP to show them so it's easier to name devices
$clientip = $_SERVER['REMOTE_ADDR'];

$wifiname = test_input(shell_exec("sudo /sbin/uci get wireless.@wifi-iface[1].ssid"));
$routermodel = test_input(shell_exec("cat /proc/cpuinfo | grep machine | cut -d\":\" -f 2"));
$routerversion = test_input(shell_exec("sudo cat /etc/openwrt_version"));
$memfree = test_input(shell_exec("cat /proc/meminfo | grep MemFree | cut -d':' -f 2"));
// Trim it and tidy it in to MB
$memfreetemp = explode(' ',$memfree);
$memfree = round(($memfreetemp[0] / 1024), 2);
$memtotal = test_input(shell_exec("cat /proc/meminfo | grep MemTotal | cut -d':' -f 2"));
$memtotaltemp = explode(' ',$memtotal);
$memtotal = round(($memtotaltemp[0] / 1024), 2);


// Test pings to WAN GW and Google:
$pingtime = test_input(shell_exec("sudo /bin/ping -c 1 -w 1 -q google.com | grep round-trip | cut -d \"/\" -f 5"));
$gwpingtime = test_input(shell_exec("sudo /bin/ping -c 1 -w 1 -q $wangw | grep round-trip | cut -d \"/\" -f 5"));
$yourpingtime = test_input(shell_exec("sudo /bin/ping -c 1 -w 1 -q $clientip | grep round-trip | cut -d \"/\" -f 5"));

// Get version details for Router, OS (Firmware) and OpenWRT
$platformversion = test_input(shell_exec("cat /etc/banner | grep Bleeding | cut -d ',' -f 2 | cut -d ')' -f 1"));
$routerfwversion = test_input(shell_exec("cat /etc/routerfwversion"));
$hardwareversion = test_input(shell_exec("cat /etc/routermodel"));
// $connections =

// Get details for the stats section
$connectionsmax = test_input(shell_exec("cat /proc/sys/net/netfilter/nf_conntrack_max"));
$connectionscurrent = test_input(shell_exec("cat /proc/sys/net/netfilter/nf_conntrack_count"));
$clientcount5ghz = test_input(shell_exec("/usr/sbin/iw dev wlan0 station dump | grep Station | wc -l"));
$clientcount24ghz = test_input(shell_exec("/usr/sbin/iw dev wlan1 station dump | grep Station | wc -l"));
$clientcountsinceboot = test_input(shell_exec("cat /tmp/dhcp.leases | wc -l"));

// Split the load average in to 1 5 and 15 minutes
$loadaverage = test_input(shell_exec("/usr/bin/uptime | cut -d 'l' -f 2 | cut -d ':' -f 2"));
$loadaverage = explode(',', $loadaverage);
$load1min = 100 * $loadaverage[0];
$load5mins = 100 * $loadaverage[1];
$load15mins = 100 * $loadaverage[2];

$wifichannel24ghz = test_input(shell_exec("sudo /sbin/uci get wireless.radio1.channel"));
$wifichannel5ghz = test_input(shell_exec("sudo /sbin/uci get wireless.radio0.channel"));

if (strlen($wanip > 7)) {
        $isconnected = 'yes';
        }

?>
<div class="mdl-grid demo-content">
<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Basic Details</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
  <table style="width:100%"><tr><td width="35%">Current time:</td><td> <?php echo $currenttime; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Time since restart:</td><td><?php echo $uptime; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Router Internet IP:</td><td> <?php echo $wanip; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Router Local IP:</td><td> <?php echo $lanip; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Your Device IP:</td><td> <?php echo $clientip; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Router Model:</td><td> <?php echo $hardwareversion; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Firmware Version:</td><td><?php echo $routerfwversion . " " . $platformversion; ?></td></tr></table>
 </div>
</div>
<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                <h2 class="mdl-card__title-text">Router / Connection Health</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
  <table style="width:100%"><tr><td width="35%">ISP Response Time:</td><td> <?php echo $gwpingtime; ?> to 
<?php
echo $wangw;
if ($isconnected == 'yes') {
        $trimgwpingtime = substr($gwpingtime, 0,4);
	if ($trimgwpingtime < 10) {
                echo " - <font color='green'>Amazing!</font>";
                }
        else if ($trimpingtime >= 10 and $trimpingtime < 30) {
                echo " - <font color='green'>Good!</font>";
                }
        else if ($trimpingtime >= 30 and $trimpingtime < 50) {
                echo " - <font color='orange'>Acceptable!</font>";
                }
        else {
                echo " - <font color='red'>Not good!</font>";
                }
        }

?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Google Response Time:</td><td> 
<?php
echo $pingtime;
if ($isconnected == 'yes') {
	$trimpingtime = substr($pingtime, 0,4);
        if ($trimpingtime < 20) {
                echo " - <font color='green'>Amazing!</font>";
                }
        else if ($trimpingtime >= 20 and $trimpingtime < 40) {
                echo " - <font color='green'>Good!</font>";
                }
        else if ($trimpingtime >= 40 and $trimpingtime < 60) {
                echo " - <font color='orange'>Acceptable!</font>";
                }
        else {
                echo " - <font color='red'>Not good!</font>";
                }
	}
?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Your Response Time:</td><td><?php echo $yourpingtime . " to " . $clientip; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">System Load:</td><td> <?php echo $load1min; ?>%</td></tr></table>
  <table style="width:100%"><tr><td width="35%">Memory Free / Total:</td><td> <?php echo $memfree . "MB / " . $memtotal . "MB"; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Active Connections:</td><td> <?php echo $connectionscurrent; ?> / <?php echo $connectionsmax; ?></td></tr></table>
             </div>
</div>

<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
	<div class="mdl-card__title mdl-card--expand mdl-color--purple-300">
	 <h2 class="mdl-card__title-text">Wired / Wireless</h2>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--grey-600">
	 <table style="width:100%"><tr><td width="35%">Wireless 2.4Ghz Clients:</td><td> <?php echo $clientcount24ghz; ?></td></tr></table>
	 <table style="width:100%"><tr><td width="35%">Wireless 5Ghz Clients:</td><td> <?php echo $clientcount5ghz; ?></td></tr></table>
	 <table style="width:100%"><tr><td width="35%">Total DHCP Leases:</td><td> <?php echo $clientcountsinceboot; ?></td></tr></table>
	 <table style="width:100%"><tr><td width="35%">2.4Ghz Channel:</td><td> <?php echo $wifichannel24ghz; ?></td></tr></table>
	 <table style="width:100%"><tr><td width="35%">5Ghz Channel:</td><td> <?php echo $wifichannel5ghz; ?></td></tr></table>
<?php
// So we're being lazy and not pulling the REAL WAN port speed coz it's only 100mbps on the MiWiFi but later can pull it using:
// swconfig dev switch0 show
?>
	 <table style="width:100%"><tr><td width="35%">Internet Port-speed:</td><td> 100mbps</td></tr></table>
	 
 </div>
</div>
<?php
include 'tpl/footer.php';
?>
