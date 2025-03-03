#!/bin/bash

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"

BALANCEPRO_CSS_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin/"
BALANCEPRO_FILE_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/"
BALANCEPRO_CHAT_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-admin/"

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups

# Ask the user if they want to restore from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders in the current working directory
CHECKBOX_CSS=$(ls -d $PWD/checkbox_css_backup_* 2>/dev/null | sort | tail -n 1)


# Check if the directories exist
if [ -z "$CHECKBOX_CSS" ]; then
  echo "Error: No MAIN BACKUP directory found in the current working directory."
  exit 1
fi


echo "Detected CHECKBOX_CSS folder: $CHECKBOX_CSS"


# File paths for restoration (WhiteLabel)
CHK_BX="${BALANCEPRO_CSS_SOURCE_DIR}admin.css"
CHK_FL="${BALANCEPRO_FILE_SOURCE_DIR}edit-module-white-label-website-programs.php"
CHK_CHAT="${BALANCEPRO_CHAT_SOURCE_DIR}chat.php"
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
echo "Restoring  files..."


cp  "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin/" "$CHK_BX"
cp  "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/" "$CHK_FL"
cp  "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-admin/" "$CHK_CHAT"


echo "Done restoring files."


# Final confirmation
echo "Restore process completed successfully!"
