#!/bin/bash

# Prompt the user for restoring from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders
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

# Define restoration paths
HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

# WL files to restore
#CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
IND_DIR="$HOME_WL/public_html/includes/index.php"
CONF_DIR="$HOME_WL/includes/config.php"
#LS_RNDR_SEARCH="$HOME_WL/templates/render-search-main-design.php"
LIFESTAGE="$HOME_WL/templates/lifestage.php"
LS="$HOME_WL/templates/ls.php"
LS_SQP="$HOME_WL/includes/core/search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="$HOME_WL/includes/core/resource_search_pagination_content.php"
LS_CORE="$HOME_WL/includes/core/core.php"

# MAIN files to restore
#MAIN_CSS_DIR="$HOME_MAIN/wp-content/themes/balance-theme/css/main.min_new.css"
LS_MAIN_SQ_PG="$HOME_MAIN/includes/core/search_query_pagination.php"
LS_MAIN_RMRS="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/render-module-M14-15-resources-search.php"
#MAIN_RNDR_SEARCH="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php"
LS_MAIN_RSMDL="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design-lifestage.php"

# Copy files from the WhiteLabel backup
echo "Restoring WhiteLabel files..."
#cp "$LS_BACKUP_FOLDER/whitelabel/public_html/assets/css/main.min.css" "$CSS_DIR"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/index.php" "$IND_DIR"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/config.php" "$CONF_DIR" || echo "Failed to restore config.php"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/templates/lifestage.php" "$LIFESTAGE" || echo "Failed to restore lifestage.php"
#cp  "$LS_BACKUP_FOLDER/whitelabel/public_html/templates/" "$LS_RNDR_SEARCH"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/templates/ls.php" "$LS" || echo "Failed to restore ls.php"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/core/search_query_pagination.php" "$LS_SQP" || echo "Failed to restore search_query_pagination.php"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/core/resource_search_pagination_content.php" "$LS_RS_SEARCH_PG_CONTENT" || echo "Failed to restore resource_search_pagination_content.php"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/core/core.php" "$LS_CORE" || echo "Failed to restore core.php"

# MAIN files to restore
echo "Restoring MAIN files..."
#cp "$LS_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/css/" "$MAIN_CSS_DIR"
#cp "$LS_MAIN_BACKUP_FOLDER/balancepro/public_html/wp-content/themes/balance-theme/inc/renderers/modules/" "$MAIN_RNDR_SEARCH"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/includes/core/search_query_pagination.php" "$LS_MAIN_SQ_PG" || echo "Failed to restore search_query_pagination.php"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/renderers/modules/render-module-M14-15-resources-search.php" "$LS_MAIN_RMRS" || echo "Failed to restore render-module-M14-15-resources-search.php"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design-lifestage.php" "$LS_MAIN_RSMDL" || echo "Failed to restore render-search-main-design-lifestage.php"

echo "Restore process completed!"
