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
read -p "Enter the Main folder name you want to use: " USER_MAIN_BACKUP

if [ -z "$USER_MAIN_BACKUP" ]; then
  echo "Error: No Main folder name provided. Exiting..."
fi

read -p "Enter the WhiteLabel folder name you want to use: " USER_WL_BACKUP

if [ -z "$USER_WL_BACKUP" ]; then
  echo "Error: No WhiteLabel folder name provided. Exiting..."
fi

# Append the folders to the $HOME directory
MAIN_BACKUP_FOLDER="$HOME/${USER_MAIN_BACKUP}"
WL_BACKUP_FOLDER="$HOME/${USER_WL_BACKUP}"

if [ ! -e "$MAIN_BACKUP_FOLDER" ]; then
  echo "Error: MAIN BACKUP directory ($MAIN_BACKUP_FOLDER) does not exist."
fi

if [ ! -e "$WL_BACKUP_FOLDER" ]; then
  echo "Error: WhiteLabel BACKUP directory ($WL_BACKUP_FOLDER) does not exist."
fi

#WL
RENDER_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php"
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
PG_STG1="${INC_SOURCE_DIR}pagination_all_stage_check.php"
#PG_CHECK_TAGS="${INC_SOURCE_DIR}pagination_check_tags.php"
PG_CHECK="${INC_SOURCE_DIR}pagination_check.php"
PG_LS="${INC_SOURCE_DIR}pagination_life_stage_check.php"
PG_NEW_CHECK="${INC_SOURCE_DIR}pagination_new_check.php"
PG_SORT_CHECK="${INC_SOURCE_DIR}pagination_sort_check.php"
# PG_TAGS_CHECK="${INC_SOURCE_DIR}pagination_tags_check.php"
PG_TAGS_NCHECK="${INC_SOURCE_DIR}pagination_tags_n_check.php"
PG_BTN_LS="${INC_SOURCE_DIR}pg_btn_lifestage.php"
# PG_BTN_NEW_LS="${INC_SOURCE_DIR}pg_btn_new_lifestage.php"
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

cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php" "$RENDER_DIR"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$CSS_DIR"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_all_stage_check.php" "$PG_STG1"
#cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_check_tags.php" "$PG_CHECK_TAGS"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_check.php" "$PG_CHECK"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_life_stage_check.php" "$PG_LS"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_new_check.php" "$PG_NEW_CHECK"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_sort_check.php" "$PG_SORT_CHECK"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pagination_tags_n_check.php" "$PG_TAGS_NCHECK"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pg_btn_lifestage.php" "$PG_BTN_LS"
#cp"$WL_BACKUP_FOLDER/includes/core/.php"  "$PG_BTN_NEW_LS" "$BACKUP_FOLDER"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pg_btn_tags.php" "$PG_BTN_TAGS"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/pgn_btn_resource.php" "$PGN_BTN_RS"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_all_page.php" "$RS_ALL"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_life_stages.php" "$RS_LS"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_pagination_all_new.php" "$RS_PG_ALL_NEW"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_pagination_back_content.php" "$RS_PG_BACK_CONTENT"
#cp"$WL_BACKUP_FOLDER/includes/core/.php"  "$RS_SEARCH_PG_CONNTENT"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_search_new.php" "$RS_SEARCH_NEW"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_sort_search.php" "$RS_SORT_SEARCH"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_tag_search.php" "$RS_TAG_SEARCH"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/search_query.php" "$SEARCH_QUERY"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/search_query_pagination.php" "$WL_SQP"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_pagination_content.php" "$RS_PG_CONTENT"
cp "$WL_BACKUP_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_search.php" "$RS_SEARCH"

echo "Done copying inc files"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Restore successful!"
else
  echo "Restore failed!"
fi


echo "NOW MAIN copying inc files"


cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css" "$MAIN_CSS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php" "$RENDER_DIR_MAIN"
# c"$MAIN_BACKUP_FOLDER/.php" p "$TEMP_HOME"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_all_stage_check.php" "$MAIN_PG_STG1"
#cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_check_tags.php" "$PG_CHECK_TAGS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_check.php" "$MAIN_PG_CHECK"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_life_stage_check.php" "$MAIN_PG_LS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_new_check.php" "$MAIN_PG_NEW_CHECK"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_sort_check.php" "$MAIN_PG_SORT"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_tags_check.php" "$MAIN_PG_TAGS_CHECK"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pagination_tags_n_check.php" "$MAIN_PG_TAGS_NCHECK"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pg_btn_lifestage.php" "$MAIN_PG_BTN_LS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pg_btn_new_lifestage.php" "$MAIN_PG_BTN_NEW_LS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pg_btn_tags.php" "$MAIN_PG_BTN_TAGS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/pgn_btn_resource.php" "$MAIN_PGN_BTN_RS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_all_page.php" "$MAIN_RS_ALL"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_life_stages.php" "$MAIN_RS_LS"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_pagination_all_new.php" "$MAIN_RS_PG_ALL_NEW"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_pagination_back_content.php" "$MAIN_RS_PG_BACK_CONTENT"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_search_pagination_content.php" "$MAIN_RS_SEARCH_PG_CONTENT"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_search_new.php" "$MAIN_RS_SEARCH_NEW"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_sort_search.php" "$MAIN_RS_SORT_SEARCH"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_tag_search.php" "$MAIN_RS_TAG_SEARCH"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_pagination_content.php" "$MAIN_RS_PG_CONTENT"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_search.php" "$MAIN_RS_SEARCH"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/search_query.php" "$MAIN_SEARCH_QUERY"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/search_query_pagination.php" "$MAIN_SQ_PG"
cp "$MAIN_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/includes/core/resource_tags.php" "$MAIN_RS_TAGS"


echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Restore successful!"
else
  echo "Restore failed!"
fi
