#!/bin/sh

# $1 = player nummer
# $2 = ein/aus
# $3 = li/re

echo $2 > /opt/innotune/settings/status_shairplay/status_shairplay$3$1.txt
