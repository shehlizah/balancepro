SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"

# Single branch name you want to work with
SEARCH_FINAL_BRANCH_NAME="Search-deployment-final"

# Directory for cloning the repository
SEARCH_FINAL_CLONE_FOLDER="$PWD/SEARCH_FINAL_repo_${SEARCH_FINAL_BRANCH_NAME}"

# Create the necessary directory for cloning
mkdir -p "$SEARCH_FINAL_CLONE_FOLDER"

# Debug output for path
echo "SEARCH_FINAL_CLONE_FOLDER: $SEARCH_FINAL_CLONE_FOLDER"

# Check if the path is valid (not empty)
if [ -z "$SEARCH_FINAL_CLONE_FOLDER" ]; then
  echo "Error: Directory path is empty. Please check the variable."
  exit 1
fi

# Clone the main branch
echo "Cloning main branch '$SEARCH_FINAL_BRANCH_NAME' from GitHub repository..."
git clone --branch "$SEARCH_FINAL_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$SEARCH_FINAL_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$SEARCH_FINAL_BRANCH_NAME' successfully cloned to $SEARCH_FINAL_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$SEARCH_FINAL_BRANCH_NAME'."
  exit 1
fi
