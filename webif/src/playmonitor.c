
/* gcc -o playmonitor playmonitor.c -lasound 
# ./playmonitor
#
# 
#
# Funktion:
# 1. Einlesen ob Airplay gerade läuft von /opt/innotune/settings/status_shairplay/status_shairplayXX.txt -> Wenn dies Größer 0, dann   
# 2. Master Lautstärkenregler für alle Quellen nach Airplay 0%
# 4. Wenn Airplay nicht mehr läuft /opt/innotune/settings/status_shairplay/status_shairplayXX.txt -> Wenn dies kleiner gleich 0, dann
# 5. Master Lautstärkenregler für alle Quellen nach Airplay 100%
#
# Anwendung: 
# Andere Quellen mute während Airplay aktiv
#
*/


#include <alsa/asoundlib.h>
#include <alsa/mixer.h>
#include <stdlib.h>
#include <stdio.h>

int LOOP01 = 1;

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


int main(int argc, char *argv[])
{
 while ( LOOP01 == 1 )  {

long result;  
  
int status01 = 0;
int status01li = 0;
int status01re = 0;
int status02 = 0;
int status02li = 0;
int status02re = 0;
int status03 = 0;
int status03li = 0;
int status03re = 0;
int status04 = 0;
int status04li = 0;
int status04re = 0;
int status05 = 0;
int status05li = 0;
int status05re = 0;
int status06 = 0;
int status06li = 0;
int status06re = 0;
int status07 = 0;
int status07li = 0;
int status07re = 0;
int status08 = 0;
int status08li = 0;
int status08re = 0;
int status09 = 0;
int status09li = 0;
int status09re = 0;
int status10 = 0;
int status10li = 0;
int status10re = 0;

 
//Player01
    FILE *player01;
    player01 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay01.txt", "r");
    fscanf(player01, "%i", &status01);
    fclose(player01);

    FILE *player01li;
    player01li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli01.txt", "r");
    fscanf(player01li, "%i", &status01li);
    fclose(player01li);

    FILE *player01re;
    player01re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre01.txt", "r");
    fscanf(player01re, "%i", &status01re);
    fclose(player01re);

//Player02
    FILE *player02;
    player02 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay02.txt", "r");
    fscanf(player02, "%i", &status02);
    fclose(player02);

    FILE *player02li;
    player02li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli02.txt", "r");
    fscanf(player02li, "%i", &status02li);
    fclose(player02li);

    FILE *player02re;
    player02re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre02.txt", "r");
    fscanf(player02re, "%i", &status02re);
    fclose(player02re);

//Player03
    FILE *player03;
    player03 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay03.txt", "r");
    fscanf(player03, "%i", &status03);
    fclose(player03);

    FILE *player03li;
    player03li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli03.txt", "r");
    fscanf(player03li, "%i", &status03li);
    fclose(player03li);

    FILE *player03re;
    player03re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre03.txt", "r");
    fscanf(player03re, "%i", &status03re);
    fclose(player03re);

//Player04
    FILE *player04;
    player04 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay04.txt", "r");
    fscanf(player04, "%i", &status04);
    fclose(player04);

    FILE *player04li;
    player04li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli04.txt", "r");
    fscanf(player04li, "%i", &status04li);
    fclose(player04li);

    FILE *player04re;
    player04re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre04.txt", "r");
    fscanf(player04re, "%i", &status04re);
    fclose(player04re);

//Player05
    FILE *player05;
    player05 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay05.txt", "r");
    fscanf(player05, "%i", &status05);
    fclose(player05);

    FILE *player05li;
    player05li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli05.txt", "r");
    fscanf(player05li, "%i", &status05li);
    fclose(player05li);

    FILE *player05re;
    player05re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre05.txt", "r");
    fscanf(player05re, "%i", &status05re);
    fclose(player05re);

//Player06
    FILE *player06;
    player06 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay06.txt", "r");
    fscanf(player06, "%i", &status06);
    fclose(player06);

    FILE *player06li;
    player06li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli06.txt", "r");
    fscanf(player06li, "%i", &status06li);
    fclose(player06li);

    FILE *player06re;
    player06re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre06.txt", "r");
    fscanf(player06re, "%i", &status06re);
    fclose(player06re);

//Player07
    FILE *player07;
    player07 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay07.txt", "r");
    fscanf(player07, "%i", &status07);
    fclose(player07);

    FILE *player07li;
    player07li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli07.txt", "r");
    fscanf(player07li, "%i", &status07li);
    fclose(player07li);

    FILE *player07re;
    player07re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre07.txt", "r");
    fscanf(player07re, "%i", &status07re);
    fclose(player07re);

//Player08
    FILE *player08;
    player08 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay08.txt", "r");
    fscanf(player08, "%i", &status08);
    fclose(player08);

    FILE *player08li;
    player08li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli08.txt", "r");
    fscanf(player08li, "%i", &status08li);
    fclose(player08li);

    FILE *player08re;
    player08re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre08.txt", "r");
    fscanf(player08re, "%i", &status08re);
    fclose(player08re);

//Player09
    FILE *player09;
    player09 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay09.txt", "r");
    fscanf(player09, "%i", &status09);
    fclose(player09);

    FILE *player09li;
    player09li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli09.txt", "r");
    fscanf(player09li, "%i", &status09li);
    fclose(player09li);

    FILE *player09re;
    player09re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre09.txt", "r");
    fscanf(player09re, "%i", &status09re);
    fclose(player09re);

//Player10
    FILE *player10;
    player10 = fopen("/opt/innotune/settings/status_shairplay/status_shairplay10.txt", "r");
    fscanf(player10, "%i", &status10);
    fclose(player10);

    FILE *player10li;
    player10li = fopen("/opt/innotune/settings/status_shairplay/status_shairplayli10.txt", "r");
    fscanf(player10li, "%i", &status10li);
    fclose(player10li);

    FILE *player10re;
    player10re = fopen("/opt/innotune/settings/status_shairplay/status_shairplayre10.txt", "r");
    fscanf(player10re, "%i", &status10re);
    fclose(player10re);


// Abfrage ob Airplay von Player 1 Aktiv andere Quellen MUTE/UNMUTE
    if (status01 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_01", "hw:1");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_01", "hw:1");
    }

    if (status01li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_01", "hw:1");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_01", "hw:1");
    }

    if (status01re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_01", "hw:1");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_01", "hw:1");
    }

