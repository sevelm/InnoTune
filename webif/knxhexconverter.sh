#!/bin/bash
exp=$((0x$1 >> 11))
base=$((0x$1 & 0x07FF))
var=$(echo "(($base*0.01)*2^$exp)" | bc)
echo $var | awk '{print int($1+0.5)}'
