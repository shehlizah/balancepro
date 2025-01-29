# Source Directories
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"

# Use the current directory as the source for custom files
CUSTOM_SOURCE_DIR="$PWD"

# Destination Directories
INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/core/"
TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"
LS_MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/includes/core/"
CSS_DIR_WL="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/"

# Backup Timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"

# WL Paths
IND_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/index.php"
CONF_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"
CSS_DIR="${CSS_DIR_WL}main.min.css"

# BalancePro Main Paths
LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"

echo "Copying MAIN updated files..."

cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/includes/core/search_query_pagination.php" "$LS_MAIN_SQ_PG"
cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-module-M14-15-resources-search.php" "$LS_MAIN_RMRS"
cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design-lifestage.php" "$LS_MAIN_RSMDL"

echo "Copying WL files..."

cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/index.php" "$IND_DIR"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$CSS_DIR"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/config.php" "$CONF_DIR"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/search_query_pagination.php" "$LS_SQP"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/lifestage.php" "$LIFESTAGE"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/ls.php" "$LS"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/resource_search_pagination_content.php" "$LS_RS_SEARCH_PG_CONTENT"
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/core/core.php" "$LS_CORE"

echo "Copied all files!"
