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
SEARCH_FINAL_BACKUP_FOLDER=$(ls -d $PWD/search_final_backup_* 2>/dev/null | sort | tail -n 1)


# Check if the directories exist
if [ -z "$SEARCH_FINAL_BACKUP_FOLDER" ]; then
  echo "Error: No SEARCH FINAL BACKUP directory found in the current working directory."
  exit 1
fi



echo "Detected MAIN BACKUP folder: $SEARCH_FINAL_BACKUP_FOLDER"


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

# Copy files from the WhiteLabel backup
echo "Restoring WhiteLabel files..."
cp  "$SEARCH_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$CSS_DIR"
cp  "$SEARCH_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php" "$LS_RNDR_SEARCH"


# MAIN files to restore
echo "Restoring MAIN files..."
cp  "$SEARCH_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css" "$MAIN_CSS_DIR"
cp  "$SEARCH_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php" "$MAIN_RNDR_SEARCH"
cp  "$SEARCH_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" "$MAIN_MDL_ADMIN"


echo "Done restoring Main website files."

# Final confirmation
echo "Restore process completed successfully!"
