#!/bin/bash

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/core/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
LS_MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/includes/core/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups

# Ask the user if they want to restore from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders in the current working directory
LS_MAIN_BACKUP_FOLDER=$(ls -d $PWD/main_backup_* 2>/dev/null | sort | tail -n 1)
LS_BACKUP_FOLDER=$(ls -d $PWD/wl_backup_* 2>/dev/null | sort | tail -n 1)

# Check if the directories exist
if [ -z "$LS_MAIN_BACKUP_FOLDER" ]; then
  echo "Error: No MAIN BACKUP directory found in the current working directory."
  exit 1
fi

if [ -z "$LS_BACKUP_FOLDER" ]; then
  echo "Error: No WhiteLabel BACKUP directory found in the current working directory."
  exit 1
fi

echo "Detected MAIN BACKUP folder: $LS_MAIN_BACKUP_FOLDER"
echo "Detected WL BACKUP folder: $LS_BACKUP_FOLDER"

# File paths for restoration (WhiteLabel)
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
CONF_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"

# File paths for restoration (Main website)
LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"

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
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$CSS_DIR"
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/config.php" "$CONF_DIR"
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/lifestage.php" "$LIFESTAGE"
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/ls.php" "$LS"
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/search_query_pagination.php" "$LS_SQP"
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_search_pagination_content.php" "$LS_RS_SEARCH_PG_CONTENT"
cp "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/core.php" "$LS_CORE"

echo "Done restoring WhiteLabel files."

# Restore Main website files
echo "Restoring Main website files..."
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/search_query_pagination.php" "$LS_MAIN_SQ_PG"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-module-M14-15-resources-search.php" "$LS_MAIN_RMRS"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design-lifestage.php" "$LS_MAIN_RSMDL"

echo "Done restoring Main website files."

# Final confirmation
echo "Restore process completed successfully!"
