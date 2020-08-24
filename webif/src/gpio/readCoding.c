/*******************************************************************************
 *                                  INFO
 *
 * Filename :    readCoding.c
 * Directory:    /var/www/src/gpio/
 * Created  :    14.05.2019
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    Alexander Elmecker
 *
 *                              DESCRIPTION
 *
 *  This program reads the GPIO pin coding values and persists the coding
 *  value in a file.
 *
 *                                 NOTES
 *
 *  compile with: gcc -o readCoding readCoding.c -lwiringPi -lpthread
 *
 *  this program is based on blink.c:
 *	Standard "blink" program in wiringPi. Blinks an LED connected
 *	to the first GPIO pin.
 *
 * Copyright (c) 2012-2013 Gordon Henderson. <projects@drogon.net>
 *******************************************************************************
 * This file is part of wiringPi:
 *	https://projects.drogon.net/raspberry-pi/wiringpi/
 *
 *    wiringPi is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as published
 *    by the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    wiringPi is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with wiringPi.  If not, see <http://www.gnu.org/licenses/>.
 ******************************************************************************/

#include <stdio.h>
#include <wiringPi.h>

// Pin - wiringPi pin 1 is HW pin 12
#define	DI1_PIN 1
// Pin - wiringPi pin 1 is HW pin 15.
#define	DI2_PIN 3
// Pin - wiringPi pin 1 is HW pin 29.
#define	DI3_PIN 21

//15 min delay
#define DELAY_MS 900000

/*
Initializes the wiring pi library.
Sets the coding pins to input and the pull up control to 'pull down'.
*/
void setup(void) {
    wiringPiSetup();

    pinMode(DI1_PIN, INPUT);
    pullUpDnControl(DI1_PIN, PUD_DOWN);

    pinMode(DI2_PIN, INPUT);
    pullUpDnControl(DI2_PIN, PUD_DOWN);

    pinMode(DI3_PIN, INPUT);
    pullUpDnControl(DI3_PIN, PUD_DOWN);
}

/*
Persists the integer value in the coding file
*/
void writeCodingToFile(int code) {
    FILE *codingFile = fopen("/opt/innotune/settings/gpio/coding", "w");

    if (codingFile != NULL) {
        fprintf(codingFile, "%d", code);
        fclose(codingFile);
    } else {
        printf("cannot open file\n");
    }
}

/*
Reads the state of the three gpio input pins and converts it to a decimal
number.
*/
int readCodeFromGPIO() {
    int di1Code = digitalRead(DI1_PIN);
    int di2Code = digitalRead(DI2_PIN);
    int di3Code = digitalRead(DI3_PIN);
    int code = (di3Code * 4) + (di2Code * 2) + (di1Code);
    printf("Bin: 0%d%d%d, Dec: %d\n", di3Code, di2Code, di1Code, code);
    return code;
}

/*
Runs the setup and a endless loop where the coding is read from the gpio and
persisted in a file.
*/
int main (void) {
    printf("Tinkerboard read codings\n");
    setup();

    int code;
    int oldCode = -1;
    for (;;) {
        code = readCodeFromGPIO();
        if (code != oldCode) {
            writeCodingToFile(code);
            oldCode = code;
        }
        delay(DELAY_MS);
    }
    return 0;
}
