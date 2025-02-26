# Source Directories
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"

# SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"

# Use the current directory as the source for custom files
CUSTOM_SOURCE_DIR="$PWD"

# Destination Directories
BALANCEPRO_CSS_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin/"
BALANCEPRO_FILE_SOURCE_DIR="$HOME/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/"


# Backup Timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"

#  Paths

CHK_BX="${BALANCEPRO_CSS_SOURCE_DIR}admin.css"
CHK_FL="${BALANCEPRO_FILE_SOURCE_DIR}edit-module-white-label-website-programs.php"


echo "Copying WL files..."


cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/css/admin/admin.css" "$CHK_BX"
cp "$CUSTOM_SOURCE_DIR/balancepro/domains/balancepro.org/public_html/wp-content/themes/balance-theme/inc/edit/modules/edit-module-white-label-website-programs.php" "$CHK_FL"
echo "Copied all files!"
