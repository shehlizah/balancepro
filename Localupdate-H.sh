#!/bin/bash

HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
TMP_SOURCE_DIR="$HOME_WL/templates/"
INC_SOURCE_DIR="$HOME_WL/includes/core/"
MAIN_RNDR_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/"
LS_MAIN_SRC_DIR="$HOME_MAIN/includes/core/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FOLDER="$PWD/backup_$TIMESTAMP"
LS_MAIN_BRANCH_NAME="lifestage-balancepro"
LS_BRANCH_NAME="lifestage-whitelabel"
LS_MAIN_CLONE_FOLDER="$PWD/main_repo_${LS_MAIN_BRANCH_NAME}"
LS_CLONE_FOLDER="$PWD/WL_repo_${LS_BRANCH_NAME}"

# Whitelabel Variables
IND_DIR="$HOME_WL/public_html/index.php"
CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
CONF_DIR="$HOME_WL/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"

# BalancePro Main Variables
LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"

echo "Copying MAIN updated files"

# Copy Main Website Files
cp "$LS_MAIN_CLONE_FOLDER/search_query_pagination.php" "$LS_MAIN_SQ_PG" || echo "Failed to copy search_query_pagination.php"
cp "$LS_MAIN_CLONE_FOLDER/render-module-M14-15-resources-search.php" "$LS_MAIN_RMRS" || echo "Failed to copy render-module-M14-15-resources-search.php"
cp "$LS_MAIN_CLONE_FOLDER/render-search-main-design-lifestage.php" "$LS_MAIN_RSMDL" || echo "Failed to copy render-search-main-design-lifestage.php"

echo "Copying WL files"

# Copy Whitelabel Files
cp "$LS_CLONE_FOLDER/config.php" "$CONF_DIR" || echo "Failed to copy config.php"
cp "$LS_CLONE_FOLDER/lifestage.php" "$LIFESTAGE" || echo "Failed to copy lifestage.php"
cp "$LS_CLONE_FOLDER/ls.php" "$LS" || echo "Failed to copy ls.php"
cp "$LS_CLONE_FOLDER/search_query_pagination.php" "$LS_SQP" || echo "Failed to copy search_query_pagination.php"
cp "$LS_CLONE_FOLDER/resource_search_pagination_content.php" "$LS_RS_SEARCH_PG_CONTENT" || echo "Failed to copy resource_search_pagination_content.php"
cp "$LS_CLONE_FOLDER/core.php" "$LS_CORE" || echo "Failed to copy core.php"

echo "Copied all files!"
