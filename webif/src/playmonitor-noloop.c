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
#include <string.h>
#include <arpa/inet.h>
#include <sys/socket.h>
#include <time.h>
#define BUFLEN 512  //Max length of buffer

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
     long result = 0;
     FILE *fp;
     int i = 0;
     char out[1024] = "null";
     if((fp = fopen (pfad , "r"))==NULL)  {
	     printf("Datei konnte nicht geöffent werden \n");
			 return -1;
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

// Funktion um von einem char das \n zu überschreiben
void chomp(char *str) {
   size_t p=strlen(str);
   /* '\n' mit '\0' überschreiben */
   str[p-1]='\0';
}

int SendUDP(int shairplayStarted) {
	struct sockaddr_in si_other;
	int s, i, slen = sizeof(si_other);
	char buf[BUFLEN];
	char message_AIRPLAY_EIN[BUFLEN] = "Airplay EIN";
	char message_AIRPLAY_AUS[BUFLEN] = "Airplay AUS";
	char SERVER[1024];
	FILE *fp;

	//  Einlesen der sende IP-Adresse (Zeile 2 in udp.txt)
  int ipos = 0;
  if((fp = fopen ("/var/www/udp.txt" , "r")) == NULL)  {
  	printf("Datei konnte nicht geöffent werden \n");
		return -1;
  }
  else {
  	for(ipos=0;ipos<(2-1);ipos++){
    	fgets(&SERVER[ipos],1024,fp);
    }
    for(ipos=0;ipos<1;ipos++){
    	fgets(&SERVER[ipos],1024,fp);
    }
    chomp(SERVER);
   	fclose(fp);
  }

	// Einlesen des sende Port (Zeile 3 in udp.txt)
 	int PORT = ReadtxtInt (3, "/var/www/udp.txt");
	if(PORT == -1) {
		return -1;
	}

	if ((s = socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP)) == -1) {
			return -1;
  }

	memset((char *) &si_other, 0, sizeof(si_other));
	si_other.sin_family = AF_INET;
	si_other.sin_port = htons(PORT);
	if (inet_aton(SERVER , &si_other.sin_addr) == 0) {
			fprintf(stderr, "inet_aton() failed\n");
			return -1;
	}

	//send the message Airplay ON or OFF
	if (shairplayStarted == 1)  {
		if (sendto(s, message_AIRPLAY_EIN, strlen(message_AIRPLAY_EIN) ,0 , (struct sockaddr *) &si_other, slen) == -1) {
			return -1;
		}
	} else if (shairplayStarted == 0)  {
		if (sendto(s, message_AIRPLAY_AUS, strlen(message_AIRPLAY_AUS) ,0 , (struct sockaddr *) &si_other, slen) == -1) {
			return -1;
		}
	}

	close(s);
	return 0;
}

int main(int argc, char *argv[]) {
	if(argc < 3) {
    printf("playmonitor needs the player id and action as arguments!");
		return -1;
	}
	/* can have the following values:
   * "li_" + id
	 * "re_" + id
	 * id
	 */
	char* player = argv[1];
  int playerId = atoi(player);

	if(strlen(player) > 2) {
		char tmpid[2];
		tmpid[0] = player[3];
		tmpid[1] = player[4];
		playerId = atoi(tmpid);
	} else {
		playerId = atoi(player);
	}

	//1 (true) for started, 0 for stopped
	int shairplayStarted = atoi(argv[2]);
	char hwName[5];
	snprintf(hwName, 5, "hw:%d",playerId);
  int len = strlen("MuteIfAirplayli_10") + 1;
	char name[len];

  if(strlen(player) > 2) {
 		snprintf(name, len, "MuteIfAirplay%s", player);
	} else {
		snprintf(name, len, "MuteIfAirplay_%s", player);
	}
	printf("playerid: %d ", playerId);
	printf("name: %s", name);
	printf("player: %s", player);
	printf("shairplayStarted: %d", shairplayStarted);

	int volume = shairplayStarted == 1 ? 1 : 100;
	int result = SetAlsaVolume(volume, name, hwName);
	result = SendUDP(shairplayStarted);

	return result;
}
