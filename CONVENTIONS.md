# CODE CONVENTIONS
Code written before 27.07.2020 as well as code used from any other source may not fulfill these conventions.

Main language of the comments is english.

## 1. File Naming
Words in file names are divided by an underscore and need the corresponding file extension e.g.:
```
this_is_a_script.sh
some_program.c
my_first_website.html
my_first_website.php
my_first_website.js
```

## 2. Bash-Scripts
### 2.1 Header
Following header needs to be inserted at the beginning of the script:
```
#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               script_name.sh                               ##
##                                                                            ##
## Directory:   /                                                             ##
## Created  :   dd.MM.YYYY                                                    ##
## Edited   :   dd.MM.YYYY                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   1. Author-Name                                                ##
##              2. Author-Name                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## The script description goes here. And references to cronjobs, systemd      ##
## services or calls of this script in other scripts etc.                     ##
##                                                                            ##
################################################################################
################################################################################
```

### 2.2 Comments
A single comment line must not exceed 80 chars.
If the comment is longer than 80 chars it should be broken down to multiple
lines.

Single-Line:
```
# This is a comment
```

Multi-Line:
```
# This is a longer comment that needs
# to be on two lines.
```

### 2.3 Code
Code can exceed 80 chars per line.

An intent equals 4 whitespace characters.

A If-Statement has the 'then' keyword in the same line.
Other Statements using the 'do' keyword have the keyword in the following line.
```
if [[ "$oldCount" -lt "$newCount" ]]; then
    # do something here
fi

for((i=0; i<$players; i++))
do
    # do something here
done
```

## 3. C-Files
### 3.1 Header
Following header needs to be inserted at the beginning of the script:

```
/*******************************************************************************
 *                                  INFO
 *
 * Filename :    program.c
 * Directory:    /
 * Created  :    14.05.2019
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    1. Author Name
 *               2. Author Name
 *
 *                              DESCRIPTION
 *
 *  Program description goes here.
 *
 *                                 NOTES
 *
 *  Notes can contain commands for compiling, other useful info about the
 *  program, useful links or other sources and licenses.
 *
 ******************************************************************************/
```

### 3.2 Comments
Add inline comments where appropriate.

Functions must have a block comment to describe its actions.

### 3.3 Code
Code can exceed 80 chars per line.

An intent equals 4 whitespace characters.

## 4. PHP-Files
To be done.

## 5. HTML-Files
HTML Files do not need any headers or comments.

## 6. JavaScript-Files
