#!/bin/bash
# set -x

#sudo su balancepro 
# SRC_DIR="C:\Users\dell\tests"
# # Check if source directory exists
# if [ ! -e "$SRC_DIR" ]; then
#   mkdir -p $SRC_DIR;
#   echo "Error: Source directory ($SRC_DIR) does not exist."
#    exit 1
# fi

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
#WHITELABEL_SOURCE_DIR="$PWD/domains/whitelabel.balancepro.org/public_html/"
TMP_SOURCE_DIR="$PWD/domains/whitelabel.balancepro.org/public_html/templates/"
INC_SOURCE_DIR="$PWD/domains/whitelabel.balancepro.org/public_html/includes/core/"    #other all 
MAIN_RNDR_SOURCE_DIR="$PWD/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
LS_MAIN_SRC_DIR="$PWD/domains/balancepro.org/public_html/includes/core/"
#BACKUP_DIR="/home/shahlizeh/finalChanges3Dec"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
LS_MAIN_BACKUP_FOLDER="$PWD/ls_main_backup_$TIMESTAMP"
LS_BACKUP_FOLDER="$PWD/ls_backup_$TIMESTAMP"
# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"


#WL
CONF_DIR="$PWD/domains/whitelabel.balancepro.org/public_html/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"



#balanceproMain

LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"

# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi

# Create the backup folder if it doesn't exist
mkdir -p "$LS_MAIN_BACKUP_FOLDER"
echo "MAIN Backup folder created at: $LS_MAIN_BACKUP_FOLDER"

mkdir -p "$LS_BACKUP_FOLDER"
echo "WL Backup folder created at: $LS_BACKUP_FOLDER"
# Create the necessary directories for Whitelabel files
mkdir -p "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates"
mkdir -p "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes"
mkdir -p "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core"

# You can add more directories as necessary based on your structure

# Create the necessary directories for BalancePro files


mkdir -p "$LS_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core"
mkdir -p "$LS_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules"

echo "Directories created successfully."

# Example: If you need to create directories for other paths
#mkdir -p "$PWD/domains/balancepro.org/public_html/wp-content/themes/balance-theme/template-T01-homepage.php"



# # Check if source is a directory or file
# # if [ -d "$SOURCE_DIR" ]; then
# #   # If it's a directory, copy the contents recursively
# #   echo "Copying directory contents..."
# #   cp -R "$SOURCE_DIR"/* "$BACKUP_FOLDER"
# # elif [ -f "$SOURCE_DIR" ]; then
# #   # If it's a single file, just copy it
# #   echo "Copying files..."
# #   cp "$SOURCE_DIR" "$BACKUP_FOLDER"
# # else
# #   echo "Error: $SOURCE_DIR is neither a file nor a directory."
# #   exit 1
# # fi

cp "$CONF_DIR" "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/"
cp "$LIFESTAGE" "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/"
cp "$LS" "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/"
cp "$LS_SQP" "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$LS_RS_SEARCH_PG_CONTENT" "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$LS_CORE" "$LS_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"

echo "Done copying inc files"


echo "NOW MAIN copying inc files"


cp "$LS_MAIN_SQ_PG" "$LS_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$LS_MAIN_BACKUP_FOLDER" "$LS_MAIN_RMRS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/" 
cp "$LS_MAIN_BACKUP_FOLDER" "$LS_MAIN_RSMDL/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/" 


echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi