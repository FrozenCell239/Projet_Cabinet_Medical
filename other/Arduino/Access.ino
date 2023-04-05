#include <SPI.h>
#include <Ethernet.h>
#include <Keypad.h>
#include "Relay.h" //Be careful about the path of this one if you downloaded it manually as I did.
#include <MFRC522.h>

#define BUZZER_PIN 49
#define PIR_PIN 2
#define SS_PIN 53 /*RFID*/
#define RST_PIN 48 /*RFID*/
#define DEBUG_PIN 4 //Declare this pin but put nothing on it to fix a bug on the Ethernet shield (pin reserved for SD card).
const byte ROWS = 4, COLS = 4; /*Keypad.*/

byte
    mac[] = {0xA8, 0x61, 0x0A, 0xAE, 0x96, 0x1D},
    rowPins[ROWS] = {29, 27, 25, 23}, /*Keypad.*/
    colPins[COLS] = {28, 26, 24, 22}, /*Keypad.*/
    data_count = 0, /*Keypad.*/
    master_count = 0 /*Keypad.*/
;
EthernetClient client;
IPAddress ip(192, 168, 1, 177); //Arduino board's IP.
IPAddress dns(192, 168, 1, 1);
char
    reply, //Used to read the response from the server.
    customKey, //Stores the last key pressed on keypad.
    HOST_NAME[] = "192.168.1.70", //Server IP address/
    data[11], //Doorcode can contain up to 10 characters.
    hexaKeys[ROWS][COLS] = { /*Keypad.*/
        {'1', '2', '3', 'A'},
        {'4', '5', '6', 'B'},
        {'7', '8', '9', 'C'},
        {'*', '0', '#', 'D'}
    }
;
bool opened = false;
Keypad customKeypad = Keypad(makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS);
Relay strikeRelay(30);
Relay doorRelay(31);
MFRC522 rfid(SS_PIN, RST_PIN); //Instance of the class.
MFRC522::MIFARE_Key key; /*RFID.*/
String to_check;

void setup(){
    Serial.begin(9600);
    Serial.println("\nStart init...");
    pinMode(DEBUG_PIN, OUTPUT); /*Ethernet shield.*/
    digitalWrite(DEBUG_PIN, HIGH); /*Ethernet shield.*/
    pinMode(BUZZER_PIN, OUTPUT);
    pinMode(PIR_PIN, INPUT);
    Ethernet.begin(mac, ip, dns); //Initializing the Ethernet shield not using DHCP.
    SPI.begin(); //Init SPI bus.
    rfid.PCD_Init(); //Init MFRC522.
    rfid.PCD_SetAntennaGain(rfid.RxGain_max); //Set antenna's gain to max to make it work correctly.
    Serial.println("Inited !");
};

void loop(){
    //while(client.connected()){ À tester !!
    //    if(client.available()){ // C : Arduino get .php > .php read .txt > .php return to .ino value from .txt;
    //        reply = client.read(); //Read an incoming byte from the server.
    //        Serial.print(reply); //Print it to serial monitor.
    //        if(reply == '$'){unlockDoor();};
    //    };
    //};
    //getOrder();
////Keypad.////////////////////////////////////////////////////////////////////////////////////////////
    customKey = customKeypad.getKey(); /*Keypad.*/
    if(customKey && customKey != '#'){ /*Keypad.*/
        data[data_count] = customKey;
        Serial.print(data[data_count++]);
    };
    if(customKey == '#'){ /*Keypad.*/
        doorcodeCheck();
    };
////RFID.////////////////////////////////////////////////////////////////////////////////////////////
    if(!rfid.PICC_IsNewCardPresent()){return;}; //Resets the loop if no new card present on the reader. This saves the entire process when idle.
    if(!rfid.PICC_ReadCardSerial()){return;}; //Verify if the NUID has been readed.
    Serial.print(F("PICC type : "));
    MFRC522::PICC_Type piccType = rfid.PICC_GetType(rfid.uid.sak);
    Serial.println(rfid.PICC_GetTypeName(piccType));
    if( //Checks if the PICC is of Classic MIFARE type.
        piccType != MFRC522::PICC_TYPE_MIFARE_MINI &&  
        piccType != MFRC522::PICC_TYPE_MIFARE_1K &&
        piccType != MFRC522::PICC_TYPE_MIFARE_4K
    ){
        Serial.println(F("Your tag is not of type MIFARE Classic."));
        return;
    };
    Serial.println(F("A card has been detected."));
    for(byte i = 0; i < 4; i++){ //Stores newly readed NUID into a string.
        to_check.concat(String(rfid.uid.uidByte[i]));
    };
    Serial.print(F("The NUID tag is :"));
    printDec(rfid.uid.uidByte, rfid.uid.size);
    Serial.println();
    rfidCheck();
    rfid.PICC_HaltA(); //Halt PICC.
    rfid.PCD_StopCrypto1(); //Stop encryption on PCD.
};

