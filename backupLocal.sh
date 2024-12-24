#!/bin/bash
set -x

HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
SOURCE_DIR="$HOME_WL/public_html/templates/main.php"    # Slider
INC_SOURCE_DIR="$HOME_WL/includes/core/"    # other all 
MAIN_SRC_DIR="$HOME_MAIN/includes/core/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
MAIN_BACKUP_FOLDER="$HOME/main_backup_$TIMESTAMP"
WL_BACKUP_FOLDER="$HOME/wl_backup_$TIMESTAMP"

# WL Variables
RENDER_DIR="$HOME_WL/public_html/templates/render-search-main-design.php"
CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
PG_STG1="${INC_SOURCE_DIR}pagination_all_stage_check.php"
PG_CHECK_TAGS="${INC_SOURCE_DIR}pagination_check_tags.php"
PG_CHECK="${INC_SOURCE_DIR}pagination_check.php"
PG_LS="${INC_SOURCE_DIR}pagination_life_stage_check.php"
PG_NEW_CHECK="${INC_SOURCE_DIR}pagination_new_check.php"
PG_SORT_CHECK="${INC_SOURCE_DIR}pagination_sort_check.php"
PG_TAGS_NCHECK="${INC_SOURCE_DIR}pagination_tags_n_check.php"
PG_BTN_LS="${INC_SOURCE_DIR}pg_btn_lifestage.php"
PG_BTN_TAGS="${INC_SOURCE_DIR}pg_btn_tags.php"
PGN_BTN_RS="${INC_SOURCE_DIR}pgn_btn_resource.php"
RS_ALL="${INC_SOURCE_DIR}resource_all_page.php"
RS_LS="${INC_SOURCE_DIR}resource_life_stages.php"
RS_PG_ALL_NEW="${INC_SOURCE_DIR}resource_pagination_all_new.php"
RS_PG_BACK_CONTENT="${INC_SOURCE_DIR}resource_pagination_back_content.php"
RS_PG_CONTENT="${INC_SOURCE_DIR}resource_pagination_content.php"
RS_SEARCH="${INC_SOURCE_DIR}resource_search.php"
RS_SEARCH_NEW="${INC_SOURCE_DIR}resource_search_new.php"
RS_SORT_SEARCH="${INC_SOURCE_DIR}resource_sort_search.php"
RS_TAG_SEARCH="${INC_SOURCE_DIR}resource_tag_search.php"
SEARCH_QUERY="${INC_SOURCE_DIR}search_query.php"
WL_SQP="${INC_SOURCE_DIR}search_query_pagination.php"

# BalancePro Main Variables
MAIN_CSS="$HOME_MAIN/wp-content/themes/balance-theme/css/main.min_new.css"
RENDER_DIR_MAIN="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php"
MAIN_PG_SORT="${MAIN_SRC_DIR}pagination_sort_check.php"
MAIN_PG_STG1="${MAIN_SRC_DIR}pagination_all_stage_check.php"
MAIN_PG_CHECK="${MAIN_SRC_DIR}pagination_check.php"
#MAIN_PG_CHECK_TAGS="${MAIN_SRC_DIR}pagination_check_tags.php"
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

# Create the backup folder if it doesn't exist
mkdir -p "$MAIN_BACKUP_FOLDER"
echo "MAIN Backup folder created at: $MAIN_BACKUP_FOLDER"

mkdir -p "$WL_BACKUP_FOLDER"
echo "WL Backup folder created at: $WL_BACKUP_FOLDER"

# Ensure all necessary directories exist in the backup paths
mkdir -p "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core"
mkdir -p "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core"
mkdir -p "$WL_BACKUP_FOLDER/whitelabel/public_html/templates"
mkdir -p "$WL_BACKUP_FOLDER/whitelabel/public_html/assets/css"
echo "Created necessary directories under backup folders."
# Ensure necessary directories exist for the main backup
mkdir -p "$MAIN_BACKUP_FOLDER/balancepro/public_html/wp-content/themes/balance-theme/css"
mkdir -p "$MAIN_BACKUP_FOLDER/balancepro/public_html/wp-content/themes/balance-theme/inc/renderers/modules"



# Copy files to the correct destination directories
cp "$RENDER_DIR" "$WL_BACKUP_FOLDER/whitelabel/public_html/templates/"
cp "$CSS_DIR" "$WL_BACKUP_FOLDER/whitelabel/public_html/assets/css/"
cp "$PG_STG1" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
#cp "$PG_CHECK_TAGS" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_CHECK" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_LS" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_NEW_CHECK" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_SORT_CHECK" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_TAGS_NCHECK" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_BTN_LS" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PG_BTN_TAGS" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$PGN_BTN_RS" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_ALL" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_LS" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_PG_ALL_NEW" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_PG_BACK_CONTENT" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_SEARCH" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_SEARCH_NEW" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_SORT_SEARCH" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_TAG_SEARCH" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$SEARCH_QUERY" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$WL_SQP" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"
cp "$RS_PG_CONTENT" "$WL_BACKUP_FOLDER/whitelabel/public_html/includes/core/"

# Main website files copying
cp "$MAIN_CSS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/wp-content/themes/balance-theme/css/"
cp "$RENDER_DIR_MAIN" "$MAIN_BACKUP_FOLDER/balancepro/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
cp "$MAIN_PG_STG1" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_CHECK" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
#cp "$MAIN_PG_CHECK_TAGS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_LS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_NEW_CHECK" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_TAGS_CHECK" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_TAGS_NCHECK" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_BTN_LS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_BTN_NEW_LS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_BTN_TAGS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PGN_BTN_RS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_ALL" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_LS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_PG_ALL_NEW" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_PG_BACK_CONTENT" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_SEARCH_PG_CONTENT" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_SEARCH_NEW" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_SORT_SEARCH" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_TAG_SEARCH" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_TAGS" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_PG_CONTENT" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_RS_SEARCH" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_SEARCH_QUERY" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_SQ_PG" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"
cp "$MAIN_PG_SORT" "$MAIN_BACKUP_FOLDER/balancepro/public_html/includes/core/"

echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
