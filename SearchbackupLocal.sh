 #!/bin/bash
#set -x

HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
TMP_SOURCE_DIR="$HOME_WL/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
SEARCH_MAIN_BACKUP_FOLDER="$PWD/search_main_backup_$TIMESTAMP"
SEARCH_WL_BACKUP_FOLDER="$PWD/search_wl_backup_$TIMESTAMP"

# WL Variables
LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"
CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"


# BalancePro Main Variables
MAIN_CSS_DIR="$HOME_MAIN/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME_MAIN/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"

# Create the backup folder if it doesn't exist
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER"
echo "MAIN Backup folder created at: $SEARCH_MAIN_BACKUP_FOLDER"

mkdir -p "$SEARCH_WL_BACKUP_FOLDER"
echo "WL Backup folder created at: $SEARCH_WL_BACKUP_FOLDER"

# Ensure all necessary directories exist in the backup paths
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/css"
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/renderers/modules"
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/edit/modules"

mkdir -p "$SEARCH_WL_BACKUP_FOLDER/whitelabel/public_html/assets/css"
mkdir -p "$SEARCH_WL_BACKUP_FOLDER/whitelabel/public_html/templates"

echo "Created necessary directories under backup folders."




# Copy files to the correct destination directories
cp "$CSS_DIR" "$SEARCH_WL_BACKUP_FOLDER/public_html/assets/css/"
cp "$LS_RNDR_SEARCH" "$SEARCH_WL_BACKUP_FOLDER/whitelabel/public_html/templates/"


# Main website files copying
cp "$MAIN_CSS_DIR" "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/css/"
cp "$MAIN_RNDR_SEARCH" "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/renderers/modules/"
cp "$MAIN_MDL_ADMIN" "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/edit/modules/"



echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
