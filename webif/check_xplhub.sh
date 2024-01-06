#!/bin/bash

# Pfad zum xplhub-Dienst
XPLHUB_SERVICE="/etc/init.d/xplhub start"

# Pr�fen, ob LMS l�uft
if systemctl is-active --quiet logitechmediaserver.service; then
    echo "Logitech Media Server l�uft."

    # Pr�fen, ob xplhub bereits l�uft
    if ! pgrep -x "xplhub" > /dev/null; then
        echo "xplhub l�uft nicht. Versuche zu starten..."
        sudo $XPLHUB_SERVICE
    else
        echo "xplhub l�uft bereits."
    fi
else
    echo "Logitech Media Server l�uft nicht. xplhub wird nicht gestartet."
fi
