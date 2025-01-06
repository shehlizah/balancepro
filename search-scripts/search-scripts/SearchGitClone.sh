
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"


SEARCH_MAIN_BRANCH_NAME="search-fixes-balancepro"
SEARCH_WL_BRANCH_NAME="search-fixes-whitelabel"
search_deployment="search-fixes-deployment"

SEARCH_MAIN_CLONE_FOLDER="$PWD/search_main_repo_${SEARCH_MAIN_BRANCH_NAME}"
SEARCH_WL_CLONE_FOLDER="$PWD/search_WL_repo_${SEARCH_WL_BRANCH_NAME}"
deploymentScripts_search="$PWD/gitScripts_${SEARCH_WL_BRANCH_NAME}"

mkdir -p "$SEARCH_MAIN_CLONE_FOLDER"
mkdir -p "$SEARCH_WL_CLONE_FOLDER"
mkdir -p "$deploymentScripts_search"

echo "Cloning deployment scripts '$search_deployment' from GitHub repository..."

git clone --branch "$search_deployment" "$SOURCE_DIR_MAIN" "$deploymentScripts_search"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$search_deployment' successfully cloned to $deploymentScripts_search"
else
  echo "Error: Failed to clone branch '$search_deployment'."
  exit 1
fi

echo "Cloning MAIN branch '$SEARCH_MAIN_BRANCH_NAME' from GitHub repository..."

git clone --branch "$SEARCH_MAIN_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$SEARCH_MAIN_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$SEARCH_MAIN_BRANCH_NAME' successfully cloned to $SEARCH_MAIN_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$SEARCH_MAIN_BRANCH_NAME'."
  exit 1
fi


echo "Cloning WL branch '$SEARCH_WL_BRANCH_NAME' from GitHub repository..."
git clone --branch "$SEARCH_WL_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$SEARCH_WL_CLONE_FOLDER"


# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$SEARCH_WL_BRANCH_NAME' successfully cloned to $SEARCH_WL_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$SEARCH_WL_BRANCH_NAME'."
  exit 1
fi
