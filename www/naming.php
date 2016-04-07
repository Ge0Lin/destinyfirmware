<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$devicemac = $newname = $leaseexpires = $epochdate = $clientip = $lanip = $variablename = "";

$lanip = test_input(shell_exec("/sbin/uci get network.lan.ipaddr"));
$clientip = $_SERVER['REMOTE_ADDR'];
$epochdate = test_input(shell_exec("date +%s"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
	$devicemac = $_POST['devicemac'];
	$newname = $_POST['newname'];
	// Find the current IP address that the MAC address has
	$deviceip = test_input(shell_exec("cat /tmp/dhcp.leases | grep $devicemac | cut -d ' ' -f 3"));
        }



    // Function for reading the values stored in the file
    function readDHCP() {
        // Open file and unserializethe contents
		// Should look like this:
		// 1455481525 40:b8:37:c4:1b:60 192.168.20.251 JosiahZ5 01:40:b8:37:c4:1b:60
	    $varData = file_get_contents("/tmp/dhcp.leases");
		$leases = explode("\n", $varData);
	        // Output variable for testing to see if the reading and unserializing is actually happening

		//DHCP Items
		$dhcpCSV = readDHCPcsv();
		$relevantDHCPCSV = array();

		foreach($dhcpCSV as $csvItems) {
			array_push($relevantDHCPCSV, $csvItems[1]);
		}

		// Start the beginning of the select statement
		echo "<select name='devicemac'>";
		$exists = false;
		foreach ($leases as $currentline) {
			$leasedetails = explode(" ", $currentline);
			foreach($relevantDHCPCSV as $dhcpItems) {
				if($dhcpItems == $leasedetails[2]) {
					$exists = true;
				}
			}
			// Check and make sure it's not the blank line at the end by making sure the MAC is semi valid
			// if (strlen($leasedetails[1]) > 12) {
				//We need to also check here to make sure it's not already got an existing name in /etc/config/dhcp.csv
				//Do a new entry for each of them, including all extra fancy stuff to make it look Material
				if($exists == false) {
					if (strlen($leasedetails[2] > 2)) {
						echo "<option value='" . $leasedetails[1] . "'>" . ((strlen($leasedetails[3]) < 3) ? "Unknown Device" : $leasedetails[3]) . " - " . $leasedetails[2] . 	"</option>";
					}
				}
			// }
			$exists = false;
	    }

		// Now we finish the select
		echo "</select>";
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
    <h2 class='mdl-card__title-text'>Device name has been saved</h2>
  </div>";
}
?>

<div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
We'll start with a devices name that it's told us, but a lot of the time this name isn't very helpful. Calling a device 'Bobs-Cellphone' is better than 'android-a1b2c3', so give your devices a better and more helpful name!<br />
You can't use spaces, only 'a-z', '0-9', along with '-' and '_'.
</div>

<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
              <div class="mdl-card__title mdl-card--expand mdl-color--blue-300">
                <h2 class="mdl-card__title-text">Name devices</h2>
              </div>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">


  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<br />
<?php readDHCP(); ?>
   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="text" id="newname" name="newname" value=''>
    <label class="mdl-textfield__label" for="newname">Ex. Andys-iPhone</label>
   </div>
<br /><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Save name</button>
</form>
</div>
 </div>



</div>
 </div>
</div>
<?php
include 'tpl/footer.php';
?>
