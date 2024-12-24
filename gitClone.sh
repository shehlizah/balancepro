
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"


MAIN_BRANCH_NAME="Balancepro-latest"
WL_BRANCH_NAME="Whitelabel-latest"
deployment="deployment"

MAIN_CLONE_FOLDER="$HOME/main_repo_${MAIN_BRANCH_NAME}"
WL_CLONE_FOLDER="$HOME/WL_repo_${WL_BRANCH_NAME}"
deploymentScripts="$HOME/gitScripts_${WL_BRANCH_NAME}"

mkdir -p "$MAIN_CLONE_FOLDER"
mkdir -p "$WL_CLONE_FOLDER"
mkdir -p "$deploymentScripts"

echo "Cloning deployment scripts '$deployment' from GitHub repository..."

git clone --branch "$deployment" "$SOURCE_DIR_MAIN" "$deploymentScripts"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$deployment' successfully cloned to $deploymentScripts"
else
  echo "Error: Failed to clone branch '$deployment'."
  exit 1
fi

echo "Cloning MAIN branch '$MAIN_BRANCH_NAME' from GitHub repository..."

git clone --branch "$MAIN_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$MAIN_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$MAIN_BRANCH_NAME' successfully cloned to $MAIN_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$MAIN_BRANCH_NAME'."
  exit 1
fi


echo "Cloning WL branch '$WL_BRANCH_NAME' from GitHub repository..."
git clone --branch "$WL_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$WL_CLONE_FOLDER"


# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$WL_BRANCH_NAME' successfully cloned to $WL_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$WL_BRANCH_NAME'."
  exit 1
fi