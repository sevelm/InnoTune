/*******************************************************************************
 *                                  INFO
 *
 * Filename :    ttsvolplay.c
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
 *  This program reduces the master volume of other audio sources while an tts
 *  audio file is played with the mpd.
 *  The volumes are read from a settings file and are set before playback,
 *  plays the requested mpd playlist and afterwards the original volume is
 *  restored.
 *
 *                                 NOTES
 *
 *  compile with: gcc -o ttsvolplay ttsvolplay.c -lasound -lmpdclient
 *  run with: ./ttsvolplay "name_of_audio_file"
 *
 *  To control the volume of splitted amp the volume value is separated by a
 *  semicolon like : left_vol;right_vol
 *
 ******************************************************************************/

#include <alsa/asoundlib.h>
#include <alsa/mixer.h>
#include <mpd/client.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/time.h>

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
Replaces a '\n' with a '\0'
*/
void chomp(char *str) {
    size_t p = strlen(str);
    str[p - 1] = '\0';
}

/*
Returns the highes value from a list of values
*/
long max(long *values) {
    long max = 0;
    int i;
    int len = sizeof(values) / sizeof(values[0]);
    for (i = 0; i < len; i++) {
        max = max > values[i] ? max : values[i];
    }
    return max;
}

/*
  Program reads the mpd settings, sets the alsa volume controls, plays the
  audio file from a path with the mpd and sets the alsa volume controls back to
  normal.
*/
int main(int argc, char *argv[]) {
    //test values for execution time measurement
    struct timeval tval_before, tval_result;
    struct timeval tval_after, tval_after_vol, tval_after_play, tval_after_stop;
    gettimeofday(&tval_before, NULL);

    char* TTS_TITLE = argv[1];
    long result;
    long* read;
    long SQ_AIR_VOLUME;
    long VOL_MPD[10];
    long VOL_MPD_LI[10];
    long VOL_MPD_RE[10];
    char MPD_TITLE[1024];

    printf("Wert von Title: %s\n", TTS_TITLE);

    int nr = 0;

    //read volume of squeezelite and airplay/spotify
    read = ReadtxtInt (1, "/opt/innotune/settings/voiceoutput/voiceoutputvol.txt");
    if (max(read) >= 0) {
        SQ_AIR_VOLUME = max(read)+1;
    }
    free(read);

    for (nr = 0; nr < 10; nr++) {
        //read volume from playerXX
        read = ReadtxtInt((1 + nr), "/opt/innotune/settings/voiceoutput/voiceoutputvol.txt");
        if (max(read) >= 0) {
            VOL_MPD[nr] = max(read);
        }
        if (read[0] >= 0) {
            VOL_MPD_LI[nr] = read[0];
        }
        if (read[1] >= 0) {
            VOL_MPD_RE[nr] = read[1];
        }
        free(read);
    }

    printf("Wert von SQAIR: %li\n", SQ_AIR_VOLUME);
    printf("Wert von MPD: %li\n", VOL_MPD[2]);
    printf("Wert von MPDLI: %li\n", VOL_MPD_LI[2]);
    printf("Wert von MPDRE: %li\n", VOL_MPD_RE[2]);

	//set tts volume
    for (nr = 0; nr < 10; nr++) {
        //mpd volume control playerXX
        result = SetAlsaVolume(VOL_MPD[nr], "mpd_", "hw:", nr);
        //mpd volume control playerXX left
        result = SetAlsaVolume(VOL_MPD_LI[nr], "mpdli_", "hw:", nr);
        //mpd volume control playerXX right
        result = SetAlsaVolume(VOL_MPD_RE[nr], "mpdre_", "hw:", nr);
	}

    gettimeofday(&tval_after, NULL);
    timersub(&tval_after, &tval_before, &tval_result);
    printf("Time elapsed (VOL): %ld.%06ld s\n", (long int) tval_result.tv_sec, (long int) tval_result.tv_usec);

    //reduce master volume control for airplay/spotify, squeezelite, etc.
    int SOFT_VOL_DOWN = 100;
    do {
        SOFT_VOL_DOWN = SOFT_VOL_DOWN - 4;
        if (SOFT_VOL_DOWN < 0) {
            SOFT_VOL_DOWN = 0;
        }

        for (nr = 0; nr < 10; nr++) {
            if (VOL_MPD[nr] != 0) {
                result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_", "hw:", nr);
            }
            if (VOL_MPD_LI[nr] != 0) {
                result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_", "hw:", nr);
            }
            if (VOL_MPD_RE[nr] != 0) {
                result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_", "hw:", nr);
            }
        }
        usleep(5000);
    } while (SOFT_VOL_DOWN > SQ_AIR_VOLUME);

    gettimeofday(&tval_after_vol, NULL);
    timersub(&tval_after_vol, &tval_before, &tval_result);
    printf("Time elapsed (FAD): %ld.%06ld s\n", (long int) tval_result.tv_sec, (long int) tval_result.tv_usec);

    //mpd start
    int COUNT = 0;
    char *PATH = "file:///media/Soundfiles/tts/";

    //format path
    char* PATHTTS = NULL;
    int argvLen = strlen(argv[1]);
	// add 1 for nul terminator
    PATHTTS = malloc(strlen(PATH) + argvLen + 4 + 1);
    strcpy(PATHTTS , PATH);
    strcat(PATHTTS , argv[1]);
    strcat(PATHTTS , ".mp3");

    printf("Pathtts: %s\n", PATHTTS);

    struct mpd_status *status = NULL;
    struct mpd_connection *conn = NULL;
    conn = mpd_connection_new("localhost", 6600, 0);
    if (conn == NULL) {
        printf("cant connect to mpd\n");
        return 1;
    }

    mpd_run_clear(conn);
    mpd_run_add(conn, PATHTTS);
    mpd_run_play(conn);

    int COUNTER02 = 1;
    while ( COUNTER02 == 1) {
        usleep(250000);
        struct mpd_status *status = NULL;
        struct mpd_connection *conn = NULL;
        conn = mpd_connection_new("localhost", 6600, 0);
        status = mpd_run_status(conn);
        enum mpd_state playstate = mpd_status_get_state(status);
        if (playstate != MPD_STATE_PLAY){
            COUNTER02 = 0;
        }
        mpd_connection_free(conn);
    }
    mpd_connection_free(conn);

    gettimeofday(&tval_after_play, NULL);
    timersub(&tval_after_play, &tval_after_vol, &tval_result);
    printf("Time elapsed (PLA): %ld.%06ld s\n", (long int) tval_result.tv_sec, (long int) tval_result.tv_usec);

	//mpd end
    mpd_kein_play:
    do {
        SQ_AIR_VOLUME = SQ_AIR_VOLUME + 2;
        if (SQ_AIR_VOLUME > 100) {
            SQ_AIR_VOLUME = 100;
        }

        for (nr = 0; nr < 10; nr++) {
            //master volume control for airplay/spotify, squeezelite, etc.
            if (VOL_MPD[nr] != 0) {
                result = SetAlsaVolume(SQ_AIR_VOLUME, "MuteIfMPD_", "hw:", nr);
            }
            if (VOL_MPD_LI[nr] != 0) {
                result = SetAlsaVolume(SQ_AIR_VOLUME, "MuteIfMPDli_", "hw:", nr);
            }
            if (VOL_MPD_RE[nr] != 0) {
                result = SetAlsaVolume(SQ_AIR_VOLUME, "MuteIfMPDre_", "hw:", nr);
            }
        }
        usleep(10000);
    } while (SQ_AIR_VOLUME < 100);

    FILE *f = fopen("/opt/innotune/settings/voiceoutput/current_tts.txt", "w");
    if (f != NULL) {
        fprintf(f, "0");
        fclose(f);
    }

    gettimeofday(&tval_after_stop, NULL);
    timersub(&tval_after_stop, &tval_after_play, &tval_result);
    printf("Time elapsed (FAD): %ld.%06ld s\n", (long int) tval_result.tv_sec, (long int) tval_result.tv_usec);

    return 0;
}
