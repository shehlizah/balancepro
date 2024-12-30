# Variables

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"

INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/core/"    #other all 
TMP_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/templates/"
MAIN_RNDR_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/renderers/modules/"

LS_MAIN_SRC_DIR="$HOME/domains/balancepro.org/public_html/includes/core/"
#BACKUP_DIR="/home/shahlizeh/finalChanges3Dec"   
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"
LS_MAIN_BRANCH_NAME="lifestage-balancepro"
LS_BRANCH_NAME="lifestage-whitelabel"
LS_MAIN_CLONE_FOLDER="$PWD/main_repo_${LS_MAIN_BRANCH_NAME}"
LS_CLONE_FOLDER="$PWD/WL_repo_${LS_BRANCH_NAME}"
# #echo "Source Directory: $SOURCE_DIR"
# # echo "Backup Directory: $BACKUP_DIR"
# #echo "Backup Folder: $BACKUP_FOLDER"



#WL
IND_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/index.php"
CSS_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css"
CONF_DIR="$PWD/domains/whitelabel.balancepro.org/public_html/includes/config.php"
LIFESTAGE="${TMP_SOURCE_DIR}lifestage.php"
LS="${TMP_SOURCE_DIR}ls.php"
LS_SQP="${INC_SOURCE_DIR}search_query_pagination.php"
LS_RS_SEARCH_PG_CONTENT="${INC_SOURCE_DIR}resource_search_pagination_content.php"
LS_CORE="${INC_SOURCE_DIR}core.php"






#balanceproMain
LS_MAIN_SQ_PG="${LS_MAIN_SRC_DIR}search_query_pagination.php"
LS_MAIN_RMRS="${MAIN_RNDR_SOURCE_DIR}render-module-M14-15-resources-search.php"
LS_MAIN_RSMDL="${MAIN_RNDR_SOURCE_DIR}render-search-main-design-lifestage.php"
echo "Copying MAIN updated files"

cp "$LS_MAIN_CLONE_FOLDER/search_query_pagination.php" "$LS_MAIN_SQ_PG" 
cp "$LS_MAIN_CLONE_FOLDER/render-module-M14-15-resources-search.php" "$LS_MAIN_SQ_PG" 
cp "$LS_MAIN_CLONE_FOLDER/render-search-main-design-lifestage.php" "$LS_MAIN_SQ_PG" 



echo "Copying WL files"

cp "$LS_CLONE_FOLDER/index.php" "$HOME/domains/whitelabel.balancepro.org/public_html/

cp "$LS_CLONE_FOLDER/main.min.css" "$HOME/domains/whitelabel.balancepro.org/public_html/assets/css/main.min.css" 

cp "$LS_CLONE_FOLDER/config.php" "$HOME/domains/whitelabel.balancepro.org/public_html/includes/config.php"

cp "$LS_CLONE_FOLDER/search_query_pagination.php" "$LIFESTAGE" 

cp "$LS_CLONE_FOLDER/lifestage.php" "$WL_SQP"

cp "$LS_CLONE_FOLDER/ls.php" "$LS"

cp "$LS_CLONE_FOLDER/resource_search_pagination_content.php" "$LS_RS_SEARCH_PG_CONTENT"

cp "$LS_CLONE_FOLDER/core.php" "$LS_CORE"




echo "Copied all files!"
