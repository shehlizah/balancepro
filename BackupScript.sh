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
REPORTS_MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
REPORTS_ADMIN_FINAL_BACKUP_FOLDER="$PWD/reports_admin_final_backup_$TIMESTAMP"



# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"


# BalancePro Main Variables

REPORTS_ADMIN_PART="${REPORTS_MAIN_SRC_DIR}partner-reports.php"


# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi


# Create the backup folder if it doesn't exist
mkdir -p "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER"
echo "REPORTS_ADMIN_FINAL_BACKUP_FOLDER created at: $REPORTS_ADMIN_FINAL_BACKUP_FOLDER"



# Ensure all necessary directories exist in the backup paths
mkdir -p "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme"

echo "Created necessary directories under backup folders."


# Main website files copying


cp "$REPORTS_ADMIN_PART" "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/" 



echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi

