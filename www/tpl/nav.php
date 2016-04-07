    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">
<?php
// Set the title based on the URL
if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/internet_access.php") {
	echo "Internet Access";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/wifi.php") {
	echo "WiFi";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/naming.php") {
	echo "Name / Label Devices";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/priority.php") {
	echo "Gaming / Priority Device";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/usage.php") {
	echo "Device Usage";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/lan.php") {
	echo "LAN / DHCP";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/sharing.php") {
	echo "Printer / HDD Sharing";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/portforward.php") {
	echo "Port-forwards";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/setpw.php") {
	echo "Router Password";
	}
	else if (htmlspecialchars($_SERVER["REQUEST_URI"]) == "/update.php") {
	echo "Update Router";
	}
	else {
	// Fallback where we don't have a name
	echo "Home";
	}

?>
</span>
          <div class="mdl-layout-spacer"></div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
              <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
              <input class="mdl-textfield__input" type="text" id="search">
              <label class="mdl-textfield__label" for="search">Enter your query...</label>
            </div>
          </div>
          <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
            <li class="mdl-menu__item">About</li>
            <li class="mdl-menu__item">Contact</li>
            <li class="mdl-menu__item">Legal information</li>
          </ul>
        </div>
      </header>
      <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
	  <a class="mdl-navigation__link" href="index.php"><img style="margin: 0px 5px" border='0' src='icons/home_small.png'> Home</a>
	  <a class="mdl-navigation__link" href="internet_access.php"><img style="margin: 0px 5px" border='0' src='icons/wan_small.png'> Internet Access</a>
	  <a class="mdl-navigation__link" href="wifi.php"><img style="margin: 0px 5px" border='0' src='icons/wifi_small.png'> WiFi</a>
	  <a class="mdl-navigation__link" href="naming.php"><img style="margin: 0px 5px" border='0' src='icons/multi_devices_small.png'> Device Naming</a>
	  <a class="mdl-navigation__link" href="priority.php"><img style="margin: 0px 5px" border='0' src='icons/gamepad_small.png'> Gaming / Priority</a>
	<hr />
	  <a class="mdl-navigation__link" href="usage.php"><img style="margin: 0px 5px" border='0' src='icons/router_small.png'> Device Usage</a>
	  <a class="mdl-navigation__link" href="lan.php"><img style="margin: 0px 5px" border='0' src='icons/lan_small.png'> LAN / DHCP</a>
	  <a class="mdl-navigation__link" href="sharing.php"><img style="margin: 0px 5px" border='0' src='icons/printer_small.png'> Printer / HDD Sharing</a>
	  <a class="mdl-navigation__link" href="portforward.php"><img style="margin: 0px 5px" border='0' src='icons/firewall_portforward_small.png'> Port-forwards</a>
	  <a class="mdl-navigation__link" href="setpw.php"><img style="margin: 0px 5px" border='0' src='icons/account_human_small.png'> Router Password</a>
	  <a class="mdl-navigation__link" href="update.php"><img style="margin: 0px 5px" border='0' src='icons/system_update_small.png'> Update Router</a>
        </nav>
      </div>
	<div class="mdl-layout__content mdl-color--grey-100">
