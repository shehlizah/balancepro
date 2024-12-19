#!/bin/bash

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
WHITELABEL_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/main.php"    #Slider
INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/core/"    #other all 

MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/includes/core/"
#BACKUP_DIR="/home/shahlizeh/finalChanges3Dec"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"

# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"

#balanceproMain
MAIN_CSS="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
RENDER_DIR_MAIN="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php"
TEMP_HOME="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/template-T01-homepage.php"


#WL
RENDER_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php"
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"

PG_STG1="${INC_SOURCE_DIR}pagination_all_stage_check.php"
# PG_CHECK_TAGS="${INC_SOURCE_DIR}pagination_check_tags.php"
PG_CHECK="${INC_SOURCE_DIR}pagination_check.php"
PG_LS="${INC_SOURCE_DIR}pagination_life_stage_check.php"
PG_NEW_CHECK="${INC_SOURCE_DIR}pagination_new_check.php"
PG_SORT_CHECK="${INC_SOURCE_DIR}pagination_sort_check.php"
# PG_TAGS_CHECK="${INC_SOURCE_DIR}pagination_tags_check.php"
PG_BTN_LS="${INC_SOURCE_DIR}pg_btn_lifestage.php"
# PG_BTN_NEW_LS="${INC_SOURCE_DIR}pg_btn_new_lifestage.php"
PG_BTN_TAGS="${INC_SOURCE_DIR}pg_btn_tags.php"
PGN_BTN_RS="${INC_SOURCE_DIR}pgn_btn_resource.php"
RS_ALL="${INC_SOURCE_DIR}resource_all_page.php"
RS_LS="${INC_SOURCE_DIR}resource_life_stages.php" 
RS_PG_ALL_NEW="${INC_SOURCE_DIR}resource_pagination_all_new.php"
RS_PG_BACK_CONTENT="${INC_SOURCE_DIR}resource_pagination_back_content.php"
RS_ALL_PAGE="${INC_SOURCE_DIR}resource_all_page.php"
#RS_SEARCH_PG_CONNTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
RS_SEARCH_NEW="${INC_SOURCE_DIR}resource_search_new.php"
RS_SORT_SEARCH="${INC_SOURCE_DIR}resource_sort_search.php"
RS_TAG_SEARCH="${INC_SOURCE_DIR}resource_tag_search.php"
SEARCH_QUERY="${INC_SOURCE_DIR}search_query.php"
WL_SQP= "${INC_SOURCE_DIR}search_query_pagination.php"
RS_PG_CONTENT= "${INC_SOURCE_DIR}resource_pagination_content.php"
RS_SEARCH= "${INC_SOURCE_DIR}resource_search.php"

MAIN_RS_PG_CONTENT= "${MAIN_SRC_DIR}resource_pagination_content.php"
MAIN_RS_SEARCH= "${MAIN_SRC_DIR}resource_search.php"
MAIN_SEARCH_QUERY="${MAIN_SRC_DIR}search_query.php"
MAIN_SQ_PG="${MAIN_SRC_DIR}search_query_pagination.php"
#MAIN_PG_SORT="${MAIN_SRC_DIR}pagination_sort_check.php"
MAIN_PG_STG1="${MAIN_SRC_DIR}pagination_all_stage_check.php"
#MAIN_PG_CHECK_TAGS="${MAIN_SRC_DIR}pagination_check_tags.php"
MAIN_PG_CHECK="${MAIN_SRC_DIR}pagination_check.php"
MAIN_PG_LS="${MAIN_SRC_DIR}pagination_life_stage_check.php"
MAIN_PG_NEW_CHECK="${MAIN_SRC_DIR}pagination_new_check.php"
MAIN_PG_SORT_CHECK="${MAIN_SRC_DIR}pagination_sort_check.php"
#MAIN_PG_TAGS_CHECK="${MAIN_SRC_DIR}pagination_tags_check.php"
MAIN_PG_BTN_LS="${MAIN_SRC_DIR}pg_btn_lifestage.php"
MAIN_PG_BTN_NEW_LS="${MAIN_SRC_DIR}pg_btn_new_lifestage.php"
MAIN_PG_BTN_TAGS="${MAIN_SRC_DIR}pg_btn_tags.php"
MAIN_PGN_BTN_RS="${MAIN_SRC_DIR}pgn_btn_resource.php"
MAIN_RS_ALL="${MAIN_SRC_DIR}resource_all_page.php"
MAIN_RS_LS="${MAIN_SRC_DIR}resource_life_stages.php" 
MAIN_RS_PG_ALL_NEW="${MAIN_SRC_DIR}resource_pagination_all_new.php"
MAIN_RS_PG_BACK_CONTENT="${MAIN_SRC_DIR}resource_pagination_back_content.php"
# MAIN_RS_SEARCH_NEW="${MAIN_SRC_DIR}resource_all_page.php"
MAIN_RS_SEARCH_PG_CONNTENT="${MAIN_SRC_DIR}resource_search_pagination_content.php"
MAIN_RS_SEARCH_NEW="${MAIN_SRC_DIR}resource_search_new.php"
MAIN_RS_SORT_SEARCH="${MAIN_SRC_DIR}resource_sort_search.php"
MAIN_RS_TAG_SEARCH="${MAIN_SRC_DIR}resource_tag_search.php"
MAIN_RS_TAGS="${MAIN_SRC_DIR}resource_tags.php"

# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi

# Create the backup folder if it doesn't exist
mkdir -p "$BACKUP_FOLDER"
echo "Backup folder created at: $BACKUP_FOLDER"

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

cp "$SOURCE_DIR" "$BACKUP_FOLDER"
cp "$RENDER_DIR" "$BACKUP_FOLDER"
cp "$PG_STG1" "$BACKUP_FOLDER"
#cp "$PG_CHECK_TAGS" "$BACKUP_FOLDER"
cp "$PG_CHECK" "$BACKUP_FOLDER"
cp "$PG_LS" "$BACKUP_FOLDER"
cp "$PG_NEW_CHECK" "$BACKUP_FOLDER"
cp "$PG_SORT_CHECK" "$BACKUP_FOLDER"
#cp "$PG_TAGS_CHECK" "$BACKUP_FOLDER"
cp "$PG_BTN_LS" "$BACKUP_FOLDER"
#cp "$PG_BTN_NEW_LS" "$BACKUP_FOLDER"
cp "$PG_BTN_TAGS" "$BACKUP_FOLDER"
cp "$PGN_BTN_RS" "$BACKUP_FOLDER"
cp "$RS_ALL_PAGE" "$BACKUP_FOLDER"
cp "$RS_LS" "$BACKUP_FOLDER"
cp "$RS_PG_ALL_NEW" "$BACKUP_FOLDER"
cp "$RS_PG_BACK_CONTENT" "$BACKUP_FOLDER"
#cp "$RS_SEARCH_PG_CONNTENT" "$BACKUP_FOLDER"
cp "$RS_SEARCH_NEW" "$BACKUP_FOLDER"
cp "$RS_SORT_SEARCH" "$BACKUP_FOLDER"
cp "$RS_TAG_SEARCH" "$BACKUP_FOLDER"
cp "$SEARCH_QUERY" "$BACKUP_FOLDER"
cp "$WL_SQP" "$BACKUP_FOLDER"
cp "$RS_PG_CONTENT" "$BACKUP_FOLDER"
cp "$RS_SEARCH" "$BACKUP_FOLDER"

echo "Done copying inc files"

cp "$RENDER_DIR" "$BACKUP_FOLDER"
cp "$CSS_DIR" "$BACKUP_FOLDER"

echo "NOW MAIN copying inc files"

cp "$MAIN_CSS" "$BACKUP_FOLDER"
cp "$RENDER_DIR_MAIN" "$BACKUP_FOLDER"
cp "$TEMP_HOME" "$BACKUP_FOLDER"

cp "$MAIN_PG_STG1" "$BACKUP_FOLDER"
#cp "$MAIN_PG_CHECK_TAGS" "$BACKUP_FOLDER"
cp "$MAIN_PG_CHECK" "$BACKUP_FOLDER"
cp "$MAIN_PG_LS" "$BACKUP_FOLDER"
cp "$MAIN_PG_NEW_CHECK" "$BACKUP_FOLDER"
cp "$MAIN_PG_SORT_CHECK" "$BACKUP_FOLDER"
cp "$MAIN_PG_TAGS_CHECK" "$BACKUP_FOLDER"
cp "$MAIN_PG_BTN_LS" "$BACKUP_FOLDER"
cp "$MAIN_PG_BTN_NEW_LS" "$BACKUP_FOLDER"
cp "$MAIN_PG_BTN_TAGS" "$BACKUP_FOLDER"
cp "$MAIN_PGN_BTN_RS" "$BACKUP_FOLDER"
cp "$MAIN_RS_ALL" "$BACKUP_FOLDER"
cp "$MAIN_RS_LS" "$BACKUP_FOLDER"
cp "$MAIN_RS_PG_ALL_NEW" "$BACKUP_FOLDER"
cp "$MAIN_RS_PG_BACK_CONTENT" "$BACKUP_FOLDER"
cp "$MAIN_RS_SEARCH_PG_CONNTENT" "$BACKUP_FOLDER"
cp "$MAIN_RS_SEARCH_NEW" "$BACKUP_FOLDER"
cp "$MAIN_RS_SORT_SEARCH" "$BACKUP_FOLDER"
cp "$MAIN_RS_TAG_SEARCH" "$BACKUP_FOLDER"
cp "$MAIN_RS_PG_CONTEN" "$BACKUP_FOLDER"
cp "$MAIN_RS_SEARCH" "$BACKUP_FOLDER"
cp "$MAIN_SEARCH_QUERY" "$BACKUP_FOLDER"
cp "$MAIN_SQ_PG" "$BACKUP_FOLDER"
cp "$MAIN_RS_TAGS" "$BACKUP_FOLDER"

echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
