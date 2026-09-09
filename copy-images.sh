#!/bin/bash
# Run this once from the UCI Project folder:
#   bash "uci-carbons-theme-2/copy-images.sh"
# It copies all Photos into the theme's assets/images/ folder.

SRC="$(dirname "$0")/../rajindra-carbons-website-2026/Photos"
DEST="$(dirname "$0")/assets/images"

mkdir -p "$DEST"
cp -n "$SRC"/*.jpeg "$DEST"/ 2>/dev/null
cp -n "$SRC"/*.jpg  "$DEST"/ 2>/dev/null

echo "Done. Images copied to $DEST"
