#!/bin/bash
# This bash script generates the JS build,
# by running `npm run build` on all scripts (blocks/editor-scripts)

# Silent
set +x

# Current directory
# @see: https://stackoverflow.com/questions/59895/how-to-get-the-source-directory-of-a-bash-script-from-within-the-script-itself#comment16925670_59895
# DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
PWD="$( pwd )"

# Must pass the path to the plugin root as first arg to the script
PLUGIN_DIR="$PWD/$1"
if [ -z "$PLUGIN_DIR" ]; then
    echo "The path to the plugin directory is missing; pass it as first argument to the script"
else
    echo "Building all JS packages, blocks and editor scripts in path '$PLUGIN_DIR'"
fi
# To install the dependencies, exec script with arg "true"
INSTALL_DEPS="$2"

# Function `buildScripts` will run `npm run build`
# on all folders in the current directory
buildScripts(){
    CURRENT_DIR=$( pwd )
    echo "In folder '$CURRENT_DIR'"
    for file in ./*
    do
        # Make sure it is a directory
        if [ -d "$file" ]; then
            echo "In subfolder '$file'"
            cd "$file"
            # Install node_modules/ dependencies
            if [ -n "$INSTALL_DEPS" ]; then
                echo "Installing dependencies"
                npm install --legacy-peer-deps
            fi
            npm run build
            cd ..
        fi
    done
}

# Function `maybeBuildScripts` will invoke `buildScripts`
# if the target folder exists
maybeBuildScripts(){
    if [[ -d "$TARGET_DIR" ]]
    then
        cd "$TARGET_DIR"
        buildScripts
    else
        echo "Directory '$TARGET_DIR' does not exist"
    fi
}

# # First create the symlinks to node_modules/ everywhere
# bash -x "$DIR/create-node-modules-symlinks.sh" >/dev/null 2>&1

# Packages: used by Blocks/Editor Scripts
# TARGET_DIR="$DIR/../../packages/"
TARGET_DIR="$PLUGIN_DIR/packages/"
maybeBuildScripts

# Blocks
# TARGET_DIR="$DIR/../../blocks/"
TARGET_DIR="$PLUGIN_DIR/blocks/"
maybeBuildScripts

# Editor Scripts
# TARGET_DIR="$DIR/../../editor-scripts/"
TARGET_DIR="$PLUGIN_DIR/editor-scripts/"
maybeBuildScripts

