/* gcc -o sendUDP sendUDP.c -lmpdclient 
# ./sendUDP
#
# 
#
# Funktion:
# Sendet sofort und im 60 sek. Takt des Status von Squeezebox, Airplay und MPD an den Angegeben IP Empfänger
#
# Anwendung: 
# Zum Ein/- Ausschalten von einem externen Audio-Verstärker, Anzeige in Externer Visualisierung oder auf KNX-Taster
#
/*
    Simple udp client
    Silver Moon (m00n.silv3r@gmail.com)
*/
#include<stdio.h> //printf
#include<string.h> //memset
#include<stdlib.h> //exit(0);
#include<arpa/inet.h>
#include<sys/socket.h>
#include<mpd/client.h>
#include<time.h>
#define BUFLEN 512  //Max length of buffer
int LOOP01 = 1;
long t1, sek, FRG_SEND_TIME; // für sekunden zähler 
int AIRPLAY_EIN_GESENDET, AIRPLAY_AUS_GESENDET, MPD_EIN_GESENDET, MPD_AUS_GESENDET;



// Funktion um von einem char das \n zu überschreiben
void chomp(char *str) {
   size_t p=strlen(str);
   /* '\n' mit '\0' überschreiben */
   str[p-1]='\0';
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

   
//#####################Start Main
int main(void)
{
 while ( LOOP01 == 1 )  {
  usleep(10000);

//##################### Sekunden Zähler
    time_t t;     
    t = time(NULL);
    if (t != t1) {
    t1 = t;
    sek = 1+sek;
    }
    if (sek >=10 ) {     // Minuten signal
    FRG_SEND_TIME = 1;
    sek = 0;
    }
    else {
    FRG_SEND_TIME = 0;
    }



//#####################Einlesen der udp.txt

FILE *fp;
int ipos, PORT;
char SERVER[1024];
char FRG_STATUS_SQUEEZE[1024];
char FRG_STATUS_AIRPLAY[1024];
char FRG_STATUS_MPD[1024];


//  Einlesen der sende IP-Adresse (Zeile 2 in udp.txt)      
     ipos = 0;
     if((fp = fopen ("/var/www/udp.txt" , "r"))==NULL)  {    
     printf("Datei konnte nicht geöffent werden \n");
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

//  Einlesen des sende Port (Zeile 3 in udp.txt) 
     PORT = ReadtxtInt (3, "/var/www/udp.txt");

//  Einlesen ob checkbox Squeezebox angehakt (Zeile 4 in udp.txt)      
     ipos = 0;
     if((fp = fopen ("/var/www/udp.txt" , "r"))==NULL)  {    
     printf("Datei konnte nicht geöffent werden \n");
     }
     else {     
          for(ipos=0;ipos<(4-1);ipos++){
             fgets(&FRG_STATUS_SQUEEZE[ipos],1024,fp);
          }
          for(ipos=0;ipos<1;ipos++){
             fgets(&FRG_STATUS_SQUEEZE[ipos],1024,fp);
          }  
       chomp(FRG_STATUS_SQUEEZE);   
     fclose(fp);
     }

//  Einlesen ob checkbox Airplay angehakt (Zeile 5 in udp.txt)      
     ipos = 0;
     if((fp = fopen ("/var/www/udp.txt" , "r"))==NULL)  {    
     printf("Datei konnte nicht geöffent werden \n");
     }
     else {     
          for(ipos=0;ipos<(5-1);ipos++){
             fgets(&FRG_STATUS_AIRPLAY[ipos],1024,fp);
          }
          for(ipos=0;ipos<1;ipos++){
             fgets(&FRG_STATUS_AIRPLAY[ipos],1024,fp);
          }  
       chomp(FRG_STATUS_AIRPLAY);   
     fclose(fp);
     }

//  Einlesen ob checkbox MPD angehakt (Zeile 6 in udp.txt)      
     ipos = 0;
     if((fp = fopen ("/var/www/udp.txt" , "r"))==NULL)  {    
     printf("Datei konnte nicht geöffent werden \n");
     }
     else {     
          for(ipos=0;ipos<(6-1);ipos++){
             fgets(&FRG_STATUS_MPD[ipos],1024,fp);
          }
          for(ipos=0;ipos<1;ipos++){
             fgets(&FRG_STATUS_MPD[ipos],1024,fp);
          }  
       chomp(FRG_STATUS_MPD);   
     fclose(fp);
     }

//printf("Server : %s\n", SERVER);
//printf("Port : %i\n", PORT);
//printf("Status SQ : %s\n", FRG_STATUS_SQUEEZE);
//printf("Status AIR : %s\n", FRG_STATUS_AIRPLAY);
//printf("Status MPD : %s\n", FRG_STATUS_MPD);

//#####################Einlesen ob Airplay Aktiv

int APstatus01 = 0;
int APstatus02 = 0;
int APstatus03 = 0;
int APstatus04 = 0;
int APstatus05 = 0;
int APstatus06 = 0;
int APstatus07 = 0;
int APstatus08 = 0;
int APstatus09 = 0;
int APstatus10 = 0;
int APstatus;

//AirPlay Player01
    FILE *player01;
    player01 = fopen("/var/www/src/status_shairplay01.txt", "r");
    fscanf(player01, "%i", &APstatus01);
    fclose(player01);

//AirPlay Player02
    FILE *player02;
    player02 = fopen("/var/www/src/status_shairplay02.txt", "r");
    fscanf(player02, "%i", &APstatus02); 
    fclose(player02);

//AirPlay Player03
    FILE *player03;
    player03 = fopen("/var/www/src/status_shairplay03.txt", "r");
    fscanf(player03, "%i", &APstatus03); 
    fclose(player03);

//AirPlay Player04
    FILE *player04;
    player04 = fopen("/var/www/src/status_shairplay04.txt", "r");
    fscanf(player04, "%i", &APstatus04); 
    fclose(player04);

//AirPlay Player05
    FILE *player05;
    player05 = fopen("/var/www/src/status_shairplay05.txt", "r");
    fscanf(player05, "%i", &APstatus05); 
    fclose(player05);

//AirPlay Player06
    FILE *player06;
    player06 = fopen("/var/www/src/status_shairplay06.txt", "r");
    fscanf(player06, "%i", &APstatus06); 
    fclose(player06);

//AirPlay Player07
    FILE *player07;
    player07 = fopen("/var/www/src/status_shairplay07.txt", "r");
    fscanf(player07, "%i", &APstatus07); 
    fclose(player07);

//AirPlay Player08
    FILE *player08;
    player08 = fopen("/var/www/src/status_shairplay08.txt", "r");
    fscanf(player08, "%i", &APstatus08); 
    fclose(player08);

//AirPlay Player09
    FILE *player09;
    player09 = fopen("/var/www/src/status_shairplay09.txt", "r");
    fscanf(player09, "%i", &APstatus09); 
    fclose(player09);

//AirPlay Player10
    FILE *player10;
    player10 = fopen("/var/www/src/status_shairplay10.txt", "r");
    fscanf(player10, "%i", &APstatus10); 
    fclose(player10);

// Auswerung Status Airplay
    if (APstatus01 == 1 || APstatus02 == 1 || APstatus03 == 1 || APstatus04 == 1 || APstatus05 == 1 || APstatus06 == 1 || APstatus07 == 1 || APstatus08 == 1 || APstatus09 == 1 || APstatus10 == 1) {
    APstatus = 1;
    }
    else { 
    APstatus = 0;
    }

//#####################Einlesen ob MPD Aktiv   
    int MPDstatus;
    struct mpd_status *status = NULL;
    struct mpd_connection *conn = NULL;
    conn = mpd_connection_new("localhost", 6600, 0);
    status = mpd_run_status(conn);
    enum mpd_state playstate = mpd_status_get_state(status);
        if (playstate == MPD_STATE_PLAY){
        MPDstatus = 1;
        } 
        else {
        MPDstatus = 0;
        } 
    mpd_connection_free(conn);



//printf("Server : %s\n", MPD_STATUS);
 
   
//#####################UDP Senden

    struct sockaddr_in si_other;
    int s, i, slen=sizeof(si_other);
    char buf[BUFLEN];
    char message_AIRPLAY_EIN[BUFLEN] = "Airplay EIN";
    char message_AIRPLAY_AUS[BUFLEN] = "Airplay AUS";
    char message_MPD_EIN[BUFLEN] = "MPD EIN";
    char message_MPD_AUS[BUFLEN] = "MPD AUS";
    char message_SQUEEZE_EIN[BUFLEN] = "Squeeze EIN";
    char message_SQUEEZE_AUS[BUFLEN] = "Squeeze AUS"; 
    if ( (s=socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP)) == -1) {
        die("socket");
    }
 
    memset((char *) &si_other, 0, sizeof(si_other));
    si_other.sin_family = AF_INET;
    si_other.sin_port = htons(PORT);     
    if (inet_aton(SERVER , &si_other.sin_addr) == 0) {
        fprintf(stderr, "inet_aton() failed\n");
        exit(1);
    }
  


//send the message Airplay ON or OFF
if (FRG_STATUS_AIRPLAY[0]) {
      if ((APstatus == 1 && AIRPLAY_EIN_GESENDET == 0) || (APstatus == 1 && FRG_SEND_TIME == 1 ))  {  
         AIRPLAY_EIN_GESENDET = 1;   
         AIRPLAY_AUS_GESENDET = 0;
         if (sendto(s, message_AIRPLAY_EIN, strlen(message_AIRPLAY_EIN) , 0 , (struct sockaddr *) &si_other, slen)==-1) {
            die("sendto()");
        }
        }
     
      if ((APstatus == 0 && AIRPLAY_AUS_GESENDET == 0) || (APstatus == 0 && FRG_SEND_TIME == 1 ))  {    
         AIRPLAY_EIN_GESENDET = 0;   
         AIRPLAY_AUS_GESENDET = 1;      
         if (sendto(s, message_AIRPLAY_AUS, strlen(message_AIRPLAY_AUS) , 0 , (struct sockaddr *) &si_other, slen)==-1) {
            die("sendto()");
        }
        }
}

//send the message MPD ON or OFF
if (FRG_STATUS_MPD[0]) {
      if ((MPDstatus == 1 && MPD_EIN_GESENDET == 0) || (MPDstatus == 1 && FRG_SEND_TIME == 1 ))  {  
         MPD_EIN_GESENDET = 1;   
         MPD_AUS_GESENDET = 0;
         if (sendto(s, message_MPD_EIN, strlen(message_MPD_EIN) , 0 , (struct sockaddr *) &si_other, slen)==-1) {
            die("sendto()");
        }
        }
     
      if ((MPDstatus == 0 && MPD_AUS_GESENDET == 0) || (MPDstatus == 0 && FRG_SEND_TIME == 1 ))  {    
         MPD_EIN_GESENDET = 0;   
         MPD_AUS_GESENDET = 1;      
         if (sendto(s, message_MPD_AUS, strlen(message_MPD_AUS) , 0 , (struct sockaddr *) &si_other, slen)==-1) {
            die("sendto()");
        }
        }
}
    
    close(s);

  }
    return 0;
}














