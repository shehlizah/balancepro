 #!/bin/bash
#set -x

HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
#WHITELABEL_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"
TMP_SOURCE_DIR="$HOME_WL/templates/"
INC_SOURCE_DIR="$HOME_WL/includes/core/"    #other all 
MAIN_RNDR_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/"
LS_MAIN_SRC_DIR="$HOME_MAIN/includes/core/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
LS_FINAL_BACKUP_FOLDER="$PWD/ls_final_backup_$TIMESTAMP"


# WL Variables
#LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"
#CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
IND_DIR="$HOME_WL/public_html/index.php"
CONF_DIR="$HOME_WL/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"

# BalancePro Main Variables
#MAIN_CSS_DIR="$HOME_MAIN/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
#MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"

# Create the backup folder if it doesn't exist
mkdir -p "$LS_FINAL_BACKUP_FOLDER"
echo "LS Final Backup folder created at: $LS_FINAL_BACKUP_FOLDER"



# Ensure all necessary directories exist in the backup paths
#mkdir -p "$LS_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/css"
mkdir -p "$LS_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core"
mkdir -p "$LS_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules"
mkdir -p "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core"
mkdir -p "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html"
mkdir -p "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates"
mkdir -p "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes"
echo "Created necessary directories under backup folders."




# Copy files to the correct destination directories

cp "$IND_DIR" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/"
cp "$CONF_DIR" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/"
cp "$LIFESTAGE" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/"
cp "$LS" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/"
cp "$LS_SQP" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$LS_RS_SEARCH_PG_CONTENT" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$LS_CORE" "$LS_FINAL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"


# Main website files copying

cp "$LS_MAIN_SQ_PG" "$LS_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$LS_MAIN_RMRS" "$LS_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/" 
cp "$LS_MAIN_RSMDL" "$LS_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/" 


echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
