=== Plugin Name ===
Contributors: Chris T, aka Tefra
Donate link: http://www.t3-design.com/donate/
Tags: Database, Cron, Backup, Schedule, Logs
Requires at least: 2.5
Tested up to: 2.5.1
Stable tag: 1.1

DBC Backup, is a simple way to schedule daily database backups using the wp cron system.

== Description ==

DBC Backup, is a simple way to schedule daily database backups using the wp cron system. You can select when and where your backup will be generated. If your server has support you can select between three different compression formats: none, Gzip and Bzip2. The plugin will try to auto create the export directory, the .htaccess and an empty index.html file to protect your backups.

The backup file is also protected by a small hash key which make it impossible for someone to guess the backup name and download it.

During generation, a log will be generated which includes, the generation date, file, filesize, status amd the duration of the generation.

Except the cron backup, you have also the ability to take backups immediately. The backups are identical of what phpmyadmin produces because DBC Backup is using the key procedures of phpmyadmin.

DBC Backup was built to be fast, flexible and as simple as possible.

= What's New=
Version 1.1
-----------
- Added option to specify the interval between crons. e.g 1 hour, 2 days, 3 weeks, 4 months etc etc
- Added option to remove older than x days backups after a new backup generation

Version 1.0
-----------
- First Initial Release

== Installation ==

1. Upload folder dbcbackup to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can enter the admin page from the link 'DB Cron Backup' on the top menu.
4. Configure the plugin settings and you are ready.

* If the plugin can't create the export directory you will have to do it manually and don't forget to chmod 777 it.
* If you are upgrading, deactivate the plugin first and remove all old files, before starting.

== Frequently Asked Questions ==

= The plugin takes a backup whenever i setup a specific cron job =
If the time of the cron is before the current time the wp cron system is adding the cron job to run at the next page view, despite of how long ago it is set. 

= Why only the none compression format appears =
Because Gzip and Bzip2 are not installed on your server.

== Screenshots ==

For screenshots visit the plugin page http://www.t3-design.com/dbc-backup-for-wordpress/