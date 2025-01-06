#!/bin/bash

# Prompt the user for restoring from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders
SEARCH_FINAL_BACKUP_FOLDER=$(ls -d $PWD/search_final_backup_folder_* 2>/dev/null | sort | tail -n 1)


# Check if the directories exist
if [ -z "$SEARCH_FINAL_BACKUP_FOLDER" ]; then
  echo "Error: No FINAL BACKUP directory found in the current working directory."
  exit 1
fi


echo "Detected SEARCH FINAL BACKUP folder: $SEARCH_FINAL_BACKUP_FOLDER"


# Define restoration paths
HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

# WL files to restore
CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
LS_RNDR_SEARCH="$HOME_WL/templates/render-search-main-design.php"


# MAIN files to restore
MAIN_CSS_DIR="$HOME_MAIN/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME_MAIN/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"

# Copy files from the WhiteLabel backup
echo "Restoring WhiteLabel files..."
cp  "$SEARCH_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$CSS_DIR"
cp  "$SEARCH_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php" "$LS_RNDR_SEARCH"


# MAIN files to restore
echo "Restoring MAIN files..."
cp  "$SEARCH_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css" "$MAIN_CSS_DIR"
cp  "$SEARCH_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php" "$MAIN_RNDR_SEARCH"
cp  "$SEARCH_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" "$MAIN_MDL_ADMIN"


echo "Restore process completed!"
