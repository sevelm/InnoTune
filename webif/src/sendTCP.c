
/* gcc -o sendTCP sendTCP.c  
# ./sendTCP
#
# 
#
# Funktion:
# 1. Einlesen ob Airplay gerade läuft von /var/www/src/status_shairplayXX.txt -> Wenn dies Größer 0, dann   
# 2. Master Lautstärkenregler für Squeezebox 0%
# 4. Wenn Airplay nicht mehr läuft /var/www/src/status_shairplayXX.txt -> Wenn dies kleiner gleich 0, dann
# 5. Master Lautstärkenregler für Squeezebox 100%
#
# Anwendung: 
# Squeezebox mute während Airplay aktiv
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
 
#define SERVER "192.168.0.111"
#define BUFLEN 512  //Max length of buffer
#define PORT 9090   //The port on which to send data
 
void die(char *s)
{
    perror(s);
    exit(1);
}
 
int main(void)
{
    struct sockaddr_in si_other;
    int s, i, slen=sizeof(si_other);
    char buf[BUFLEN];
    char message[BUFLEN];
 
    if ( (s=socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP)) == -1)
    {
        die("socket");
    }
 
    memset((char *) &si_other, 0, sizeof(si_other));
    si_other.sin_family = AF_INET;
    si_other.sin_port = htons(PORT);
     
    if (inet_aton(SERVER , &si_other.sin_addr) == 0) 
    {
        fprintf(stderr, "inet_aton() failed\n");
        exit(1);
    }
 
    while(1)
    {
        printf("Enter message : ");
        gets(message);
     

    
        //send the message
        if (sendto(s, message, strlen(message) , 0 , (struct sockaddr *) &si_other, slen)==-1)
        {
            die("sendto()");
        }
         
        //receive a reply and print it
        //clear the buffer by filling null, it might have previously received data
        memset(buf,'\0', BUFLEN);
        //try to receive some data, this is a blocking call
        if (recvfrom(s, buf, BUFLEN, 0, (struct sockaddr *) &si_other, &slen) == -1)
        {
            die("recvfrom()");
        }
         
        puts(buf);
   
    }
 
    close(s);
    return 0;
}