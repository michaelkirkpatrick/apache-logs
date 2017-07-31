# apache-logs

PHP parser with database component to view Apache access log data.

## Import.php

Imports the access log. I suggest you setup a cron job to periodically run the import.php file and then truncate the access.log file so you don't import duplicates.

## Classes

* **AccessLog.class.php** -- Handles the Apache access log data
* **Database.class.php** -- MySQL connection and query handling
* **uuid.class.php** -- Generates UUID's to insert into the database 'id' field

## Database Structure

The database runs in a MySQL environment. You can create your tables using the `database-structures.sql` file.