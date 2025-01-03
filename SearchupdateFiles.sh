# Variables

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"


TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"


TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"
SEARCH_MAIN_BRANCH_NAME="search-fixes-balancepro"
SEARCH_WL_BRANCH_NAME="search-fixes-whitelabel"
SEARCH_MAIN_CLONE_FOLDER="$PWD/search_main_repo_${SEARCH_MAIN_BRANCH_NAME}"
SEARCH_WL_CLONE_FOLDER="$PWD/search_WL_repo_${SEARCH_WL_BRANCH_NAME}"
# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"



#WL

CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
LS_RNDR_SEARCH="${TMP_SOURCE_DIR}render-search-main-design.php"








#balanceproMain
MAIN_CSS_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
MAIN_RNDR_SEARCH="${MAIN_RNDR_SOURCE_DIR}render-search-main-design.php"
MAIN_MDL_ADMIN="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php"

echo "Copying MAIN updated files"

cp "$SEARCH_MAIN_CLONE_FOLDER/main.min_new.css" "$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
cp "$SEARCH_MAIN_CLONE_FOLDER/render-search-main-design.php" "$MAIN_RNDR_SEARCH"
cp "$SEARCH_MAIN_CLONE_FOLDER/edit-module-white-label-website-admins.php" $HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" 
 



echo "Copying WL files"

cp "$SEARCH_WL_CLONE_FOLDER/main.min.css" "$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" 
cp "$SEARCH_WL_CLONE_FOLDER/render-search-main-design.php" "$LS_RNDR_SEARCH"





echo "Copied all files!"
