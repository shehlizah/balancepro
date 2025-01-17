SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"

# Single branch name you want to work with
LS_FINAL_BRANCH_NAME="ls-final-deployment"

# Directory for cloning the repository
LS_FINAL_CLONE_FOLDER="$PWD/ls_final_repo_${LS_FINAL_BRANCH_NAME}"

# Create the necessary directory for cloning
mkdir -p "$LS_FINAL_CLONE_FOLDER"

# Debug output for path
echo "LS_FINAL_CLONE_FOLDER: $LS_FINAL_CLONE_FOLDER"

# Check if the path is valid (not empty)
if [ -z "$LS_FINAL_CLONE_FOLDER" ]; then
  echo "Error: Directory path is empty. Please check the variable."
  exit 1
fi

# Clone the main branch
echo "Cloning main branch '$LS_FINAL_BRANCH_NAME' from GitHub repository..."
git clone --branch "$LS_FINAL_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$LS_FINAL_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$LS_FINAL_BRANCH_NAME' successfully cloned to $LS_FINAL_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$LS_FINAL_BRANCH_NAME'."
  exit 1
fi
