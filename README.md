# Destiny Firmware
Firmware initially for the Xiaomi MiWiFi Mini with plans to eventually extend to other devices

Built in PHP with the following goals:
* Simple, the firmware should target the 95%, not the power-users
* Minimal, remove anything that doesn't *really* need to be asked, don't give the user an overabundance of choices
* Intuitive to use, no manuals and no online help, with minimal "tips" in the interface where required
* Clean, based very loosely on Material Design
* Mobile + Desktop, it shouldn't matter if you plug the device in and use a cellphone, tablet or laptop to set it up
* Naming devices, the central 'theme' for navigation around the UI

#BEFORE YOU BEGIN:
Ensure you add your SSH key to: etc/dropbear/authorized_keys

Alternatively edit etc/config/dropbear and allow root login etc or you'll lock yourself out of the router

#BUILDING:
Grab yourself the latest OpenWRT Image Builder from here:

https://downloads.openwrt.org/snapshots/trunk/ramips/mt7620/OpenWrt-ImageBuilder-ramips-mt7620.Linux-x86_64.tar.bz2

tar xvjf OpenWrt-ImageBuilder-ramips-mt7620.Linux-x86_64.tar.bz2

cd ~/OpenWrt-ImageBuilder-ramips-mt7620.Linux-x86_64/

make image PROFILE=MIWIFI-MINI PACKAGES="lighttpd lighttpd-mod-cgi mtr nano php5 php5-cgi php5-cli sudo netcat screen iperf3" FILES=~/destinyfirmware/ && cp bin/ramips/openwrt-ramips-mt7620-miwifi-mini-squashfs-sysupgrade.bin ~/xiaomi.bin
