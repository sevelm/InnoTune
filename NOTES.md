# NOTES

## 1. Cronjobs

The following list contains all jobs with their time occurrence.

```
# m h  dom mon dow   command
*/15 * * * * /var/www/playercheck.sh
*/30 * * * * /var/www/check_linein.sh

3 3 * * * sudo shutdown -r now

* * * * * /var/www/checkprocesses.sh
*/15 * * * * /var/www/checkcputemp.sh
45 3 * * * /var/www/archivelogs.sh
*/15 * * * * /var/www/check_soundcards.sh
*/5 * * * * /var/www/checklogsize.sh
30 */1 * * * /var/www/filesizechecker.sh
30 3 * * * sudo /var/www/update/checkImportantUpdates.sh
0 */4 * * * sudo /var/www/shutdown_hook.sh
```

## 2. Systemd-Services
### 2.1 USB-Mount Service
File: `usbmount.service`

Location: `/etc/systemd/system/usbmount@.service`

Service template for auto-mounting usb devices.

### 2.2 Custom Shutdown Service
File: `custom_shutdown.service`

Location:`/etc/systemd/system/custom_shutdown.service`

Service runs the `/var/www/shutdown_hook.sh` before a target halt or shutdown is achieved.

## 3. UDEV-Rules
### 3.1 Audio Rules
#### 3.1.1 Card IDs
File: auto-generated

Location: `/etc/udev/rules.d/80-usb-audio-id.rules`

This rules file will be auto-generated during the audio configuration.
These rules are used to give the same usb audio device always the same device id even when previous devices in the chain are unplugged.

#### 3.1.2 Device-Removal Log
File: auto-generated

Location: `/etc/udev/rules.d/90-usb-audio-log-remove.rules`

This rules file will be auto-generated during the audio configuration.

These rules are used to log if a certain usb audio device is suddenly unplugged.

### 3.2 USB-Mount Rules
File: `usbmount.rules`

Location: `/etc/udev/rules.d/usbmount.rules`

Rules to mount/unmount usb filesystems when the kernel recognizes such devices.

### 3.3 KNXD Rules
File: `70-knxd.rules`

Location: `/etc/udev/rules.d/70-knxd.rules`

Rules to set the knx-user as the owner of a device if it is a knx interface.

## 4. Config-Files
### 4.1 USB-Mount Config
File: `usbmount.conf`

Location: `/etc/usbmount/usbmount.conf`

Enables usbmount, sets supported filesystems and the mount folders.

### 4.2 DHClient Config
File: `dhclient.conf`

Location: `/etc/dhcp/dhclient.conf`

Used for getting a ip address from a dhcp server.
Changed timeout and retry values.

### 4.3 Journald Config
File: `journald.conf`

Location: `/etc/systemd/journald.conf`

Journald is used for systemd service logging.
The journal is set to volatile so logs get deleted, when the server is rebooted.
Safety measure to ensure that logs aren't overflowing the filesystem.

### 4.4 Lighttpd Config
File: `lighttpd.conf`

Location: `/etc/lighttpd/lighttpd.conf`

Configurations used for the lighttpd web server (InnoControl Webinterface).

### 4.5 Logrotate Config
File: `logrotate.conf`

Location: `/etc/logrotate.conf`

Set the rotations to a more frequent time and added knx logs to be rotated.

### 4.6 PHP Config
File: `php.ini`

Location: `/etc/php/5.6/cgi/php.ini`

PHP installation configurations.

### 4.7 WPA Supplicant Config
File: `wpa_supplicant.conf`

Location: `/opt/innotune/settings/wpa_supplicant.conf`

Used for settings up a wifi connection.

## 5. Other
### 5.1 APT Sources
File: `sources.list`

Location: `/etc/apt/sources.list`

APT Packages list for Ubuntu Xenial (16.04):
* Main
* Security
* Updates
* Backports

### 5.2 LMS Wizard
File: `wizard.html`

Location: `/usr/share/squeezeboxserver/HTML/EN/settings/server/wizard.html`

This lms wizard version already contains the correct data in the forms and get auto submitted when requested, so the Logitech Media Server doesn't get stuck at the wizard in the background.

### 5.3 Version
File `version.txt`

Location: `/var/www/version.txt`

Contains the current version number of the InnoTune-System. Used for updates.
### 5.4 Update
#### 5.4.1 Update Script
File: `update.sh`

Location: `/opt/innotune/update/cache/InnoTune/update.sh`

This update scripts will be executed after the new software is downloaded from the repository.

#### 5.4.2 Important Updates
File: `importantUpdate.txt`

Location: `/opt/innotune/settings/importantUpdate.txt`

This file is used to check if important updates are available on the Github-Repo and should be automatically installed.

The file contains the current version-code and is checked against the repos version-code.

The versions are checked in the `checkImportantUpdates.sh` script which is running as a cronjob.
### 5.5 Librespot
File: `librespot`

Location: `/root/librespot`

Librespot is a program emulating a spotify speaker. This program is self-compiled and the source is from
[Github](https://github.com/librespot-org/librespot).
### 5.6 KNX
Version: 0.14.29-5

Github-Repo can be found [here](https://github.com/knxd/knxd).
#### 5.6.1 Default Radios
File: `knxdefaultradios.txt`

Location: `/opt/innotune/settings/knxdefaultradios.txt`

Contains a list of default radios used for a knx radio button.

#### 5.6.2 KNXD Deb
File: `knxd_0.14.29-5_armfh.deb`

Location: `/root/knxd_0.14.29-5_armfh.deb`

Installation-File for the KNX Daemon program.
#### 5.6.3 KNXD-Tools Deb
File: `knxd-tools_0.14.29-5_armfh.deb`

Location: `/root/knxd-tools_0.14.29-5_armfh.deb`

Installation-file for the KNX Tools.
