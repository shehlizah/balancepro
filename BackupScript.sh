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
WHITELABEL_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/main.php"    #Slider
INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/core/"    #other all 

MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/includes/core/"
#BACKUP_DIR="/home/shahlizeh/finalChanges3Dec"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
MAIN_BACKUP_FOLDER="$HOME/main_backup_$TIMESTAMP"
WL_BACKUP_FOLDER="$HOME/wl_backup_$TIMESTAMP"
# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"


#WL
RENDER_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php"
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
PG_STG1="${INC_SOURCE_DIR}pagination_all_stage_check.php"
PG_CHECK_TAGS="${INC_SOURCE_DIR}pagination_check_tags.php"
PG_CHECK="${INC_SOURCE_DIR}pagination_check.php"
PG_LS="${INC_SOURCE_DIR}pagination_life_stage_check.php"
PG_NEW_CHECK="${INC_SOURCE_DIR}pagination_new_check.php"
PG_SORT_CHECK="${INC_SOURCE_DIR}pagination_sort_check.php"
# PG_TAGS_CHECK="${INC_SOURCE_DIR}pagination_tags_check.php"
PG_TAGS_NCHECK="${INC_SOURCE_DIR}pagination_tags_n_check.php"
PG_BTN_LS="${INC_SOURCE_DIR}pg_btn_lifestage.php"
PG_BTN_NEW_LS="${INC_SOURCE_DIR}pg_btn_new_lifestage.php"
PG_BTN_TAGS="${INC_SOURCE_DIR}pg_btn_tags.php"
PGN_BTN_RS="${INC_SOURCE_DIR}pgn_btn_resource.php"
RS_ALL="${INC_SOURCE_DIR}resource_all_page.php"
RS_LS="${INC_SOURCE_DIR}resource_life_stages.php" 
RS_PG_ALL_NEW="${INC_SOURCE_DIR}resource_pagination_all_new.php"
RS_PG_BACK_CONTENT="${INC_SOURCE_DIR}resource_pagination_back_content.php"
# RS_SEARCH_PG_CONNTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
RS_PG_CONTENT="${INC_SOURCE_DIR}resource_pagination_content.php"
RS_SEARCH="${INC_SOURCE_DIR}resource_search.php"
RS_SEARCH_NEW="${INC_SOURCE_DIR}resource_search_new.php"
RS_SORT_SEARCH="${INC_SOURCE_DIR}resource_sort_search.php"
RS_TAG_SEARCH="${INC_SOURCE_DIR}resource_tag_search.php"
SEARCH_QUERY="${INC_SOURCE_DIR}search_query.php"
WL_SQP="${INC_SOURCE_DIR}search_query_pagination.php"


#balanceproMain
MAIN_CSS="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
RENDER_DIR_MAIN="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php"
# TEMP_HOME="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/template-T01-homepage.php"
MAIN_PG_SORT="${MAIN_SRC_DIR}pagination_sort_check.php"
MAIN_PG_STG1="${MAIN_SRC_DIR}pagination_all_stage_check.php"
MAIN_PG_CHECK="${MAIN_SRC_DIR}pagination_check.php"
# MAIN_PG_CHECK_TAGS="${MAIN_SRC_DIR}pagination_check_tags.php"
MAIN_PG_LS="${MAIN_SRC_DIR}pagination_life_stage_check.php"
MAIN_PG_NEW_CHECK="${MAIN_SRC_DIR}pagination_new_check.php"
#MAIN_PG_SORT_CHECK="${MAIN_SRC_DIR}pagination_sort_check.php"
MAIN_PG_TAGS_CHECK="${MAIN_SRC_DIR}pagination_tags_check.php"
MAIN_PG_TAGS_NCHECK="${MAIN_SRC_DIR}pagination_tags_n_check.php"
MAIN_PG_BTN_LS="${MAIN_SRC_DIR}pg_btn_lifestage.php"
MAIN_PG_BTN_NEW_LS="${MAIN_SRC_DIR}pg_btn_new_lifestage.php"
MAIN_PG_BTN_TAGS="${MAIN_SRC_DIR}pg_btn_tags.php"
MAIN_PGN_BTN_RS="${MAIN_SRC_DIR}pgn_btn_resource.php"
MAIN_RS_ALL="${MAIN_SRC_DIR}resource_all_page.php"
MAIN_RS_LS="${MAIN_SRC_DIR}resource_life_stages.php" 
MAIN_RS_PG_ALL_NEW="${MAIN_SRC_DIR}resource_pagination_all_new.php"
MAIN_RS_PG_BACK_CONTENT="${MAIN_SRC_DIR}resource_pagination_back_content.php"
MAIN_RS_SEARCH_PG_CONTENT="${MAIN_SRC_DIR}resource_search_pagination_content.php"
MAIN_RS_SEARCH="${MAIN_SRC_DIR}resource_search.php"
MAIN_RS_SEARCH_NEW="${MAIN_SRC_DIR}resource_search_new.php"
MAIN_RS_SORT_SEARCH="${MAIN_SRC_DIR}resource_sort_search.php"
MAIN_RS_TAG_SEARCH="${MAIN_SRC_DIR}resource_tag_search.php"
MAIN_RS_TAGS="${MAIN_SRC_DIR}resource_tags.php"
MAIN_RS_PG_CONTENT="${MAIN_SRC_DIR}resource_pagination_content.php"
MAIN_SEARCH_QUERY="${MAIN_SRC_DIR}search_query.php"
MAIN_SQ_PG="${MAIN_SRC_DIR}search_query_pagination.php"

