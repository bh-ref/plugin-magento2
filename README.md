# boxalino Magento 2 plugin

## Installation

1. Copy the Boxalino folder with all files in your app/code folder (create the folder if it doesn't already exist).
2. Set chmod for Boxalino directory and files:
    * chmod 755 -R app/code/Boxalino
3. Upgrade with the two modules (Exporter and Frontend)
	* run the command line (from your main magento folder): php bin/magento setup:upgrade
4. Update the administrator role:
    * System > Permissions > Roles > Administrators - Save Role
5. Indicate your account name and password in the Store -> Configuration -> Boxalion -> General
6. Run a full data sync (direct command line from your main magento folder): php bin/magento indexer:reindex boxalino_indexer
7. Activate the search, facets, autocompletion and recommendations (one after the other).
7. Set up a an indexing cronjob, running at least one full index per day. Use the delta indexer if you want to update more than once per hour.

## Documentation

The documentation is currently beying created, a link will be added soon.