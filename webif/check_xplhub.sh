#!/bin/bash

# Pfad zum xplhub-Dienst
XPLHUB_SERVICE="/etc/init.d/xplhub start"

# Prüfen, ob LMS läuft
if systemctl is-active --quiet logitechmediaserver.service; then
    echo "Logitech Media Server läuft."

    # Prüfen, ob xplhub bereits läuft
    if ! pgrep -x "xplhub" > /dev/null; then
        echo "xplhub läuft nicht. Versuche zu starten..."
        sudo $XPLHUB_SERVICE
    else
        echo "xplhub läuft bereits."
    fi
else
    echo "Logitech Media Server läuft nicht. xplhub wird nicht gestartet."
fi
