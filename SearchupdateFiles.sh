# Variables

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"

# Use the current directory as the source for custom files
CUSTOM_SOURCE_DIR="$PWD"

TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"


TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
BACKUP_FOLDER="$PWD/backup_$TIMESTAMP"

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

echo "Using CUSTOM_SOURCE_DIR: $CUSTOM_SOURCE_DIR"
echo "Copying MAIN updated files"

cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css" "$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/main.min_new.css"
cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/render-search-main-design.php" "$MAIN_RNDR_SEARCH"
cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" "$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-admins.php" 
 



echo "Copying WL files"

cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" "$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" 
cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/templates/render-search-main-design.php" "$LS_RNDR_SEARCH"





echo "Copied all files!"
