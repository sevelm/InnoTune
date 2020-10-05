/*
    innotune_discovery_listener.c

    UDP Client Server programs
    http://openbook.rheinwerk-verlag.de/linux_unix_programmierung/Kap11-016.htm#RxxKap11016040003951F04F112

    Enable UDP-Broadcast:
    http://www.cs.ubbcluj.ro/~dadi/compnet/labs/lab3/udp-broadcast.html
*/

//includes
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <netdb.h>
#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <stdlib.h>
#include <errno.h>

//constants
#define LOCAL_LISTENER_PORT 19900
#define REMOTE_BROADCASTER_PORT 19901
#define BUFFER_SIZE 255
#define MAX_FAILURES 100
#define DISCOVERY_MESSAGE "hello there"

//method prototypes
int createSocket();
int bindPort(int socketHandle);
void handleReceivedMessage(char* messageBuffer,
                           struct sockaddr_in clientAddress);
void listenerLoop(int socketHandle);

/*
main function creates the socket, binds the port and listens for messages
*/
int main(int argc, char **argv) {
    listenerLoop(createSocket());

    return EXIT_SUCCESS;
}

/*
    creates and datagram socket for UDP connections
*/
int createSocket() {
    int socketHandle = socket(AF_INET, SOCK_DGRAM, 0);
    if (socketHandle < 0) {
        printf("couldn't open socket...(%s)\n", strerror(errno));
        exit(EXIT_FAILURE);
    }
    return bindPort(socketHandle);
}

/*
    binds the socket to the listener port
*/
int bindPort(int socketHandle) {
    int bindState;
    struct sockaddr_in serverAddress;
    const int broadcast = 1;

    serverAddress.sin_family = AF_INET;
    serverAddress.sin_addr.s_addr = htonl(INADDR_ANY);
    serverAddress.sin_port = htons(LOCAL_LISTENER_PORT);
    setsockopt(socketHandle, SOL_SOCKET, SO_BROADCAST, &broadcast, sizeof(int));

    bindState = bind(socketHandle, (struct sockaddr *) &serverAddress,
        sizeof(serverAddress));
    if (bindState < 0) {
        printf("couldn't bind port (%s)\n", strerror(errno));
        exit(EXIT_FAILURE);
    }
    return socketHandle;
}

/*
    main loop of the program, listens for incoming UDP messages
*/
void listenerLoop(int socketHandle) {
    int running = 1;
    int failCount = 0;
    int receiveState, addressLength;
    struct sockaddr_in clientAddress;
    char messageBuffer[BUFFER_SIZE];

    while(running) {
        /* initialize buffer */
        memset(messageBuffer, 0, BUFFER_SIZE);

        /* receive messages */
        addressLength = sizeof(clientAddress);
        receiveState = recvfrom(socketHandle, messageBuffer, BUFFER_SIZE, 0,
            (struct sockaddr *) &clientAddress, &addressLength);
        if(receiveState < 0) {
            printf("couldn't receive data...\n");
            failCount++;
            if (failCount >= MAX_FAILURES) {
                running = 0;
            }
        } else {
            failCount = 0;
            handleReceivedMessage(messageBuffer, clientAddress);
        }
    }
    printf("reached max failure count, exiting...\n");
    exit(EXIT_FAILURE);
}

/*
discovery messages will be handled accordingly
invalid data will be logged and discarded
*/
void handleReceivedMessage(char* messageBuffer,
                           struct sockaddr_in clientAddress) {
    if(strcmp(messageBuffer, DISCOVERY_MESSAGE) == 0) {
        printf("received discovery message from %s:%u\n",
            inet_ntoa(clientAddress.sin_addr),
            ntohs(clientAddress.sin_port));

        sendReturnMessage(inet_ntoa(clientAddress.sin_addr), createMessage());
    } else {
        printf("received invalid data from %s:%u\n--> message contains: %s\n",
            inet_ntoa(clientAddress.sin_addr),
            ntohs(clientAddress.sin_port),
            messageBuffer);
    }
}

void sendReturnMessage(char* clientAddress, char* message) {
    struct sockaddr_in remoteServerAddress = getRemoteAddress(clientAddress);
    int socketHandle = createClientSocket();

    int sendState = sendto(socketHandle, message, strlen (message) + 1, 0,
                 (struct sockaddr *) &remoteServAddr,
                 sizeof (remoteServAddr));
    if (sendState < 0) {
       printf("Konnte Daten nicht senden %d\n", i-1 );
       close(socketHandle);
       exit(EXIT_FAILURE);
    }
}

char* createMessage() {
    return "";
}

int createClientSocket() {
    int socketHandle = socket(AF_INET, SOCK_DGRAM, 0);
    if (socketHandle < 0) {
       printf("Kann Socket nicht öffnen (%s) \n", strerror(errno));
       exit (EXIT_FAILURE);
    }

    const int broadcast = 1;
    setsockopt(socketHandle, SOL_SOCKET, SO_BROADCAST, &broadcast, sizeof(int));
    return bindClientPorts(socketHandle);
}

int bindClientPorts(int socketHandle) {
    int bindState;
    struct sockaddr_in clientAddress;

    clientAddress.sin_family = AF_INET;
    clientAddress.sin_addr.s_addr = htonl(INADDR_ANY);
    clientAddress.sin_port = htons(0);
    bindState = bind(socketHandle, (struct sockaddr *) &clientAddress,
        sizeof(clientAddress));
    if (bindState < 0) {
       printf("Konnte Port nicht bind(en) (%s)\n", strerror(errno));
       exit (EXIT_FAILURE);
    }
    return socketHandle;
}

struct sockaddr_in getRemoteAddress(char* clientAddress) {
    struct hostent *host = gethostbyname (argv[1]);
    if(host == NULL) {
      printf("unbekannter Host '%s' \n", clientAddress);
      exit (EXIT_FAILURE);
  } else {
      struct sockaddr_in remoteServerAddress;
      remoteServerAddress.sin_family = host->h_addrtype;
      memcpy((char *) &remoteServerAddress.sin_addr.s_addr,
               host->h_addr_list[0], host->h_length);
      remoteServerAddress.sin_port = htons(REMOTE_BROADCASTER_PORT);
      return remoteServerAddress;
  }
}
