/*******************************************************************************
 *                                  INFO
 *
 * Filename :    playmonitor.c
 * Directory:    /var/www/src/
 * Created  :    24.08.2017 (initial git commit)
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    Severin Elmecker
 *               Alexander Elmecker
 *               Julian Hoerbst
 *
 *                              DESCRIPTION
 *
 *  This program mutes other audio sources (with lower priority) if
 *  airplay/spotify starts to play.
 *
 *                                 NOTES
 *
 *  compile with: gcc -o playmonitor playmonitor.c -lasound
 *  run with: ./playmonitor
 *
 *  To control the volume of splitted amp the volume value is separated by a
 *  semicolon like : left_vol;right_vol
 *
 ******************************************************************************/

#include <alsa/asoundlib.h>
#include <alsa/mixer.h>
#include <stdlib.h>
#include <stdio.h>

int LOOP01 = 1;
int nr = 0;

/*
Set alsa volume for specific sound card.
*/
long SetAlsaVolume(int volume, char* devicePrefix, char* hwPrefix, int nr) {
    long result = -1;
    long getvol;
    long min, max;
    snd_mixer_t *handle;
    snd_mixer_selem_id_t *sid;

    char device[32];
    char hw[16];

    sprintf(device, "%s%02d", devicePrefix, nr);
    //sndc prefix for soundcard name
    sprintf(hw, "%ssndc%02d", hwPrefix, nr);

    snd_mixer_open(&handle, 0);
    result = snd_mixer_attach(handle, hw);
    if (result != 0) {
        sprintf(hw, "%s%d", hwPrefix, nr);
        result = snd_mixer_attach(handle, hw);
    }
    snd_mixer_selem_register(handle, NULL, NULL);
    snd_mixer_load(handle);
    snd_mixer_selem_id_alloca(&sid);
    snd_mixer_selem_id_set_index(sid, 0);
    snd_mixer_selem_id_set_name(sid, device);
    snd_mixer_elem_t* elem = snd_mixer_find_selem(handle, sid);
    if (elem != NULL && handle != NULL){
        snd_mixer_selem_get_playback_volume_range(elem, &min, &max);
        snd_mixer_selem_set_playback_volume_all(elem, volume * max / 100);
    }
    snd_mixer_close(handle);
    return result;
}

/*
Open the PCM audio output device and configure it.
Returns a handle to the PCM device; needed for other actions.
*/
snd_pcm_t *Audio_openDevice(char* Name_Device)
{
	snd_pcm_t *handle;
	// Open the PCM output
	int err = snd_pcm_open(&handle, Name_Device, SND_PCM_STREAM_PLAYBACK, 0);
	if (err < 0) {
		printf("Play-back open error: %s\n", snd_strerror(err));
	}
	return handle;
}

/*
Reads a integer value from a specific line of a file.
*/
long* ReadtxtInt(int position, char* pfad) {
    long* result = malloc(2 * sizeof(long));
    result[0] = -1;
    result[1] = -1;

    FILE *fp;
    int i;
    char out[1024];
    char *li;
    char *re;
	if ((fp = fopen(pfad, "r")) == NULL)  {
        printf("could not open file!\n");
    } else {
        for (i = 0; i < (position - 1); i++){
            fgets(&out[i], 1024, fp);
        }
        for (i = 0; i < 1; i++){
            fgets(&out[i], 1024, fp);
        }

        if (strstr(out, "/")) {
            li = strtok(out, "/");
            re = strtok(NULL, "/");
            result[0] = atol(li);
            result[1] = atol(re);
        } else {
            result[0] = atol(out);
            result[1] = atol(out);
        }
        fclose(fp);
    }
    return result;
}

/*
Sets an error message and exits the program with code 1
*/
void die(char *s) {
    perror(s);
    exit(1);
}

