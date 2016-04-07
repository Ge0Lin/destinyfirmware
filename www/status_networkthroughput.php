<?php
// First things first define empty variables
$wifiname = $wifipw = $wifienc = "";

// Our input validation
function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $wifiname = test_input($_POST["wifiname"]);
        $wifipw = test_input($_POST["wifipw"]);
        $wifienc = test_input($_POST["wifienc"]);
        }



?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Network Throughput</title>
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
      <h1 class="title">Network Throughput</h1>
    </header>

    <!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
    <div class="content">
	<p class="content-padded">Where the router says "eth0", this is your internet port.<br />wlan0 is your Wireless network.<br />eth1, eth2, eth3 and eth4 are all the individual Network ports</p>
	<small><small><pre><?php
$handle = popen('/usr/bin/bwm-ng -d -D -c1 -a -I eth0,eth1,eth2,eth3,wlan0,eth4', 'r');
while ($line = fread($handle, 100)){
    echo $line;
}
pclose($handle);

?></pre></small></small>
    </div>

  </body>
</html>


