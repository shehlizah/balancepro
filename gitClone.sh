
SOURCE_DIR_MAIN="https://github.com/shehlizah/balancepro.git"


LS_MAIN_BRANCH_NAME="lifestage-balancepro"
LS_BRANCH_NAME="lifestage-whitelabel"
deployment="lifestage-deployment"

LS_MAIN_CLONE_FOLDER="$PWD/main_repo_${LS_MAIN_BRANCH_NAME}"
LS_CLONE_FOLDER="$PWD/WL_repo_${LS_BRANCH_NAME}"
deploymentScripts="$PWD/gitScripts_${LS_BRANCH_NAME}"

mkdir -p "$LS_MAIN_CLONE_FOLDER"
mkdir -p "$LS_CLONE_FOLDER"
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

echo "Cloning MAIN branch '$LS_MAIN_BRANCH_NAME' from GitHub repository..."

git clone --branch "$LS_MAIN_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$LS_MAIN_CLONE_FOLDER"

# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$LS_MAIN_BRANCH_NAME' successfully cloned to $LS_MAIN_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$LS_MAIN_BRANCH_NAME'."
  exit 1
fi


echo "Cloning WL branch '$LS_BRANCH_NAME' from GitHub repository..."
git clone --branch "$LS_BRANCH_NAME" "$SOURCE_DIR_MAIN" "$LS_CLONE_FOLDER"


# Verify if the clone operation was successful
if [ $? -eq 0 ]; then
  echo "Branch '$LS_BRANCH_NAME' successfully cloned to $LS_CLONE_FOLDER"
else
  echo "Error: Failed to clone branch '$LS_BRANCH_NAME'."
  exit 1
fi