/*

*/
int main(int argc, char *argv[]) {
    long result;
    FILE *out;
    long out_dev[10];
    long play_status[10];
    long play_statusli[10];
    long play_statusre[10];

    //open all audio devices
    nr = 1;
    for (nr = 1; nr < 11; nr++) {
        char dev_setting[256];
        char alsa_dev[256];
        char oac[256];
        long currentoac;

        if (nr < 10) {
            sprintf(dev_setting, "/opt/innotune/settings/settings_player/dev0%d.txt", nr);
            sprintf(alsa_dev, "dmixer0%d", nr);
            sprintf(oac, "/opt/innotune/settings/settings_player/oac/oac0%d.txt", nr);
        } else {
            sprintf(dev_setting, "/opt/innotune/settings/settings_player/dev%d.txt", nr);
            sprintf(alsa_dev, "dmixer%d", nr);
            sprintf(oac, "/opt/innotune/settings/settings_player/oac/oac%d.txt", nr);
        }

        out_dev[nr] = ReadtxtInt(1, dev_setting);
        currentoac = ReadtxtInt(1, oac);
        printf("OUTPUT:%ld \n",out_dev[nr]);
        printf("Open Ch:%ld \n", currentoac);

        if (currentoac == 1 && (out_dev[nr] == 1 || out_dev[nr] == 2)) {
            printf("ALSA:%s \n",alsa_dev);
            snd_pcm_t *handle = Audio_openDevice(alsa_dev);
            printf("Pfad: %s Wert: %ld \n",dev_setting, out_dev[nr]);
        }
    }

    //start loop
    while ( LOOP01 == 1 )  {
        //initialize
        nr = 1;
        for (nr = 1; nr < 11; nr++) {
            play_status[nr] = 0;
            play_statusli[nr] = 0;
            play_statusre[nr] = 0;
        }

        // read state (0 = inactive, 1 = active)
        nr = 1;
        for (nr = 1; nr < 11; nr++) {
            char myfile[256];
            char myfileli[256];
            char myfilere[256];

            if (nr < 10) {
                sprintf(myfile, "/opt/innotune/settings/status_shairplay/status_shairplay0%d.txt", nr);
                sprintf(myfileli, "/opt/innotune/settings/status_shairplay/status_shairplayli0%d.txt", nr);
                sprintf(myfilere, "/opt/innotune/settings/status_shairplay/status_shairplayre0%d.txt", nr);
            } else {
                sprintf(myfile, "/opt/innotune/settings/status_shairplay/status_shairplay%d.txt", nr);
                sprintf(myfileli, "/opt/innotune/settings/status_shairplay/status_shairplayli%d.txt", nr);
                sprintf(myfilere, "/opt/innotune/settings/status_shairplay/status_shairplayre%d.txt", nr);
            }

            out = fopen(myfile, "r");
            fscanf(out, "%ld", &play_status[nr]);
            fclose(out);

            out = fopen(myfileli, "r");
            fscanf(out, "%ld", &play_statusli[nr]);
            fclose(out);

            out = fopen(myfilere, "r");
            fscanf(out, "%ld", &play_statusre[nr]);
            fclose(out);
        }

        // read if airplay/spotify of playerXX is aktive, if so mute other srcs
        nr = 1;
        for (nr = 1; nr < 11; nr++) {
            char regler[256];
            char hw[256];
            char regler_li[256];
            char regler_re[256];

            if (nr < 10) {
                sprintf(regler, "MuteIfAirplay_0%d", nr);
                //added sndc prefix
                sprintf(hw, "hw:sndc0%d", nr);
                sprintf(regler_li, "MuteIfAirplayli_0%d", nr);
                sprintf(regler_re, "MuteIfAirplayre_0%d", nr);
            } else {
                sprintf(regler, "MuteIfAirplay_%d", nr);
                sprintf(hw, "hw:%d", nr);
                sprintf(regler_li, "MuteIfAirplayli_%d", nr);
                sprintf(regler_re, "MuteIfAirplayre_%d", nr);
            }

            if (play_status[nr] == 1) {
                result = SetAlsaVolume(1, regler, hw);
            } else {
                result = SetAlsaVolume(100, regler, hw);
            }

            if (play_statusli[nr] == 1) {
                result = SetAlsaVolume(1, regler_li, hw);
            } else {
                result = SetAlsaVolume(100, regler_li, hw);
            }

            if (play_statusre[nr] == 1) {
                result = SetAlsaVolume(1, regler_re, hw);
            } else {
                result = SetAlsaVolume(100, regler_re, hw);
            }
        }

        sleep(1);
    }

    return 0;
}