void unlockDoor(){
    strikeRelay.on();
    Serial.println("- Strike unlocked !");
    //while(digitalRead(PIR_PIN) == HIGH){;};
    delay(2000);
    strikeRelay.off();
    Serial.println("- Strike locked !");
    delay(100);
};

void openDoor(){
    strikeRelay.on();
    Serial.println("- Strike unlocked !");
    delay(1500);
    doorRelay.on();
    Serial.println("- Door unlocked !");
    //while(digitalRead(PIR_PIN) == HIGH){;};
    delay(3000);
    doorRelay.off();
    Serial.println("- Door locked !");
    delay(1500);
    strikeRelay.off();
    Serial.println("- Strike locked !");
    delay(100);
};

void refused(){
    Serial.println("Accès refusé !");
    digitalWrite(BUZZER_PIN, HIGH);
    delay(1000);
    digitalWrite(BUZZER_PIN, LOW);
    delay(100);
};

void getOrder(){ /*Une fonction que j'essaie de créer pour lire l'ordre venant du serveur.*/
    if(client.connect(HOST_NAME, 80)){ //Connect to web server on port 80.
        Serial.println("Waiting for order...");
        client.print("GET http://localhost/Pages/SNIR_2/Projet/Projet_Cabinet_Medical/src/access/command.php");
        //client.print(to_check);
        client.println(" HTTP/2.0");
        client.println("Host: " + String(HOST_NAME));
        client.println("Connection: close");
        client.println(); //End HTTP header.
        while(client.connected()){
            if(client.available()){
                reply = client.read(); //Read an incoming byte from the server.
                Serial.print(reply); //Print it to serial monitor.
                if(reply == '$'){
                    Serial.println();
                    unlockDoor();
                    opened = true;
                };
                if(reply == '£'){
                    Serial.println();
                    openDoor();
                    opened = true;
                };
            };
        };
        client.stop(); //The server is disconnected, then stop the client.
        Serial.println("\n> Disconnected !");
        if(!opened){
            refused();
            Serial.println("No order received.");
        }
        else{
            opened = false;
            Serial.println("Houston, we got an order !");
        };
    }
    else{Serial.println("\n> Connection failed !");}; //If not connected.
};

void doorcodeCheck(){ /*Keypad.*/
    if(client.connect(HOST_NAME, 80)){ //Connect to web server on port 80.
        Serial.println(" → Checking...");
        client.print("POST http://localhost/Pages/SNIR_2/Projet/Projet_Cabinet_Medical/src/access/access_checker.php?dc=");
        client.print(data);
        client.println(" HTTP/2.0");
        client.println("Host: " + String(HOST_NAME));
        client.println("Connection: close");
        client.println(); //End HTTP header.
        while(client.connected()){
            if(client.available()){
                reply = client.read(); //Read an incoming byte from the server.
                Serial.print(reply); //Print it to serial monitor.
                if(reply == '$'){
                    Serial.println();
                    unlockDoor();
                    opened = true;
                };
            };
        };
        client.stop(); //The server is disconnected, then stop the client.
        Serial.println("\n> Disconnected !");
        if(!opened){
            refused();
        }
        else{opened = false;};
    }
    else{Serial.println("\n> Connection failed !");}; //If not connected.
    while(data_count != 0){
        data[data_count--] = 0; 
    };
};

void rfidCheck(){ /*RFID*/
    if(client.connect(HOST_NAME, 80)){ //Connect to web server on port 80.
        Serial.println(" → Checking...");
        client.print("POST http://localhost/Pages/SNIR_2/Projet/Projet_Cabinet_Medical/src/access/access_checker.php?rt=");
        client.print(to_check);
        client.println(" HTTP/2.0");
        client.println("Host: " + String(HOST_NAME));
        client.println("Connection: close");
        client.println(); //End HTTP header.
        while(client.connected()){
            if(client.available()){
                reply = client.read(); //Read an incoming byte from the server.
                Serial.print(reply); //Print it to serial monitor.
                if(reply == '$'){
                    Serial.println();
                    unlockDoor();
                    opened = true;
                };
            };
        };
        client.stop(); //The server is disconnected, then stop the client.
        Serial.println("\n> Disconnected !");
        if(!opened){
            refused();
        }
        else{opened = false;};
    }
    else{Serial.println("\n> Connection failed !");}; //If not connected.
    to_check = "";
};

void printDec(byte *buffer, byte bufferSize){ /*RFID*/ //Helper routine to dump a byte array as dec values to Serial.
    for(byte i = 0; i < bufferSize; i++){
        Serial.print(buffer[i] < 0x10 ? " 0" : " ");
        Serial.print(buffer[i], DEC);
    };
};
