#!/bin/bash

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
WHITELABEL_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/main.php"    #Slider
INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/core/"    #other all 

MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/includes/core/"
#BACKUP_DIR="/home/shahlizeh/finalChanges3Dec"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups

# Prompt the user to enter the folder name
read -p "Enter the folder name you want to use: " BACKUP_FOLDER

if [ -z "$BACKUP_FOLDER" ]; then
  echo "Error: No folder name provided. Exiting..."
  exit 1
fi

# BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"

echo "Source Directory: $SOURCE_DIR"
echo "Backup Directory: $BACKUP_DIR"
echo "Backup Folder: $BACKUP_FOLDER"

#balanceproMain
MAIN_CSS="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
RENDER_DIR_MAIN="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php"
TEMP_HOME="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/template-T01-homepage.php"


#WL
RENDER_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php"
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
PG_STG1="${INC_SOURCE_DIR}pagination_all_stage_check.php"
PG_CHECK_TAGS="${INC_SOURCE_DIR}pagination_check_tags.php"
PG_CHECK="${INC_SOURCE_DIR}pagination_check.php"
PG_LS="${INC_SOURCE_DIR}pagination_life_stage_check.php"
PG_NEW_CHECK="${INC_SOURCE_DIR}pagination_new_check.php"
PG_SORT_CHECK="${INC_SOURCE_DIR}pagination_sort_check.php"
PG_TAGS_CHECK="${INC_SOURCE_DIR}pagination_tags_check.php"
PG_BTN_LS="${INC_SOURCE_DIR}pg_btn_lifestage.php"
PG_BTN_NEW_LS="${INC_SOURCE_DIR}pg_btn_new_lifestage.php"
PG_BTN_TAGS="${INC_SOURCE_DIR}pg_btn_tags.php"
PGN_BTN_RS="${INC_SOURCE_DIR}pgn_btn_resource.php"
RS_ALL="${INC_SOURCE_DIR}resource_all_page.php"
RS_LS="${INC_SOURCE_DIR}resource_life_stages.php" 
RS_PG_ALL_NEW="${INC_SOURCE_DIR}resource_pagination_all_new.php"
RS_PG_BACK_CONTENT="${INC_SOURCE_DIR}resource_pagination_back_content.php"
RS_SEARCH_NEW="${INC_SOURCE_DIR}resource_all_page.php"
RS_SEARCH_PG_CONNTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
RS_SEARCH_NEW="${INC_SOURCE_DIR}resource_search_new.php"
RS_SORT_SEARCH="${INC_SOURCE_DIR}resource_sort_search.php"
RS_TAG_SEARCH="${INC_SOURCE_DIR}resource_tag_search.php"
# RS_TAGS="${INC_SOURCE_DIR}resource_tags.php"

MAIN_PG_STG1="${MAIN_SRC_DIR}pagination_all_stage_check.php"
MAIN_PG_CHECK_TAGS="${MAIN_SRC_DIR}pagination_check_tags.php"
MAIN_PG_CHECK="${MAIN_SRC_DIR}pagination_check.php"
MAIN_PG_LS="${MAIN_SRC_DIR}pagination_life_stage_check.php"
MAIN_PG_NEW_CHECK="${MAIN_SRC_DIR}pagination_new_check.php"
MAIN_PG_SORT_CHECK="${MAIN_SRC_DIR}pagination_sort_check.php"
MAIN_PG_TAGS_CHECK="${MAIN_SRC_DIR}pagination_tags_check.php"
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

Check if backup directory exists
if [ ! -e "$BACKUP_FOLDER" ]; then
  echo "Error: BACKUP directory ($BACKUP_FOLDER) does not exist."
  exit 1
fi


cp "$BACKUP_FOLDER" "$SOURCE_DIR"
cp "$BACKUP_FOLDER" "$RENDER_DIR" 
cp "$BACKUP_FOLDER" "$PG_STG1" 
cp "$BACKUP_FOLDER" "$PG_CHECK_TAGS" 
cp "$BACKUP_FOLDER" "$PG_CHECK"
cp "$BACKUP_FOLDER" "$PG_LS" 
cp "$BACKUP_FOLDER" "$PG_NEW_CHECK" 
cp "$BACKUP_FOLDER" "$PG_SORT_CHECK" 
cp "$BACKUP_FOLDER" "$PG_TAGS_CHECK"
cp "$BACKUP_FOLDER" "$PG_BTN_LS" 
cp "$BACKUP_FOLDER" "$PG_BTN_NEW_LS" 
cp "$BACKUP_FOLDER" "$PG_BTN_TAGS" 
cp "$BACKUP_FOLDER" "$PGN_BTN_RS" 
cp "$BACKUP_FOLDER" "$RS_ALL" 
cp "$BACKUP_FOLDER" "$RS_LS" 
cp "$BACKUP_FOLDER" "$RS_PG_ALL_NEW" 
cp "$BACKUP_FOLDER" "$RS_PG_BACK_CONTENT"
cp "$BACKUP_FOLDER" "$RS_SEARCH_PG_CONNTENT" 
cp "$BACKUP_FOLDER" "$RS_SEARCH_NEW" 
cp "$BACKUP_FOLDER" "$RS_SORT_SEARCH" 
cp "$BACKUP_FOLDER" "$RS_TAG_SEARCH" 

echo "Done copying inc files"

cp "$BACKUP_FOLDER" "$RENDER_DIR" 
cp "$BACKUP_FOLDER" "$CSS_DIR"

echo "NOW MAIN copying inc files"

cp "$BACKUP_FOLDER" "$MAIN_CSS" 
cp "$BACKUP_FOLDER" "$RENDER_DIR_MAIN" 
cp "$BACKUP_FOLDER" "$TEMP_HOME" 

cp "$BACKUP_FOLDER" "$MAIN_PG_STG1"  
cp "$BACKUP_FOLDER" "$MAIN_PG_CHECK_TAGS"  
cp "$BACKUP_FOLDER" "$MAIN_PG_CHECK"  
cp "$BACKUP_FOLDER" "$MAIN_PG_LS"  
cp "$BACKUP_FOLDER" "$MAIN_PG_NEW_CHECK"  
cp "$BACKUP_FOLDER" "$MAIN_PG_SORT_CHECK"  
cp "$BACKUP_FOLDER" "$MAIN_PG_TAGS_CHECK"  
cp "$BACKUP_FOLDER" "$MAIN_PG_BTN_LS"  
cp "$BACKUP_FOLDER" "$MAIN_PG_BTN_NEW_LS"  
cp "$BACKUP_FOLDER" "$MAIN_PG_BTN_TAGS"  
cp "$BACKUP_FOLDER" "$MAIN_PGN_BTN_RS"  
cp "$BACKUP_FOLDER" "$MAIN_RS_ALL"  
cp "$BACKUP_FOLDER" "$MAIN_RS_LS"  
cp "$BACKUP_FOLDER" "$MAIN_RS_PG_ALL_NEW"  
cp "$BACKUP_FOLDER" "$MAIN_RS_PG_BACK_CONT"  
cp "$BACKUP_FOLDER" "$MAIN_RS_SEARCH_PG_CO"  
cp "$BACKUP_FOLDER" "$MAIN_RS_SEARCH_NEW"  
cp "$BACKUP_FOLDER" "$MAIN_RS_SORT_SEARCH"  
cp "$BACKUP_FOLDER" "$MAIN_RS_TAG_SEARCH"  


echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Restore successful!"
else
  echo "Restore failed!"
fi
