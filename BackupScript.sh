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
BALANCEPRO_CSS_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin/"
BALANCEPRO_FILE_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/"
BALANCEPRO_CHAT_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-admin/"

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
CHECKBOX_CSS="$PWD/checkbox_css_backup_$TIMESTAMP"



# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"


#  Variables
CHK_BX="${BALANCEPRO_CSS_SOURCE_DIR}admin.css"
CHK_FL="${BALANCEPRO_FILE_SOURCE_DIR}edit-module-white-label-website-programs.php"
CHK_CHAT="${BALANCEPRO_CHAT_SOURCE_DIR}chat.php"


# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi


# Create the backup folder if it doesn't exist
mkdir -p "$CHECKBOX_CSS"
echo "CHECKBOX_CSS created at: $CHECKBOX_CSS"



# Ensure all necessary directories exist in the backup paths
mkdir -p "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin"
mkdir -p "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules"
mkdir -p "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-admin"

echo "Created necessary directories under backup folders."


# Main website files copying

cp "$CHK_BX" "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin/"
cp "$CHK_FL" "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/"
cp "$CHK_CHAT" "$CHECKBOX_CSS/balancepro/domains/balancepro.org/public_html/wp-admin/"



echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi

