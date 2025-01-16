#!/bin/bash


HOME_MAIN="C:/xampp/htdocs/balancetest"

SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
MAIN_EDIT_SOURCE_DIR="$HOME_MAIN//wp-content/themes/balance-theme/inc/edit/modules/"
REPORTS_MAIN_SRC_DIR="$HOME_MAIN//wp-content/themes/balance-theme/"  
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
BACKUP_FOLDER="$HOME_MAIN/backup_$TIMESTAMP"
REPORTS_ADMIN_BRANCH_NAME="reports-admin"
REPORTS_ADMIN_CLONE_FOLDER="$PWD/reports_admin_repo_${REPORTS_ADMIN_BRANCH_NAME}"

#balanceproMain
REPORTS_ADMIN_FUNC="${REPORTS_MAIN_SRC_DIR}functions.php"
REPORTS_ADMIN_PART="${REPORTS_MAIN_SRC_DIR}partner-reports.php"
REPORTS_ADMIN_EXP="${MAIN_EDIT_SOURCE_DIR}export.php"

echo "Copying MAIN updated files"

cp "$REPORTS_ADMIN_CLONE_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/functions.php" "$REPORTS_ADMIN_FUNC" 
cp "$REPORTS_ADMIN_CLONE_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/partner-reports.php" "$REPORTS_ADMIN_PART" 
cp "$REPORTS_ADMIN_CLONE_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/export.php" "$REPORTS_ADMIN_EXP" 




echo "Copied all files!"
