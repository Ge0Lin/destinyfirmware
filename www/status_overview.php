<?php
// First things first define empty variables
$platformversion = $routerfwversion = $hardwareversion = $wanifname = $wangw = $wanip = $lanip = $uptime = $currenttime = $wifiname = $routermodel = $routerversion = $memfree = $memtotal = $connections = $pingtime = $gwpingtime = $dnsstatus = "";

// Our input validation
function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
        }


// Load system details into PHP variables for displaying
// Find what interface the WAN is - Sometimes its eth0, others its eth0.2 etc
$wanifname = test_input(shell_exec("uci show network.wan.ifname | cut -d \"'\" -f 2"));

// Then get the WAN IP from there
$wanip = test_input(shell_exec("ifconfig $wanifname | grep addr | grep inet | grep -v inet6 | cut -d ':' -f 2"));
$wanip = str_replace('Bcast', "", $wanip);
$wanip = $str = trim($wanip, '"');

$wangw = test_input(shell_exec("sudo /sbin/ip ro get 8.8.8.8 | grep 8.8.8.8 | cut -d ' ' -f 3"));

// Now the LAN IP
$lanip = test_input(shell_exec("sudo /sbin/uci show network.lan.ipaddr |  cut -d\"'\" -f 2"));

// General system info
// Uptime is weird to get coz it has current time first so we cut based on the word 'up', then the 'l' from 'load average'
// Then we remove the last comma after the uptime
$uptime = trim(test_input(shell_exec("sudo /usr/bin/uptime | cut -d 'u' -f 2 | cut -d 'l' -f 1 | cut -d 'p' -f 2")),',');
// Current time is nice and easy
$currenttime = test_input(shell_exec("sudo /bin/date"));


$wifiname = test_input(shell_exec("sudo /sbin/uci show wireless.@wifi-iface[0].ssid |  cut -d\"'\" -f 2"));
$routermodel = test_input(shell_exec("cat /proc/cpuinfo | grep machine | cut -d\":\" -f 2"));
$routerversion = test_input(shell_exec("sudo cat /etc/openwrt_version"));
$memfree = test_input(shell_exec("cat /proc/meminfo | grep MemFree | cut -d\":\" -f 2"));
$memtotal = test_input(shell_exec("cat /proc/meminfo | grep MemTotal | cut -d\":\" -f 2"));


// Test pings to WAN GW and Google:
$pingtime = test_input(shell_exec("sudo /bin/ping -c 1 -w 1 -q google.com | grep round-trip | cut -d \"/\" -f 5"));
$gwpingtime = test_input(shell_exec("sudo /bin/ping -c 1 -w 1 -q $wangw | grep round-trip | cut -d \"/\" -f 5"));

// Get version details for Router, OS (Firmware) and OpenWRT
$platformversion = test_input(shell_exec("cat /etc/banner | grep Bleeding | cut -d ',' -f 2 | cut -d ')' -f 1"));
$routerfwversion = test_input(shell_exec("cat /etc/routerfwversion"));
$hardwareversion = test_input(shell_exec("cat /etc/routermodel"));
// $connections = 

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Router overview</title>
    <!-- Sets initial viewport load and disables zooming  -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">

    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Include the compiled Ratchet CSS -->
    <link href="ratchet.css" rel="stylesheet">
    <link href="ratchet-theme-ios.css" rel="stylesheet">

    <!-- Include the compiled Ratchet JS -->
    <script src="ratchet.js"></script>
  </head>
  <body>

    <!-- Make sure all your bars are the first things in your <body> -->
    <header class="bar bar-nav">
  <button class="btn btn-link btn-nav pull-left">
    <a href="index.php"><span class="icon icon-left-nav"></span>
    Home</a>
  </button>
      <h1 class="title">Router overview</h1>
    </header>

    <!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
    <div class="content">
      <div class="card">
	<ul class="table-view">
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Current time:</td><td> <?php echo $currenttime; ?></td></tr></table></li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Internet IP:</td><td> <?php echo $wanip; ?></td></tr></table></li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Local IP:</td><td> <?php echo $lanip; ?></td></tr></table></li>
	<li class="table-view-cell table-view-divider">Technical:</li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Router Model:</td><td> <?php echo $hardwareversion; ?></td></tr></table></li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Firmware Version:</td><td><?php echo $routerfwversion . " " . $platformversion; ?></td></tr></table></li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Uptime:</td><td><?php echo $uptime; ?></td></tr></table></li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">ISP Response Time:</td><td> <?php echo $gwpingtime; ?> to <?php echo $wangw; ?></td></tr></table></li>
	<li class="table-view-cell"><table style="width:100%"><tr><td width="35%">Google Response Time:</td><td> <?php echo $pingtime; ?></td></tr></table></li>
	</ul>
      </div>
    </div>

  </body>
</html>


