### Magento cron importexport

This module simply takes the process seen in the import/export section of the site and puts it into a nightly cron. The times can be changed in the app/code/community/Meta13/CronImportExport/etc/config file to however often you want.

I created this because a client requested the ability to import and export products however do to the size of the database it was not possible through the admin. I highly suggest pairing this with [AOE Scheduler](https://github.com/AOEpeople/Aoe_Scheduler) to allow you to run the cron via the magento admin.
