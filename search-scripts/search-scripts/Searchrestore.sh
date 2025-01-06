#!/bin/bash

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups

# Ask the user if they want to restore from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders in the current working directory
SEARCH_MAIN_BACKUP_FOLDER=$(ls -d $PWD/search_main_backup_* 2>/dev/null | sort | tail -n 1)
SEARCH_WL_BACKUP_FOLDER=$(ls -d $PWD/search_wl_backup_* 2>/dev/null | sort | tail -n 1)

# Check if the directories exist
if [ -z "$SEARCH_MAIN_BACKUP_FOLDER" ]; then
  echo "Error: No MAIN BACKUP directory found in the current working directory."
  exit 1
fi

if [ -z "$SEARCH_WL_BACKUP_FOLDER" ]; then
  echo "Error: No WhiteLabel BACKUP directory found in the current working directory."
  exit 1
fi

echo "Detected MAIN BACKUP folder: $SEARCH_MAIN_BACKUP_FOLDER"
echo "Detected WL BACKUP folder: $SEARCH_WL_BACKUP_FOLDER"

# File paths for restoration (WhiteLabel)

CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"


# File paths for restoration (Main website)
MAIN_CSS_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"
# Function to copy files and check if it succeeded
cp() {
  src=$1
  dest=$2
  if cp "$src" "$dest"; then
    echo "Successfully copied $src to $dest"
  else
    echo "Failed to copy $src to $dest"
    exit 1
  fi
}

# Restore WhiteLabel files
echo "Restoring WhiteLabel files..."
cp  "$SEARCH_WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/" "$LS_RNDR_SEARCH"
cp "$SEARCH_WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$CSS_DIR"


echo "Done restoring WhiteLabel files."

# Restore Main website files
echo "Restoring Main website files..."
cp "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/" "$MAIN_CSS_DIR"
cp "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/" "$MAIN_RNDR_SEARCH" 
cp "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/" "$MAIN_MDL_ADMIN"


echo "Done restoring Main website files."

# Final confirmation
echo "Restore process completed successfully!"
