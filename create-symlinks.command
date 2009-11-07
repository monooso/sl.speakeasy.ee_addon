#!/bin/bash

# This script assumes that it lives here:
# /addons/speakeasy/
#
# If this is not the case, you'll need to alter
# the script accordingly. Obviously.

addon_dir_path=`pwd`

echo "Enter your 'system' folder name, and press ENTER:"
read ee_system_folder

# Delete any existing symlinks.
rm ../../"$ee_system_folder"/extensions/ext.speakeasy.php
rm ../../"$ee_system_folder"/extensions/speakeasy
rm ../../"$ee_system_folder"/plugins/pi.speakeasy_pl.php
rm ../../"$ee_system_folder"/language/english/lang.speakeasy.php
rm ../../themes/cp_themes/default/speakeasy

# Create the symlinks.
ln -s ../../addons/speakeasy/system/extensions/ext.speakeasy.php ../../"$ee_system_folder"/extensions/
ln -s ../../addons/speakeasy/system/extensions/speakeasy ../../"$ee_system_folder"/extensions/
ln -s ../../addons/speakeasy/system/plugins/pi.speakeasy_pl.php ../../"$ee_system_folder"/plugins/
ln -s ../../../addons/speakeasy/system/language/english/lang.speakeasy.php ../../"$ee_system_folder"/language/english/
ln -s ../../../addons/speakeasy/themes/cp_themes/default/speakeasy ../../themes/cp_themes/default/
