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
TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
SEARCH_MAIN_BACKUP_FOLDER="$PWD/search_main_backup_$TIMESTAMP"
SEARCH_WL_BACKUP_FOLDER="$PWD/search_WL_backup_$TIMESTAMP"
# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"


#WL
LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"



#balanceproMain
MAIN_CSS_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"

# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi

# Create the backup folder if it doesn't exist
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER"
echo "MAIN Backup folder created at: $SEARCH_MAIN_BACKUP_FOLDER"

mkdir -p "$SEARCH_WL_BACKUP_FOLDER"
echo "WL Backup folder created at: $SEARCH_WL_BACKUP_FOLDER"
# Create the necessary directories for Whitelabel files
mkdir -p "$SEARCH_WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css"
mkdir -p "$SEARCH_WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates"


# You can add more directories as necessary based on your structure

# Create the necessary directories for BalancePro files

mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css"
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules"
mkdir -p "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules"

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

cp "$CSS_DIR" "$SEARCH_WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/"
cp "$LS_RNDR_SEARCH" "$SEARCH_WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/"


echo "Done copying inc files"


echo "NOW MAIN copying inc files"

cp "$MAIN_CSS_DIR" "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/"
cp "$MAIN_RNDR_SEARCH" "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
cp "$MAIN_MDL_ADMIN" "$SEARCH_MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/"


echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
