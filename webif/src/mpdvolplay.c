
/* gcc -o mpdvolplay mpdvolplay.c -lasound -lmpdclient
                               ./mpdvolplay 1
#                                                             |
#   Anfangszahl zum lesen der Setting-Daten >-----------------
#
# Funktion:
# 1. Einlesen der Settings von /opt/innotune/settings/mpdvolplay.txt
# 2. MPD Lautstärke setzen
# 3. Master Lautstärkenregler für Airplay&Squeezebox&... setzen
# 3. MPD Clear Playlist, Load Playlist, MPD Play
# 4. MPD fertig gespielt (nicht mehr Status Play), dann
# 5. Master Lautstärkenregler für Airplay&Squeezebox&... 100%
#
# Um die Lautstärke bei gesplitteten Verstärkern seperat zu regelen kann dies mit einem ; (Semicolon) gemacht werden.
# dazu wird einfach der Wert <li>;<re> angegeben. (z.B. 0;50 heisst rechts 50% und links stumm geschalten)
#
# Anwendung:
# Die Master-Lautstärke anderer Quellen reduzieren während Haustürgong, Sprachdurchsage, .... vom MPD
#
*/


#include <alsa/asoundlib.h>
#include <alsa/mixer.h>
#include <mpd/client.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// Funktion: Setze Alsa Volume
long SetAlsaVolume (int volume, char* devicePrefix, char* hwPrefix, int nr)
{
	long result = -1;
	long getvol;
	long min, max;
	snd_mixer_t *handle;
	snd_mixer_selem_id_t *sid;

	char device[32];
	char hw[16];

	sprintf(device, "%s%02d", devicePrefix, nr);
	//sndc prefix for soudncard name
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

// Funktion zum Lese einer Zahl aus bestimmter Zeile aus einer .txt Datei
long* ReadtxtInt (int position, char* pfad)
{
	long* result = malloc(2 * sizeof(long));
	result[0] = -1;
	result[1] = -1;

	FILE *fp;
	int i;
	char out[1024];
	char *li;
	char *re;
	if((fp = fopen (pfad , "r"))==NULL)  {
		printf("Datei konnte nicht geöffent werden \n");
	} else {
		for(i=0;i<(position-1);i++){
			fgets(&out[i],1024,fp);
		}
		for(i=0;i<1;i++){
			fgets(&out[i],1024,fp);
		}

		if (strstr(out, "/")) {
			li = strtok(out, "/");
			re = strtok(NULL, "/");
			result[0]=atol(li);
			result[1]=atol(re);
		} else {
			result[0]=atol(out);
			result[1]=atol(out);
		}
		fclose(fp);
	}
	return result;
}

// Funktion um von einem char das \n zu überschreiben
void chomp(char *str) {
	size_t p=strlen(str);
	/* '\n' mit '\0' überschreiben */
	str[p-1]='\0';
}

long max (long *values) {
	long max = 0;
	int i;
	int len = sizeof(values)/sizeof(values[0]);
	for (i = 0; i < len; i++) {
		max = max > values[i] ? max:values[i];
	}
	return max;
}

int main(int argc, char *argv[])
{

	int START_NR = atol(argv[1]);
	long result;
	long* read;
	long SQ_AIR_VOLUME;
	long VOL_MPD[10];
	long VOL_MPD_LI[10];
	long VOL_MPD_RE[10];
	char MPD_TITLE[1024];

	int nr = 0;
	START_NR = ((START_NR*12)-11);

	//  Einlesen der Lautstärke von Squeezbox&Airplay
	read = ReadtxtInt (START_NR+1, "/opt/innotune/settings/mpdvolplay.txt");
	if (max(read) >= 0) {
		SQ_AIR_VOLUME = max(read)+1;
	}
	free(read);

	for (nr = 0; nr < 10; nr++) {
		//  Einlesen der Lautstärke von PlayerXX
		read = ReadtxtInt ((START_NR+2+nr), "/opt/innotune/settings/mpdvolplay.txt");
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

	//  Einlesen des MPD Playlist Namens
	FILE *fp;
	int i;
	if((fp = fopen ("/opt/innotune/settings/mpdvolplay.txt" , "r"))==NULL)  {
		printf("Datei konnte nicht geöffent werden \n");
	} else {
		for(i=0;i<(START_NR-1);i++){
			fgets(&MPD_TITLE[i],1024,fp);
		}
		for(i=0;i<1;i++){
			fgets(&MPD_TITLE[i],1024,fp);
		}
		chomp(MPD_TITLE);
		fclose(fp);
	}

	// Laustärke setzen

	for (nr = 0; nr < 10; nr++) {
		//  MPD Lautstärkenregler - PlayerXX
		result = SetAlsaVolume (VOL_MPD[nr], "mpd_", "hw:", nr+1);
		printf("Result: %li, nr: %i\n", result, nr);
		//  MPD Lautstärkenregler - PlayerXX links
		result = SetAlsaVolume (VOL_MPD_LI[nr], "mpdli_", "hw:", nr+1);
		printf("Result: %li, nr-li: %i\n", result, nr);
		//  MPD Lautstärkenregler - PlayerXX rechts
		result = SetAlsaVolume (VOL_MPD_RE[nr], "mpdre_", "hw:", nr+1);
		printf("Result: %li, nr-re: %i\n", result, nr);
	}


	printf("Wert von SQAIR: %li\n", SQ_AIR_VOLUME);
	printf("Wert von MPD: %li\n", VOL_MPD[1]);
	printf("Wert von MPDLI: %li\n", VOL_MPD_LI[1]);
	printf("Wert von MPDRE: %li\n", VOL_MPD_RE[1]);


    //  Master Lautstärkenregler für Airplay & Squeezebox & ... reduzieren
	int SOFT_VOL_DOWN = 100;
    nr = 0;
	do {
		//SOFT_VOL_DOWN = 0;
		SOFT_VOL_DOWN = SOFT_VOL_DOWN - 2;
		if (SOFT_VOL_DOWN < 0) {
			SOFT_VOL_DOWN = 0;
		}

		for (nr = 0; nr <= 10; nr++) {

			if (VOL_MPD[nr] != 0) {
				result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_", "hw:", nr+1);
			}
			if (VOL_MPD_LI[nr] != 0) {
				result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_", "hw:", nr+1);
			}
			if (VOL_MPD_RE[nr] != 0) {
				result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_", "hw:", nr+1);
			}

		}
		usleep(10000);
	} while (SOFT_VOL_DOWN > SQ_AIR_VOLUME);

	// #################   MPD Anfang   #################
	//
	//
	//
	//

	int COUNT = 0;
	const char *TITLE = MPD_TITLE;
	struct mpd_status *status = NULL;
	struct mpd_connection *conn = NULL;
	conn = mpd_connection_new("localhost", 6600, 0);
	if (conn == NULL) {
		printf("cant connect to mpd\n");
		return 1;
	}

	mpd_run_clear(conn);
	mpd_run_load(conn, TITLE);
    mpd_run_play(conn);

	int COUNTER02 = 1;
	while ( COUNTER02 == 1 )
	{
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


	//
	//
	//
	//
	//#################   MPD Ende   #################

	mpd_kein_play:

	do {
		SQ_AIR_VOLUME = SQ_AIR_VOLUME + 2;
		if (SQ_AIR_VOLUME > 100) {
			SQ_AIR_VOLUME = 100;
		}

		for (nr = 0; nr <= 10; nr++) {
			//  Master Lautstärkenregler für Airplay & Squeezebox & ... 100% - PlayerXX
			if (VOL_MPD[nr] != 0) {
				result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_", "hw:", nr+1);
			}
			if (VOL_MPD_LI[nr] != 0) {
				result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_", "hw:", nr+1);
			}
			if (VOL_MPD_RE[nr] != 0) {
				result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_", "hw:", nr+1);
			}
		}
		usleep(10000);
	} while (SQ_AIR_VOLUME < 100);

	return 0;

}
