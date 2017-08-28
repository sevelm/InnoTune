##########################################################################
##                                                                       ##
##              Sending E-Mail notification with sendEmail               ##
##                                                                       ##
## Creation:    15.08.2013                                               ##
## Last Update: 20.10.2013                                               ##
##                                                                       ##
## Copyright (c) 2013 by Georg Kainzbauer <georgkainzbauer@gmx.net>      ##
##                                                                       ##
## This program is free software; you can redistribute it and/or modify  ##
## it under the terms of the GNU General Public License as published by  ##
## the Free Software Foundation; either version 2 of the License, or     ##
## (at your option) any later version.                                   ##
##                                                                       ##
###########################################################################
#!/bin/bash

# Sender of the mail
#SENDER="ip.io.watchdog@gmail.com"
SENDER=$(cat /opt/innotune/settings/sendmail_data.txt | head -n1  | tail -n1)

# Recipient of the mail
#RECIPIENT="severin@elmecker.net"
RECIPIENT=$(cat /opt/innotune/settings/sendmail_data.txt | head -n2  | tail -n1)

# SMTP server
#SMTPSERVER="smtp.gmail.com"
SMTPSERVER=$(cat /opt/innotune/settings/sendmail_data.txt | head -n3  | tail -n1)

# User name on the SMTP server
#SMTPUSERNAME="ip.io.watchdog@gmail.com"
SMTPUSERNAME=$(cat /opt/innotune/settings/sendmail_data.txt | head -n4  | tail -n1)

# Password on the SMTP server
#SMTPPASSWORD="watchdog2015"
SMTPPASSWORD=$(cat /opt/innotune/settings/sendmail_data.txt | head -n5  | tail -n1)

# Enable TLS for the SMTP connection
USETLS=1

###################################################################
# NORMALLY THERE IS NO NEED TO CHANGE ANYTHING BELOW THIS COMMENT #
###################################################################

# Use first argument as mail subject
if [ -n "$1" ]; then
  SUBJECT="$1"
else
  # No subject specified
  SUBJECT=""
fi

# Use second argument as mail body
if [ -n "$2" ]; then
  BODY="$2"
else
  # No mail body specified
  BODY=""
fi

# Generate the options list for sendEmail
OPTIONS=""

if [ -n "${SMTPSERVER}" ]; then
  OPTIONS="${OPTIONS} -s ${SMTPSERVER}"
fi

if [ -n "${SMTPUSERNAME}" ]; then
  OPTIONS="${OPTIONS} -xu ${SMTPUSERNAME}"
fi

if [ -n "${SMTPPASSWORD}" ]; then
  OPTIONS="${OPTIONS} -xp ${SMTPPASSWORD}"
fi

if [ -n "${USETLS}" ]; then
  if [ ${USETLS} == 1 ]; then
    OPTIONS="${OPTIONS} -o tls=yes"
  else
    OPTIONS="${OPTIONS} -o tls=no"
  fi
fi

# Send the mail with sendEmail
sendEmail -f ${SENDER} -t ${RECIPIENT} -u "${SUBJECT}" -m "${BODY}" ${OPTIONS}

exit 0