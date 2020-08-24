/*******************************************************************************
 *                                  INFO
 *
 * Filename :    fanreg.c
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
 *  This program sets the GPIO pin value for the fan if the cpu temperature
 *  exceeds 70 celcius.
 *
 *                                 NOTES
 *
 *  compile with: gcc -o fanreg fanreg.c -lwiringPi -lpthread
 *
 *  This program is based on blink.c:
 *	Standard "blink" program in wiringPi. Blinks an LED connected
 *	to the first GPIO pin.
 *
 * Copyright (c) 2012-2013 Gordon Henderson. <projects@drogon.net>
 *******************************************************************************
 * This file (blink.c) is part of wiringPi:
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

// Pin - wiringPi pin 26 is HW pin 32.
#define	FAN_PIN 26

//temp to start fan 70 degrees celcius
#define TEMP_ON 65000
//temp to stop fan 65 degrees celcius
#define TEMP_OFF 60000
// 5 sec delay
#define DELAY_MS 5000

//modes for options file
#define MODE_RELAIS 0
#define MODE_PWM 1

/*
Writes the high/low value to the ping accordingly if the temperature exceeds or
falls unter a certain limit.
*/
int setOutputFan(int temp, int currentState) {
    if (temp >= TEMP_ON) {
        digitalWrite(FAN_PIN, HIGH);
        return 1;
    } else if (temp <= TEMP_OFF) {
        digitalWrite(FAN_PIN, LOW);
        return 0;
    } else {
        return currentState;
    }
}

/*
Writes the pwm value to the pin accordingly to the current cpu temperature.
*/
int setPwmFan(int temp, int currentState) {
    int state = 0;
    if (temp >= 55000) {
        state = 4;
        if (temp >= 60000) {
            state = 5;
            if (temp >= 65000) {
                state = 6;
                if (temp >= 70000) {
                    state = 8;
                    if (temp >= 75000) {
                        state = 10;
                    }
                }
            }
        }
    }
    printf("Temp: %d, State: %d, PWM: %d\n", temp, state, (102*state));
    pwmWrite(FAN_PIN, (102*state));
    return state;
}

/*
Runs the wiring pi setup and sets the ping to output with value low.
*/
void setup() {
    wiringPiSetup();
    pinMode(FAN_PIN, OUTPUT);
    digitalWrite(FAN_PIN, LOW);
}

/*
Main runs the setup and a endlees loop reading the cpu temperature and setting
the gpio pins.
*/
int main (void) {
    printf("Tinkerboard fan regulation\n");

    setup();

    int temp, coding;
    int currentPinMode = -1, currentState = -1;
    for (;;) {
        FILE *codingFile = fopen("/opt/innotune/settings/gpio/coding", "r");

        if (codingFile != NULL) {
            coding = -1;
            fscanf(codingFile, "%d", &coding);
            fclose(codingFile);

            printf("Coding: %d\n", coding);

            if (coding > 0) {
                FILE *tempFile = fopen("/sys/class/thermal/thermal_zone0/temp", "r");

                if (tempFile != NULL) {
                    temp = 0;
                    fscanf(tempFile, "%d", &temp);
                    fclose(tempFile);
                } else {
                    printf("cannot open file: TEMPERATURE\n");
                    temp = -1;
                }

                if (coding == 1) {
                    //coding 1 equals InnoRack V1
                    if (currentPinMode != MODE_RELAIS) {
                        pinMode(FAN_PIN, OUTPUT);
                        currentPinMode = MODE_RELAIS;
                        printf("Mode: OUTPUT\n");
                    }

                    currentState = setOutputFan(temp, currentState);
                } else if (coding >= 2) {
                    //coding 2 or higher equals InnoRack V2
                    if (currentPinMode != MODE_PWM) {
                        pinMode(FAN_PIN, PWM_OUTPUT);
                        currentPinMode = MODE_PWM;
                        printf("Mode: PWM\n");
                    }
                    currentState = setPwmFan(temp, currentState);
                }

                FILE *optionsFile = fopen("/opt/innotune/settings/gpio/fan_options", "w");

                if (optionsFile != NULL) {
                    fprintf(optionsFile, "0;%d;%d", currentPinMode, currentState);
                    fclose(optionsFile);
                } else {
                    printf("cannot open file: FAN_OPTIONS\n");
                }
            }
        } else {
            printf("cannot open file: CODING\n");
        }

        delay(DELAY_MS);
    }

    return 0;
}
