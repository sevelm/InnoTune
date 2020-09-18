/*******************************************************************************
 *                                  INFO
 *
 * Filename :    mutecard.c
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
 *  Test programm to mute a soundcard.
 *
 *                                 NOTES
 *
 *  compile with: gcc -o mutecard mutecard.c -lwiringPi -lpthread
 *
 ******************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include<string.h>
#include <wiringPi.h>

#define DELAY_MS 5000

/*
Parses the pin number and sets the right state path.
Has a endless loop that sets the pin to LOW.

PARAMETER
0:  GPIO Pin Number
*/
int main(int argc, char *argv[]) {
    printf("Tinkerboard Mute Card\n");

    char* p;
    int pin = strtol(argv[1], &p, 10);

    if (*p != '\0') {
        printf("Error parsing pin number\n");
        return 1;
    }

    if (pin < 1 || pin > 31) {
        printf("Pin number is out of range\n");
        printf("Range: 1 - 31\n");
        printf("Actual: %d\n", pin);
        return 3;
    }

    char* path;
    switch (pin) {
        case 7:
            path = "/opt/innotune/settings/gpio/mute/state01";
        break;
        case 2:
            path = "/opt/innotune/settings/gpio/mute/state02";
        break;
        case 22:
            path = "/opt/innotune/settings/gpio/mute/state03";
        break;
        case 24:
            path = "/opt/innotune/settings/gpio/mute/state04";
        break;
        case 6:
            path = "/opt/innotune/settings/gpio/mute/state05";
        break;
        case 27:
            path = "/opt/innotune/settings/gpio/mute/state06";
        break;
        case 28:
            path = "/opt/innotune/settings/gpio/mute/state07";
        break;
        case 29:
            path = "/opt/innotune/settings/gpio/mute/state08";
        break;
        default:
        printf("Error getting id\n");
        return 4;
    }

    printf("GPIO Pin: %d\n", pin);
    wiringPiSetup();
    pinMode(pin, OUTPUT);

    int coding;
    for (;;) {
        FILE *codingFile = fopen("/opt/innotune/settings/gpio/coding", "r");

        if (codingFile != NULL) {
            coding = -1;
            fscanf(codingFile, "%d", &coding);
            fclose(codingFile);

            printf("Coding: %d\n", coding);

            //only access mute logic if coding >= 2 (= InnoRack V2)
            if (coding > 1) {
                digitalWrite(pin, LOW);
                FILE *stateFile = fopen(path, "w");

                if (stateFile != NULL) {
                    fprintf(stateFile, "0;%d", 0);
                    fclose(stateFile);
                } else {
                    printf("cannot open file: STATE\n");
                }
            }
        } else {
            printf("cannot open file: CODING\n");
        }

        delay(DELAY_MS);
    }

    return 0;
}
