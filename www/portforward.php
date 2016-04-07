<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$totalexistingforwards = $portnum = $deviceip = $variablename = "";

// Get the existing forwards
// The last line of existing forwards in uci should look like this:
// firewall.@redirect[7].dest='lan'
// So we want one a few up, that looks like this:
// firewall.@redirect[7]=redirect
// Which we use to populate the variable with the total number of redirects:
$totalexistingforwards = test_input(shell_exec("/sbin/uci show firewall | grep 'redirect' | tail -n 1 | cut -d '[' -f 2 | cut -d ']' -f 1"));



if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Do stuff
	// Like get the variables
	$portnum = $_POST['portnum'];
	$deviceip = $_POST['deviceip'];

	// We save it in a CSV file for later re-use
	// We add it so it's live:
	$exec1results = test_input(shell_exec("sudo /sbin/uci add firewall redirect"));
	$exec2results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].name='Rule generated from Router WebUI'"));
	$exec3results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].src=wan"));
	$exec4results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].target=DNAT"));
	$exec5results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].proto=tcpudp"));
	$exec6results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].src_dport=$portnum"));
	$exec7results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].dest_ip=$deviceip"));
	$exec8results = test_input(shell_exec("sudo /sbin/uci set firewall.@redirect[-1].dest=lan"));
	$exec9results = test_input(shell_exec("sudo /sbin/uci commit firewall"));
	$exec10results = test_input(shell_exec("sudo /etc/init.d/firewall restart"));
	// End of the POST stuff
	}

// Function for reading the named devices from the CSV
function readDHCPcsv() {
        $file = fopen('/etc/config/dhcp.csv', 'r');
        $dhcpItems = array();

        while (($line = fgetcsv($file)) !== FALSE) {
                //$line is an array of the csv elements
                // print_r($line);
                array_push($dhcpItems, $line);
        }

        // var_dump($dhcpItems);
        fclose($file);

        return $dhcpItems;
}

?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Settings have been saved! Forwarded $portnum to $deviceip</h2>
  </div>";
}
?>


 <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
	<ul><li>In order to port-forward you must first name your device.</li>
	<li>Forwarding does both TCP and UDP.</li>
	<li>To forward a range, simply enter it as "21-23".</li>
  </div>

<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Port-forwards</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">

   <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>Select your device to forward to:</label><br />
        <select name='deviceip' id='deviceip' style='width: 300px'>
<?php
$dhcpCSV = readDHCPcsv();
foreach ($dhcpCSV as $currentline) {
                        echo "<option value='$currentline[1]'>$currentline[2]</option>";
                        }
?>
        </select><br />
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
     <input class="mdl-textfield__input" type="text" id="portnum" name="portnum" value='' />
     <label class="mdl-textfield__label" for="portnum">Enter your port to forward</label>
    </div>
 <br /><br />
 <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Save settings</button>
         </form>


  </div>
</div>
<?php
// Start the check for existing forwards, display them if there's any
if ($totalexistingforwards >= '1') {
echo "<div class='demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col'>
              <div class='mdl-card__title mdl-card--expand mdl-color--teal-300'>
                <h2 class='mdl-card__title-text'>Existing Port-forwards</h2>
              </div>
              <div class='mdl-card__supporting-text mdl-color-text--grey-600'>
Stuff goes here<br />
Line 2<br />
Line 3<br />
Line 4<br />
Line 5
</div>";
}
// End of check for existing forwards, we're done here
?>


 </div>
</div>
<?php
include 'tpl/footer.php';
?>
