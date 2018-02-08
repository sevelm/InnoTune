
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
int nr = 0;


// Funktion zum Schreiben von Vol in Alsa
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



// Open the PCM audio output device and configure it.
// Returns a handle to the PCM device; needed for other actions.
snd_pcm_t *Audio_openDevice(char* Name_Device)
{
	snd_pcm_t *handle;
	// Open the PCM output
	int err = snd_pcm_open(&handle, Name_Device, SND_PCM_STREAM_PLAYBACK, 0);
	if (err < 0) {
		printf("Play-back open error: %s\n", snd_strerror(err));
		exit(EXIT_FAILURE);
	}
	return handle;
}



// Funktion zum Lese einer Zahl aus bestimmter Zeile aus einer .txt Datei
long ReadtxtInt (int position, char* pfad)
{
     long result = 0;
     FILE *fp;
     int i = 0;
     char out[1024] = "null";
     if((fp = fopen (pfad , "r"))==NULL)  {    
     printf("Datei konnte nicht geöffent werden \n");
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

void die(char *s)
{
    perror(s);
    exit(1);
}


/* 
#########
##                Strat Program
#########
*/
int main(int argc, char *argv[])
{

long result;  
FILE *out;
long out_dev[10];
long play_status[10];
long play_statusli[10];
long play_statusre[10];




// Alle Audio Devices öffnen
nr = 1;
	for (nr = 1; nr < 11; nr++) {
                        char dev_setting[256];
                        char alsa_dev[256];
                        if (nr < 10) {             
                        sprintf(dev_setting, "/opt/innotune/settings/settings_player/dev0%d.txt", nr);  
                        sprintf(alsa_dev, "dmixer0%d", nr);             
                        }
                        else {
                        sprintf(dev_setting, "/opt/innotune/settings/settings_player/dev%d.txt", nr);  
                        sprintf(alsa_dev, "dmixer%d", nr);                          
                        }

                        out_dev[nr] = ReadtxtInt (1, dev_setting);


                        if (out_dev[nr] == 1 || out_dev[nr] == 2 ) {
                        snd_pcm_t *handle = Audio_openDevice(alsa_dev);
//                        printf("Pfad: %s Wert: %ld \n",dev_setting, out_dev[nr]);
                        }
	}


/* 
#########
##                Start Loop
#########
*/
while ( LOOP01 == 1 )  {



 


// Initialisieren
nr = 1;
	for (nr = 1; nr < 11; nr++) {
                             play_status[nr] = 0;
                             play_statusli[nr] = 0;
                             play_statusre[nr] = 0;
	}


// Status lesen 0=inaktiv; 1=aktiv
nr = 1;
	for (nr = 1; nr < 11; nr++) {
                        char myfile[256];
                        char myfileli[256];
                        char myfilere[256];
                        if (nr < 10) {             
                        sprintf(myfile, "/opt/innotune/settings/status_shairplay/status_shairplay0%d.txt", nr);   
                        sprintf(myfileli, "/opt/innotune/settings/status_shairplay/status_shairplayli0%d.txt", nr);  
                        sprintf(myfilere, "/opt/innotune/settings/status_shairplay/status_shairplayre0%d.txt", nr);                           
                        }
                        else {
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



// Abfrage ob Airplay von Player XX aktiv dann andere Quellen MUTE/UNMUTE
nr = 1;
	for (nr = 1; nr < 11; nr++) {
                       char regler[256];
                       char hw[256];
                       char regler_li[256];
                       char regler_re[256];
                        if (nr < 10) {             
                        sprintf(regler, "MuteIfAirplay_0%d", nr);   
                        sprintf(hw, "hw:0%d", nr);   
                        sprintf(regler_li, "MuteIfAirplayli_0%d", nr);   
                        sprintf(regler_re, "MuteIfAirplayre_0%d", nr);                    
                        }
                        else {
                        sprintf(regler, "MuteIfAirplay_%d", nr);   
                        sprintf(hw, "hw:%d", nr);   
                        sprintf(regler_li, "MuteIfAirplayli_%d", nr);   
                        sprintf(regler_re, "MuteIfAirplayre_%d", nr);                         
                        }
                        if (play_status[nr] == 1) {
                             result = SetAlsaVolume (1, regler, hw);
                             }
                        else {
                             result = SetAlsaVolume (100, regler, hw);
                         }
                        if (play_statusli[nr] == 1) {
                             result = SetAlsaVolume (1, regler_li, hw);
                             }
                        else {
                             result = SetAlsaVolume (100, regler_li, hw);
                         }
                        if (play_statusre[nr] == 1) {
                             result = SetAlsaVolume (1, regler_re, hw);
                             }
                        else {
                             result = SetAlsaVolume (100, regler_re, hw);
                         }

                   }




   


  sleep(1); 
  }
return 0;
}