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

# Create the backup folder if it doesn't exist
mkdir -p "$MAIN_BACKUP_FOLDER"
echo "MAIN Backup folder created at: $MAIN_BACKUP_FOLDER"

mkdir -p "$WL_BACKUP_FOLDER"
echo "WL Backup folder created at: $WL_BACKUP_FOLDER"

# Create directory structure for public_html and other source directories in the backup folders
mkdir -p "$WL_BACKUP_FOLDER/public_html/templates"
mkdir -p "$WL_BACKUP_FOLDER/public_html/assets/css"
mkdir -p "$WL_BACKUP_FOLDER/includes/core"

mkdir -p "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/css"
mkdir -p "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/renderers/modules"
mkdir -p "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core"

# Backup whitelabel files
echo "Copying Whitelabel files..."
cp "$HOME_WL/public_html/templates/render-search-main-design.php" "$WL_BACKUP_FOLDER/public_html/templates/"
cp "$HOME_WL/public_html/assets/css/main.min.css" "$WL_BACKUP_FOLDER/public_html/assets/css/"
cp "$INC_SOURCE_DIR/pagination_all_stage_check.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pagination_check.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pagination_life_stage_check.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pagination_new_check.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pagination_sort_check.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pagination_tags_n_check.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pg_btn_lifestage.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pg_btn_tags.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/pgn_btn_resource.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_all_page.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_life_stages.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_pagination_all_new.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_pagination_back_content.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_pagination_content.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_search.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_search_new.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_sort_search.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/resource_tag_search.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/search_query.php" "$WL_BACKUP_FOLDER/includes/core/"
cp "$INC_SOURCE_DIR/search_query_pagination.php" "$WL_BACKUP_FOLDER/includes/core/"

echo "Whitelabel files copied"

# Backup main website files
echo "Copying Main website files..."
cp "$HOME_MAIN/wp-content/themes/balance-theme/css/main.min_new.css" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/css/"
cp "$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/renderers/modules/"
cp "$MAIN_SRC_DIR/pagination_all_stage_check.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/pagination_check.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/pagination_life_stage_check.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/pagination_new_check.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/pagination_sort_check.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/pagination_tags_check.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/pgn_btn_resource.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_all_page.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_life_stages.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_pagination_all_new.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_pagination_back_content.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_pagination_content.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_search.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_search_new.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_sort_search.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/resource_tag_search.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/search_query.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"
cp "$MAIN_SRC_DIR/search_query_pagination.php" "$MAIN_BACKUP_FOLDER/wp-content/themes/balance-theme/inc/includes/core/"

echo "Main website files copied"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
