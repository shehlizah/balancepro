#!/bin/bash

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"

INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/"

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups

# Ask the user if they want to restore from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders in the current working directory
RELATED_ARTICLES=$(ls -d $PWD/realted_articles_backup_* 2>/dev/null | sort | tail -n 1)


# Check if the directories exist
if [ -z "$RELATED_ARTICLES" ]; then
  echo "Error: No MAIN BACKUP directory found in the current working directory."
  exit 1
fi


echo "Detected LS FINAL BACKUP folder: $RELATED_ARTICLES"


# File paths for restoration (WhiteLabel)

#CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"

FN_RSC="${TMP_SOURCE_DIR}func_resource.php"





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
#cp  "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/" "$LS_RNDR_SEARCH"

cp "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/func_resource.php" "$FN_RSC"


echo "Done restoring WhiteLabel files."


# Final confirmation
echo "Restore process completed successfully!"
