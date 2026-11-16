#!/bin/sh
# Copie les feuilles de style d'Elan dans le thème enfant (à relancer après chaque mise à jour d'Elan).
# Usage : bin/sync_elan_sheets.sh <dossier public/theme de Moodle>
set -e
THEMES="${1:?dossier public/theme requis}"
mkdir -p "$THEMES/datocracy/style"
for f in owl-carousel owl-theme-default magnific-popup animate boxicons remixicon odometer meanmenu style envytheme-mdl-core responsive dashboard compatibility; do
  cp "$THEMES/elan/style/$f.css" "$THEMES/datocracy/style/$f.css"
done
echo "Feuilles d'Elan copiées dans $THEMES/datocracy/style"
