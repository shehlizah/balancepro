#!/bin/bash

HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
TMP_SOURCE_DIR="$HOME_WL/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FOLDER="$PWD/backup_$TIMESTAMP"
SEARCH_MAIN_BRANCH_NAME="search-fixes-balancepro"
SEARCH_WL_NAME="search-fixes-whitelabel"
SEARCH_MAIN_CLONE_FOLDER="$PWD/search_main_repo_${SEARCH_MAIN_BRANCH_NAME}"
SEARCH_WL_CLONE_FOLDER="$PWD/search_WL_repo_${SEARCH_WL_NAME}"

# Whitelabel Variables

CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"


# BalancePro Main Variables
MAIN_CSS_DIR="$HOME_MAIN/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME_MAIN/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"

echo "Copying MAIN updated files"

# Copy Main Website Files
cp "$SEARCH_MAIN_CLONE_FOLDER/main.min_new.css" "$HOME_MAIN/wp-content/themes/balance-theme/css/main.min_new.css"
cp "$SEARCH_MAIN_CLONE_FOLDER/render-search-main-design.php" "$MAIN_RNDR_SEARCH"
cp "$SEARCH_MAIN_CLONE_FOLDER/edit-module-white-label-website-admins.php" "$HOME_MAIN/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" 


echo "Copying WL files"

# Copy Whitelabel Files

cp "$SEARCH_WL_CLONE_FOLDER/main.min.css" "$HOME_WL/public_html/assets/css/main.min.css" 
cp "$SEARCH_WL_CLONE_FOLDER/render-search-main-design.php" "$LS_RNDR_SEARCH"


echo "Copied all files!"
