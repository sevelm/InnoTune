// Funktion um von einem char das \n zu ueberschreiben
void chomp(char *str) {
    size_t p=strlen(str);
    /* '\n' mit '\0' �berschreiben */
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
        printf("Datei konnte nicht geoeffent werden \n");
    } else {
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

// Funktion zum Lese eines Textes aus bestimmter Zeile aus einer .txt Datei
char *ReadtxtChar (int position, char* pfad)
{
    char *result = "null";
    FILE *fp;
    int i = 0;
    char out[1024] = "null";
    if((fp = fopen (pfad , "r"))==NULL)  {
        printf("Datei konnte nicht geoeffent werden \n");
    } else {
        for(i=0;i<(position-1);i++){
            fgets(&out[i],1024,fp);
        }
        for(i=0;i<1;i++){
            fgets(&out[i],1024,fp);
        }
        result = out;
        chomp(result);
        fclose(fp);
    }
    return result;
}









struct sockaddr_in si_other;
    int s, i, slen=sizeof(si_other);
    char buf[BUFLEN];
    char message[BUFLEN] = "Penner";

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

//    while(1)
//    {
     //   printf("Enter message : ");
     //   gets(message);



        //send the message
        if (sendto(s, message, strlen(message) , 0 , (struct sockaddr *) &si_other, slen)==-1)
        {
            die("sendto()");
        }

        //receive a reply and print it
        //clear the buffer by filling null, it might have previously received data
//        memset(buf,'\0', BUFLEN);
        //try to receive some data, this is a blocking call
//        if (recvfrom(s, buf, BUFLEN, 0, (struct sockaddr *) &si_other, &slen) == -1)
//        {
            die("recvfrom()");
//        }

//        puts(buf);

//    }
