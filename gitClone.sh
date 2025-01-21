SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"

# Single branch name you want to work with
REPORTS_ADMIN_BRANCH_NAME="reports-admin-change"

# Directory for cloning the repository
REPORTS_ADMIN_CLONE_FOLDER="$PWD/reports_admin_repo_${REPORTS_ADMIN_BRANCH_NAME}"

# Create the necessary directory for cloning
mkdir -p "$REPORTS_ADMIN_CLONE_FOLDER"

# Debug output for path
echo "REPORTS_ADMIN_CLONE_FOLDER: $REPORTS_ADMIN_CLONE_FOLDER"

# Check if the path is valid (not empty)
if [ -z "$REPORTS_ADMIN_CLONE_FOLDER" ]; then
  echo "Error: Directory path is empty. Please check the variable."
  exit 1
fi

# Clone the main branch
echo "Cloning main branch '$REPORTS_ADMIN_BRANCH_NAME' from GitHub repository..."
git clone --branch "$REPORTS_ADMIN_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$REPORTS_ADMIN_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$REPORTS_ADMIN_BRANCH_NAME' successfully cloned to $REPORTS_ADMIN_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$REPORTS_ADMIN_BRANCH_NAME'."
  exit 1
fi
