#!/bin/bash
# set -x
HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"
# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
#WHITELABEL_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"
TMP_SOURCE_DIR="$HOME_WL/templates/"
INC_SOURCE_DIR="$HOME_WL/includes/core/"    #other all 
MAIN_RNDR_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/"
LS_MAIN_SRC_DIR="$HOME_MAIN/includes/core/"
# BACKUP_DIR="/home/shahlizeh/finalChanges3Dec"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups

# Prompt the user to enter the folder name
read -p "Enter the Main folder name you want to use: " USER_MAIN_BACKUP

if [ -z "$USER_MAIN_BACKUP" ]; then
  echo "Error: No Main folder name provided. Exiting..."
  exit 1
fi

read -p "Enter the WhiteLabel folder name you want to use: " USER_WL_BACKUP

if [ -z "$USER_WL_BACKUP" ]; then
  echo "Error: No WhiteLabel folder name provided. Exiting..."
  exit 1
fi

# Append the folders to the $HOME directory
LS_MAIN_BACKUP_FOLDER="$HOME_MAIN/$USER_MAIN_BACKUP"
LS_BACKUP_FOLDER="$HOME_WL/$USER_WL_BACKUP"

# Check if the directories exist
if [ ! -d "$LS_MAIN_BACKUP_FOLDER" ]; then
  echo "Error: MAIN BACKUP directory ($LS_MAIN_BACKUP_FOLDER) does not exist."
  exit 1
fi

if [ ! -d "$LS_BACKUP_FOLDER" ]; then
  echo "Error: WhiteLabel BACKUP directory ($LS_BACKUP_FOLDER) does not exist."
  exit 1
fi

if [ ! -e "$LS_MAIN_BACKUP_FOLDER" ]; then
  echo "Error: MAIN BACKUP directory ($MAIN_BACKUP_FOLDER) does not exist."
fi

if [ ! -e "$LS_BACKUP_FOLDER" ]; then
  echo "Error: WhiteLabel BACKUP directory ($WL_BACKUP_FOLDER) does not exist."
fi

#WL
CONF_DIR="$HOME_WL/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"



#balanceproMain
LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"



# Copy files from the WhiteLabel backup
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/congig.php" "$CONF_DIR"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/templates/lifestage.php" "$LIFESTAGE"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/templates/ls.php" "$LS"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/core/search_query_pagination.php" "$LS_SQP"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/core/resource_search_pagination_content.php" "$LS_RS_SEARCH_PG_CONTENT"
cp "$LS_BACKUP_FOLDER/whitelabel/public_html/includes/core/core.php" "$LS_CORE"
echo "Done copying inc files"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Restore successful!"
else
  echo "Restore failed!"
fi


echo "NOW MAIN copying inc files"


cp "$LS_MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/search_query_pagination.php" "$LS_MAIN_SQ_PG"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/renderers/modules/render-module-M14-15-resources-search.php" "$LS_MAIN_RMRS"
cp "$LS_MAIN_BACKUP_FOLDER/balancepro/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design-lifestage.php" "$LS_MAIN_RSMDL"



echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Restore successful!"
else
  echo "Restore failed!"
fi