# # Check if source directory exists
# #if [ ! -e "$SOURCE_DIR" ]; then
# #   echo "Error: Source directory ($SOURCE_DIR) does not exist."
# #   exit 1
# # fi

# Create the backup folder if it doesn't exist
mkdir -p "$MAIN_BACKUP_FOLDER"
echo "MAIN Backup folder created at: $MAIN_BACKUP_FOLDER"

mkdir -p "$WL_BACKUP_FOLDER"
echo "WL Backup folder created at: $WL_BACKUP_FOLDER"
# Create the necessary directories for Whitelabel files
mkdir -p "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates"
mkdir -p "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css"
mkdir -p "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core"

# You can add more directories as necessary based on your structure

# Create the necessary directories for BalancePro files

mkdir -p "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css"
mkdir -p "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules"
mkdir -p "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core"

echo "Directories created successfully."

# Example: If you need to create directories for other paths
#mkdir -p "$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/template-T01-homepage.php"



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

cp "$RENDER_DIR" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/"
cp "$CSS_DIR" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/"
cp "$PG_STG1" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
#cp "$PG_CHECK_TAGS" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PG_CHECK" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PG_LS" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PG_NEW_CHECK" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PG_SORT_CHECK" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PG_TAGS_NCHECK" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PG_BTN_LS" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
#cp "$PG_BTN_NEW_LS" "$BACKUP_FOLDER"
cp "$PG_BTN_TAGS" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$PGN_BTN_RS" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_ALL" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_LS" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_PG_ALL_NEW" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_PG_BACK_CONTENT" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
#cp "$RS_SEARCH_PG_CONNTENT" "$WL_BACKUP_FOLDER"
cp "$RS_SEARCH_NEW" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_SORT_SEARCH" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_TAG_SEARCH" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$SEARCH_QUERY" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$WL_SQP" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_PG_CONTENT" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"
cp "$RS_SEARCH" "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/"

echo "Done copying inc files"


echo "NOW MAIN copying inc files"

cp "$MAIN_CSS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/"
cp "$RENDER_DIR_MAIN" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
# cp "$TEMP_HOME" "$MAIN_BACKUP_FOLDER"
cp "$MAIN_PG_STG1" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
#cp "$MAIN_PG_CHECK_TAGS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_CHECK" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_LS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_NEW_CHECK" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_SORT" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_TAGS_CHECK" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_TAGS_NCHECK" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_BTN_LS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_BTN_NEW_LS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PG_BTN_TAGS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_PGN_BTN_RS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_ALL" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_LS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_PG_ALL_NEW" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_PG_BACK_CONTENT" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_SEARCH_PG_CONTENT" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_SEARCH_NEW" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_SORT_SEARCH" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_TAG_SEARCH" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_PG_CONTENT" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_SEARCH" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_SEARCH_QUERY" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_SQ_PG" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"
cp "$MAIN_RS_TAGS" "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/"

echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
