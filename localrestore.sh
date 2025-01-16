#!/bin/bash

# Ask the user if they want to restore from the current working directory
read -p "Do you want to restore from the current working directory? (yes/no): " USER_RESPONSE

if [[ "$USER_RESPONSE" != "yes" ]]; then
  echo "Exiting restore process."
  exit 0
fi

# Automatically detect the latest timestamped backup folders in the current working directory
REPORTS_ADMIN_FINAL_BACKUP_FOLDER=$(ls -d $PWD/reports_admin_final_backup_* 2>/dev/null | sort | tail -n 1)


# Check if the directories exist
if [ -z "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER" ]; then
  echo "Error: No MAIN BACKUP directory found in the current working directory."
  exit 1
fi


echo "Detected LS FINAL BACKUP folder: $REPORTS_ADMIN_FINAL_BACKUP_FOLDER"


# Define restoration paths

HOME_MAIN="C:/xampp/htdocs/balancetest"

# File paths for restoration (Main website)
REPORTS_ADMIN_FUNC="${REPORTS_MAIN_SRC_DIR}functions.php"
REPORTS_ADMIN_PART="${REPORTS_MAIN_SRC_DIR}partner-reports.php"
REPORTS_ADMIN_EXP="${MAIN_EDIT_SOURCE_DIR}export.php"

# Function to copy files and check if it succeeded
cp() {
  src=$1
  dest=$2
  if cp "$src" "$dest"; then
    echo "Successfully copied $src to $dest"
  else
    echo "Failed to copy $src to $dest"
    exit 1
  fi
}

# Restore Main website files
echo "Restoring Main website files..."
cp "$LS_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/functions.php" "$REPORTS_ADMIN_FUNC"
cp "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/partner-reports.php" "$REPORTS_ADMIN_PART"
cp "$REPORTS_ADMIN_FINAL_BACKUP_FOLDER/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/export.php" "$REPORTS_ADMIN_EXP"

echo "Done restoring Main website files."

# Final confirmation
echo "Restore process completed successfully!"