// Abfrage ob Airplay von Player 2 Aktiv andere Quellen MUTE/UNMUTE
    if (status02 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_02", "hw:2");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_02", "hw:2");
    }

    if (status02li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_02", "hw:2");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_02", "hw:2");
    }

    if (status02re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_02", "hw:2");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_02", "hw:2");
    }

// Abfrage ob Airplay von Player 3 Aktiv andere Quellen MUTE/UNMUTE
    if (status03 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_03", "hw:3");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_03", "hw:3");
    }

    if (status03li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_03", "hw:3");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_03", "hw:3");
    }

    if (status03re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_03", "hw:3");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_03", "hw:3");
    }

// Abfrage ob Airplay von Player 4 Aktiv andere Quellen MUTE/UNMUTE
    if (status04 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_04", "hw:4");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_04", "hw:4");
    }

    if (status04li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_04", "hw:4");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_04", "hw:4");
    }

    if (status04re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_04", "hw:4");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_04", "hw:4");
    }

// Abfrage ob Airplay von Player 5 Aktiv andere Quellen MUTE/UNMUTE
    if (status05 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_05", "hw:5");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_05", "hw:5");
    }

    if (status05li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_05", "hw:5");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_05", "hw:5");
    }

    if (status05re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_05", "hw:5");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_05", "hw:5");
    }

// Abfrage ob Airplay von Player 6 Aktiv andere Quellen MUTE/UNMUTE
    if (status06 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_06", "hw:6");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_06", "hw:6");
    }

    if (status06li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_06", "hw:6");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_06", "hw:6");
    }

    if (status06re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_06", "hw:6");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_06", "hw:6");
    }

// Abfrage ob Airplay von Player 7 Aktiv andere Quellen MUTE/UNMUTE
    if (status07 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_07", "hw:7");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_07", "hw:7");
    }

    if (status07li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_07", "hw:7");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_07", "hw:7");
    }

    if (status07re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_07", "hw:7");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_07", "hw:7");
    }

// Abfrage ob Airplay von Player 8 Aktiv andere Quellen MUTE/UNMUTE
    if (status08 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_08", "hw:8");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_08", "hw:8");
    }

    if (status08li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_08", "hw:8");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_08", "hw:8");
    }

    if (status08re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_08", "hw:8");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_08", "hw:8");
    }

// Abfrage ob Airplay von Player 9 Aktiv andere Quellen MUTE/UNMUTE
    if (status09 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_09", "hw:9");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_09", "hw:9");
    }

    if (status09li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_09", "hw:9");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_09", "hw:9");
    }

    if (status09re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_09", "hw:9");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_09", "hw:9");
    }

// Abfrage ob Airplay von Player 10 Aktiv andere Quellen MUTE/UNMUTE
    if (status10 == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplay_10", "hw:10");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplay_10", "hw:10");
    }

    if (status10li == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayli_10", "hw:10");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayli_10", "hw:10");
    }

    if (status10re == 1) {            
        result = SetAlsaVolume (1, "MuteIfAirplayre_10", "hw:10");
    }
     else {
        result = SetAlsaVolume (100, "MuteIfAirplayre_10", "hw:10");
    }


  sleep(1); 
  }
return 0;
}