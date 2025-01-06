#!/bin/bash

HOME_WL="C:/xampp/htdocs/whitelabel"
HOME_MAIN="C:/xampp/htdocs/balancetest"

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
TMP_SOURCE_DIR="$HOME_WL/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/renderers/modules/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FOLDER="$PWD/backup_$TIMESTAMP"
SEARCH_FINAL_REPO="Search-deployment-final"  # The final branch you want to work with
SEARCH_FINAL_CLONE_FOLDER="$PWD/search_final_repo_${SEARCH_FINAL_REPO}"

# Whitelabel Variables
CSS_DIR="$HOME_WL/public_html/assets/css/main.min.css"
LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"

# BalancePro Main Variables
MAIN_CSS_DIR="$HOME_MAIN/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME_MAIN/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"

# Check if the folder already exists, and rename it if so
if [ -d "$SEARCH_FINAL_CLONE_FOLDER" ]; then
  echo "Directory $SEARCH_FINAL_CLONE_FOLDER already exists. Renaming it..."
  mv "$SEARCH_FINAL_CLONE_FOLDER" "${SEARCH_FINAL_CLONE_FOLDER}_backup_$TIMESTAMP"
  echo "Directory renamed to ${SEARCH_FINAL_CLONE_FOLDER}_backup_$TIMESTAMP"
fi

echo "Cloning the final deployment branch '$SEARCH_FINAL_REPO' from GitHub repository..."

# Clone the final branch
git clone --branch "$SEARCH_FINAL_REPO" "$SOURCE_DIR_MAIN" "$SEARCH_FINAL_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$SEARCH_FINAL_REPO' successfully cloned to $SEARCH_FINAL_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$SEARCH_FINAL_REPO'."
  exit 1
fi

# Exclude 'search-scripts' directory after cloning
if [ -d "$SEARCH_FINAL_CLONE_FOLDER/search-scripts" ]; then
  echo "'search-scripts' directory found, removing it from the cloned repository..."
  rm -rf "$SEARCH_FINAL_CLONE_FOLDER/search-scripts"
  echo "'search-scripts' directory removed."
fi

echo "Copying MAIN updated files"

# Copy Main Website Files from the cloned final branch
cp "$SEARCH_FINAL_CLONE_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css" "$HOME_MAIN/wp-content/themes/balance-theme/css/main.min_new.css"
cp "$SEARCH_FINAL_CLONE_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php" "$MAIN_RNDR_SEARCH"
cp "$SEARCH_FINAL_CLONE_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" "$MAIN_MDL_ADMIN"

echo "Copying WL files"

# Copy Whitelabel Files from the cloned final branch
cp "$SEARCH_FINAL_CLONE_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$HOME_WL/public_html/assets/css/main.min.css"
cp "$SEARCH_FINAL_CLONE_FOLDER/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php" "$LS_RNDR_SEARCH"

echo "Copied all files, excluding search-scripts!"
