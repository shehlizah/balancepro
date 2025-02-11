# Source Directories
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"
SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/"

# Use the current directory as the source for custom files
CUSTOM_SOURCE_DIR="$PWD"

# Destination Directories
INC_SOURCE_DIR="$HOME/domains/whitelabel.balancepro.org/public_html/includes/"


# Backup Timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FOLDER="$HOME/backup_$TIMESTAMP"

# WL Paths

FN_RES="${INC_SOURCE_DIR}func_resource.php"


echo "Copying WL files..."


cp "$CUSTOM_SOURCE_DIR/whitelabel/domains/whitelabel.balancepro.org/public_html/includes/func_resource.php" "$FN_RES"

echo "Copied all files!"
