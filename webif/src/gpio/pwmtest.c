/*******************************************************************************
 *                                  INFO
 *
 * Filename :    pwmtest.c
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
 *  This tests the hardware PWM channel.
 *
 *                                 NOTES
 *
 * compile with: gcc -o pwmtest pwmtest.c -lwiringPi -lpthread
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

#include <wiringPi.h>

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>

/*
Sets up the wiring pi library.
Writes ascending values to the pwm pin 26 with a 100ms delay between.
Waits for 20 seconds and then writes descending values with the same delay.
*/
int main(void)
{
  int bright;

  printf("pwm relais test\n");
  wiringPiSetup();
  pinMode (26, PWM_OUTPUT);
  printf("starting pwm 0 - 1023 with 10ms delay\n");
  for (bright = 0 ; bright < 1024 ; bright=bright+4)
  {
      pwmWrite(26, bright);
      delay(100);
  }

  printf("reached max @ 1023, waiting for 5 secs\n");
  delay(20000);

  printf("starting pwm 1023 - 0 with 10ms delay\n");
  for (bright = 1023 ; bright >= 0 ; bright=bright-4)
  {
      pwmWrite(26, bright);
      delay(100);
  }
  printf("exiting test\n");

  return 0;
}
