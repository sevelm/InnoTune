
/* gcc -o mpdvolplay mpdvolplay.c -lasound -lmpdclient
#                                                ./mpdvolplay 1
#                                                             |
#   Anfangszahl zum lesen der Setting-Daten >-----------------
#
# Funktion:
# 1. Einlesen der Settings von /opt/innotune/settings/mpdvolplay.txt
# 2. MPD Lautst�rke setzen 
# 3. Master Lautst�rkenregler f�r Airplay&Squeezebox&... setzen
# 3. MPD Clear Playlist, Load Playlist, MPD Play 
# 4. MPD fertig gespielt (nicht mehr Status Play), dann
# 5. Master Lautst�rkenregler f�r Airplay&Squeezebox&... 100%
#
# Anwendung: 
# Die Master-Lautst�rke anderer Quellen reduzieren w�hrend Haust�rgong, Sprachdurchsage, .... vom MPD
#
*/


#include <alsa/asoundlib.h>
#include <alsa/mixer.h>
#include <mpd/client.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// Funktion: Setze Alsa Volume
long SetAlsaVolume (int volume, char* device, char* hw)
{
    long result = -1;
        long getvol;
        long min, max;
        snd_mixer_t *handle;
        snd_mixer_selem_id_t *sid;
        const char *card = hw;

        snd_mixer_open(&handle, 0);
        snd_mixer_attach(handle, card);
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
long ReadtxtInt (int position, char* pfad)
{
   long result = -1;
     FILE *fp;
     int i;
     char out[1024];
     if((fp = fopen (pfad , "r"))==NULL)  {    
     printf("Datei konnte nicht ge�ffent werden \n");
     }
     else {     
          for(i=0;i<(position-1);i++){
             fgets(&out[i],1024,fp);
          }
          for(i=0;i<1;i++){
             fgets(&out[i],1024,fp);
          }  
      result = atol(out);
     fclose(fp);
     }
   return result;
}

// Funktion um von einem char das \n zu �berschreiben
void chomp(char *str) {
   size_t p=strlen(str);
   /* '\n' mit '\0' �berschreiben */
   str[p-1]='\0';
}

int main(int argc, char *argv[])
{
 
int START_NR = atol(argv[1]);
long result;
long SQ_AIR_VOLUME, VOL_MPD01, VOL_MPD02, VOL_MPD03, VOL_MPD04, VOL_MPD05, VOL_MPD06, VOL_MPD07, VOL_MPD08, VOL_MPD09, VOL_MPD10;
char MPD_TITLE[1024];

    START_NR = ((START_NR*12)-11);

//  Einlesen der Lautst�rke von Squeezbox&Airplay
	result = ReadtxtInt (START_NR+1, "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		SQ_AIR_VOLUME = result+1;

//  Einlesen der Lautst�rke von Player01
	result = ReadtxtInt ((START_NR+2), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD01 = result;

//  Einlesen der Lautst�rke von Player02
	result = ReadtxtInt ((START_NR+3), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD02 = result;

//  Einlesen der Lautst�rke von Player03
	result = ReadtxtInt ((START_NR+4), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD03 = result;

//  Einlesen der Lautst�rke von Player04
	result = ReadtxtInt ((START_NR+5), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD04 = result;

//  Einlesen der Lautst�rke von Player05
	result = ReadtxtInt ((START_NR+6), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD05 = result;

//  Einlesen der Lautst�rke von Player06
	result = ReadtxtInt ((START_NR+7), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD06 = result;

//  Einlesen der Lautst�rke von Player07
	result = ReadtxtInt ((START_NR+8), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD07 = result;

//  Einlesen der Lautst�rke von Player08
	result = ReadtxtInt ((START_NR+9), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD08 = result;

//  Einlesen der Lautst�rke von Player09
	result = ReadtxtInt ((START_NR+10), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD09 = result;

//  Einlesen der Lautst�rke von Player10
	result = ReadtxtInt ((START_NR+11), "/opt/innotune/settings/mpdvolplay.txt");
	if (result >= 0)
		VOL_MPD10 = result;

//  Einlesen des MPD Playlist Namens
     FILE *fp;
     int i;
     if((fp = fopen ("/opt/innotune/settings/mpdvolplay.txt" , "r"))==NULL)  {    
     printf("Datei konnte nicht ge�ffent werden \n");
     }
     else {     
          for(i=0;i<(START_NR-1);i++){
             fgets(&MPD_TITLE[i],1024,fp);
          }
          for(i=0;i<1;i++){
             fgets(&MPD_TITLE[i],1024,fp);
          }  
      chomp(MPD_TITLE);    
     fclose(fp);
     }

//  MPD Lautst�rkenregler - Player01
	result = SetAlsaVolume (VOL_MPD01, "mpd_01", "hw:1");
//  MPD Lautst�rkenregler - Player01 links
	result = SetAlsaVolume (VOL_MPD01, "mpdli_01", "hw:1");
//  MPD Lautst�rkenregler - Player01 rechts
	result = SetAlsaVolume (VOL_MPD01, "mpdre_01", "hw:1");

//  MPD Lautst�rkenregler - Player02
	result = SetAlsaVolume (VOL_MPD02, "mpd_02", "hw:2");
//  MPD Lautst�rkenregler - Player02 links
	result = SetAlsaVolume (VOL_MPD02, "mpdli_02", "hw:2");
//  MPD Lautst�rkenregler - Player02 rechts
	result = SetAlsaVolume (VOL_MPD02, "mpdre_02", "hw:2");

//  MPD Lautst�rkenregler - Player03
	result = SetAlsaVolume (VOL_MPD03, "mpd_03", "hw:3");
//  MPD Lautst�rkenregler - Player03 links
	result = SetAlsaVolume (VOL_MPD03, "mpdli_03", "hw:3");
//  MPD Lautst�rkenregler - Player03 rechts
	result = SetAlsaVolume (VOL_MPD03, "mpdre_03", "hw:3");

//  MPD Lautst�rkenregler - Player04
	result = SetAlsaVolume (VOL_MPD04, "mpd_04", "hw:4");
//  MPD Lautst�rkenregler - Player04 links
	result = SetAlsaVolume (VOL_MPD04, "mpdli_04", "hw:4");
//  MPD Lautst�rkenregler - Player04 rechts
	result = SetAlsaVolume (VOL_MPD04, "mpdre_04", "hw:4");

//  MPD Lautst�rkenregler - Player05
	result = SetAlsaVolume (VOL_MPD05, "mpd_05", "hw:5");
//  MPD Lautst�rkenregler - Player05 links
	result = SetAlsaVolume (VOL_MPD05, "mpdli_05", "hw:5");
//  MPD Lautst�rkenregler - Player05 rechts
	result = SetAlsaVolume (VOL_MPD05, "mpdre_05", "hw:5");

//  MPD Lautst�rkenregler - Player06
	result = SetAlsaVolume (VOL_MPD06, "mpd_06", "hw:6");
//  MPD Lautst�rkenregler - Player06 links
	result = SetAlsaVolume (VOL_MPD06, "mpdli_06", "hw:6");
//  MPD Lautst�rkenregler - Player06 rechts
	result = SetAlsaVolume (VOL_MPD06, "mpdre_06", "hw:6");

//  MPD Lautst�rkenregler - Player07
	result = SetAlsaVolume (VOL_MPD07, "mpd_07", "hw:7");
//  MPD Lautst�rkenregler - Player07 links
	result = SetAlsaVolume (VOL_MPD07, "mpdli_07", "hw:7");
//  MPD Lautst�rkenregler - Player07 rechts
	result = SetAlsaVolume (VOL_MPD07, "mpdre_07", "hw:7");

//  MPD Lautst�rkenregler - Player08
	result = SetAlsaVolume (VOL_MPD08, "mpd_08", "hw:8");
//  MPD Lautst�rkenregler - Player08 links
	result = SetAlsaVolume (VOL_MPD08, "mpdli_08", "hw:8");
//  MPD Lautst�rkenregler - Player08 rechts
	result = SetAlsaVolume (VOL_MPD08, "mpdre_08", "hw:8");

//  MPD Lautst�rkenregler - Player09
	result = SetAlsaVolume (VOL_MPD09, "mpd_09", "hw:9");
//  MPD Lautst�rkenregler - Player09 links
	result = SetAlsaVolume (VOL_MPD09, "mpdli_09", "hw:9");
//  MPD Lautst�rkenregler - Player09 rechts
	result = SetAlsaVolume (VOL_MPD09, "mpdre_09", "hw:9");

//  MPD Lautst�rkenregler - Player10
	result = SetAlsaVolume (VOL_MPD10, "mpd_10", "hw:10");
//  MPD Lautst�rkenregler - Player10 links
	result = SetAlsaVolume (VOL_MPD10, "mpdli_10", "hw:10");
//  MPD Lautst�rkenregler - Player10 rechts
	result = SetAlsaVolume (VOL_MPD10, "mpdre_10", "hw:10");

int SOFT_VOL_DOWN = 100;

do {
    SOFT_VOL_DOWN = SOFT_VOL_DOWN - 1;

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player01
   if (VOL_MPD01 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_01", "hw:1");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_01", "hw:1");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_01", "hw:1");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player02
   if (VOL_MPD02 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_02", "hw:2");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_02", "hw:2");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_02", "hw:2");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player03
   if (VOL_MPD03 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_03", "hw:3");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_03", "hw:3");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_03", "hw:3");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player04
   if (VOL_MPD04 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_04", "hw:4");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_04", "hw:4");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_04", "hw:4");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player05
   if (VOL_MPD05 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_05", "hw:5");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_05", "hw:5");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_05", "hw:5");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player06
   if (VOL_MPD06 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_06", "hw:6");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_06", "hw:6");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_06", "hw:6");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player07
   if (VOL_MPD07 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_07", "hw:7");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_07", "hw:7");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_07", "hw:7");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player08
   if (VOL_MPD08 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_08", "hw:8");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_08", "hw:8");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_08", "hw:8");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player09
   if (VOL_MPD09 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_09", "hw:9");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_09", "hw:9");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_09", "hw:9");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... reduzieren - Player10
   if (VOL_MPD10 != 0) {
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPD_10", "hw:10");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDli_10", "hw:10");
	result = SetAlsaVolume (SOFT_VOL_DOWN, "MuteIfMPDre_10", "hw:10");
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
        mpd_send_play(conn);
    int COUNTER01 = 1;    
           while ( COUNTER01 == 1 )
           {
             sleep(1);   
             struct mpd_status *status = NULL;
             struct mpd_connection *conn = NULL;
             conn = mpd_connection_new("localhost", 6600, 0);
             status = mpd_run_status(conn);
             enum mpd_state playstate = mpd_status_get_state(status);
             if (playstate == MPD_STATE_PLAY){
                 COUNTER01 = 0;
               }             
             mpd_connection_free(conn);
             COUNT = COUNT + 1;
             if (COUNT > 3) {
                 goto mpd_kein_play;               }  
           }
     int COUNTER02 = 1;   
           while ( COUNTER02 == 1 )
           {
             sleep(1);   
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
    SQ_AIR_VOLUME = SQ_AIR_VOLUME + 1;

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player01
   if (VOL_MPD01 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_01", "hw:1");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_01", "hw:1");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_01", "hw:1");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player02
   if (VOL_MPD02 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_02", "hw:2");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_02", "hw:2");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_02", "hw:2");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player03
   if (VOL_MPD03 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_03", "hw:3");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_03", "hw:3");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_03", "hw:3");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player04
   if (VOL_MPD04 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_04", "hw:4");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_04", "hw:4");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_04", "hw:4");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player05
   if (VOL_MPD05 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_05", "hw:5");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_05", "hw:5");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_05", "hw:5");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player06
   if (VOL_MPD06 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_06", "hw:6");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_06", "hw:6");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_06", "hw:6");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player07
   if (VOL_MPD07 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_07", "hw:7");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_07", "hw:7");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_07", "hw:7");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player08
   if (VOL_MPD08 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_08", "hw:8");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_08", "hw:8");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_08", "hw:8");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player09
   if (VOL_MPD09 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_09", "hw:9");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_09", "hw:9");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_09", "hw:9");
    }

   //  Master Lautst�rkenregler f�r Airplay & Squeezebox & ... 100% - Player10
   if (VOL_MPD10 != 0) {
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPD_10", "hw:10");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDli_10", "hw:10");
	result = SetAlsaVolume (SQ_AIR_VOLUME, "MuteIfMPDre_10", "hw:10");
    }
  usleep(10000);
} while (SQ_AIR_VOLUME < 100);

   return 0;

}



