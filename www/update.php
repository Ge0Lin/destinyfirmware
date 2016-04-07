<?php
// Include the default stuff needed to dislplay the page
include 'tpl/header.php';
include 'tpl/nav.php';
include 'tpl/auth.php';

// Setup Variables
$doupdate = $platformversion = $routerfwversion = $hardwareversion = $routermodel = $routerversion = $newestavailable = "";

$platformversion = test_input(shell_exec("cat /etc/banner | grep Bleeding | cut -d ',' -f 2 | cut -d ')' -f 1"));
$routerfwversion = test_input(shell_exec("cat /etc/routerfwversion"));
$hardwareversion = test_input(shell_exec("cat /etc/routermodel"));

$routermodel = test_input(shell_exec("cat /proc/cpuinfo | grep machine | cut -d\":\" -f 2"));
$routerversion = test_input(shell_exec("sudo cat /etc/openwrt_version"));

// Get the latest current version for this model
$newestavailable = test_input(shell_exec("wget http://routers.co.nz/fw/$hardwareversion/latest -O /tmp/latest"));
$newestavailable = test_input(shell_exec("cat /tmp/latest"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Do stuff
        }


?>


<div class="mdl-grid demo-content">
<?php
// Check and see if we've saved the settings. If we have, tell the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
echo "  <div class='mdl-card__title mdl-card--expand mdl-color--red-300 mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>
    <h2 class='mdl-card__title-text'>Downloading firmware<br />";
	$doupdate = test_input(shell_exec("wget http://routers.co.nz/fw/$hardwareversion/latest.bin -O /tmp/latest.bin"));
	$doupdate = test_input(shell_exec("sudo /sbin/sysupgrade -v /tmp/latest.bin"));	
echo "    </h2>
  </div>";
}
?>


<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
	<div class="mdl-card__title mdl-color--teal-300">
	<h2 class="mdl-card__title-text">Currently running</h2>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--grey-600">


  <table style="width:100%"><tr><td width="35%">Router Model:</td><td> <?php echo $hardwareversion; ?></td></tr></table>
  <table style="width:100%"><tr><td width="35%">Firmware Version:</td><td><?php echo $routerfwversion . " " . $platformversion; ?></td></tr></table>
  </div>
</div>
<div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
	<div class="mdl-card__title mdl-color--blue-300">
	<h2 class="mdl-card__title-text">OLD VERSION</h2>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--grey-600">
  <table style="width:100%"><tr><td width="35%">Firmware Version:</td><td> <?php echo $newestavailable; ?></td></tr></table>
	<?php if ($newestavailable > $routerfwversion) { echo "Upgrade available!"; } ?>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="submit">Upgrade now</button>
	</form>
	</div>
</div>



 </div>
</div>
<?php
include 'tpl/footer.php';
?>
