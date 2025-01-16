 #!/bin/bash
#set -x


HOME_MAIN="C:/xampp/htdocs/balancetest"

# Variables
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
MAIN_EDIT_SOURCE_DIR="$HOME_MAIN/wp-content/themes/balance-theme/inc/edit/modules/"
REPORTS_MAIN_SRC_DIR="$HOME_MAIN/wp-content/themes/balance-theme/"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")  # Current timestamp for unique backups
REPORTS_ADMIN_FINAL_BACKUP_FOLDER="$PWD/reports_admin_final_backup_$TIMESTAMP"

# BalancePro Main Variables
REPORTS_ADMIN_FUNC="${REPORTS_MAIN_SRC_DIR}functions.php"
REPORTS_ADMIN_PART="${REPORTS_MAIN_SRC_DIR}partner-reports.php"
REPORTS_ADMIN_EXP="${MAIN_EDIT_SOURCE_DIR}export.php"

# Create the backup folder if it doesn't exist
mkdir -p "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER"
echo "REPORTS_ADMIN_FINAL_BACKUP_FOLDER created at: $REPORTS_ADMIN_FINAL_BACKUP_FOLDER"



# Ensure all necessary directories exist in the backup paths
mkdir -p "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme"
mkdir -p "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules"
echo "Created necessary directories under backup folders."


# Main website files copying

cp "$REPORTS_ADMIN_FUNC" "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/"
cp "$REPORTS_ADMIN_PART" "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/" 
cp "$REPORTS_ADMIN_EXP" "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/" 


echo "Files copied successfully!"

# Confirm the backup
if [ $? -eq 0 ]; then
  echo "Backup successful!"
else
  echo "Backup failed!"
fi
