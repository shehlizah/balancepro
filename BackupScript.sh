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
WHITELABEL_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/"

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
RELATED_ARTICLES="$PWD/realted_articles_backup_$TIMESTAMP"



# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"


# Whitelabel Variables
RLT_ARTICLES="${WHITELABEL_SOURCE_DIR}func_resource.php"


# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi


# Create the backup folder if it doesn't exist
mkdir -p "$RELATED_ARTICLES"
echo "RELATED_ARTICLES created at: $RELATED_ARTICLES"



# Ensure all necessary directories exist in the backup paths
mkdir -p "$RELATED_ARTICLES/whitelabel/domains/whitelabel.balancepro.org/public_html/includes"

echo "Created necessary directories under backup folders."


# Main website files copying

cp "$RLT_ARTICLES" "$RELATED_ARTICLES/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/"



echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi

