#!/bin/bash
ping -q -c4 -w30 8.8.8.8 &>/dev/null ; echo $?
