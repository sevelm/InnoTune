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

//temp to start fan 65 degrees celcius
#define CPU_TEMP_ON 65000
//temp to stop fan 60 degrees celcius
#define CPU_TEMP_OFF 60000
// 5 sec delay
#define DELAY_MS 5000

//sensor (ambient) temp to start fan at 55 degrees celcius
#define SENSOR_TEMP_ON 55
//sensor (ambient) temp to stop fan at 50 degrees celcius
#define SENSOR_TEMP_OFF 50

//modes for options file
#define MODE_RELAIS 0
#define MODE_PWM 1

/*
Writes the high/low value to the ping accordingly if the temperature exceeds or
falls unter a certain limit.
*/
int setOutputFan(int temp, int sensorTemp, int currentState) {
    int state = 0;
    int cpuState = 0;
    int sensorState = 0;

    if (sensorTemp != -1) {
        if (sensorTemp >= SENSOR_TEMP_ON) {
            sensorState = 1;
        } else if (sensorTemp <= SENSOR_TEMP_OFF) {
            sensorState = 0;
        } else {
            sensorState = currentState;
        }
    }

    if (temp >= CPU_TEMP_ON) {
        cpuState = 1;
    } else if (temp <= CPU_TEMP_OFF) {
        cpuState = 0;
    } else {
        cpuState = currentState;
    }

    if ((cpuState + sensorState) != 0) {
        state = 1;
    }

    printf("Temp: %d, Sensor-Temp: %d, State: %d\n", temp, sensorTemp, state);

    if (state != currentState) {
        if (state == 1) {
            digitalWrite(FAN_PIN, HIGH);
        } else {
            digitalWrite(FAN_PIN, LOW);
        }
        return state;
    } else {
        return currentState;
    }
}

/*
Writes the pwm value to the pin accordingly to the current cpu temperature.
*/
int setPwmFan(int temp, int sensorTemp, int currentState) {
    int state = 0;
    int stateSensor = 0;

    if (sensorTemp >= 35) {
        stateSensor = 2;
        if (sensorTemp >= 40) {
            stateSensor = 4;
            if (sensorTemp >= 45) {
                stateSensor = 6;
                if (sensorTemp >= 50) {
                    stateSensor = 8;
                    if (sensorTemp >= 55) {
                        stateSensor = 10;
                    }
                }
            }
        }
    }

    if (temp >= 45000) {
        state = 2;
        if (temp >= 50000) {
            state = 4;
            if (temp >= 55000) {
                state = 6;
                if (temp >= 60000) {
                    state = 8;
                    if (temp >= 65000) {
                        state = 10;
                    }
                }
            }
        }
    }

    if (stateSensor > state) {
        state = stateSensor;
    }

    printf("Temp: %d, State: %d, Sensor-Temp: %d, Sensor-State: %d, PWM: %d\n",
        temp, state, sensorTemp, stateSensor, (102*state));
    pwmWrite(FAN_PIN, (102*state));
    return state;
}

int getSensorTemperature() {
    FILE *tempFile = fopen("/opt/innotune/settings/gpio/sensor_temp", "r");
    int temp = -1;
    if (tempFile != NULL) {
        fscanf(tempFile, "%d", &temp);
        fclose(tempFile);
    } else {
        printf("cannot open file: sensor temperature\n");
    }
    return temp;
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

    int temp, coding, sensorTemp;
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

                sensorTemp = getSensorTemperature();

                if (coding == 1) {
                    //coding 1 equals InnoRack V1
                    if (currentPinMode != MODE_RELAIS) {
                        pinMode(FAN_PIN, OUTPUT);
                        currentPinMode = MODE_RELAIS;
                        printf("Mode: OUTPUT\n");
                    }

                    currentState = setOutputFan(temp, sensorTemp, currentState);
                } else if (coding >= 2) {
                    //coding 2 or higher equals InnoRack V2
                    if (currentPinMode != MODE_PWM) {
                        pinMode(FAN_PIN, PWM_OUTPUT);
                        currentPinMode = MODE_PWM;
                        printf("Mode: PWM\n");
                    }
                    currentState = setPwmFan(temp, sensorTemp, currentState);
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
