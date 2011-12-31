//======================================
// THIS SKETCH HAS BEEN CREATED BY THE 
// WEBUINO SIMULATOR TOOL 
//--------------------------------------
// BOARD_TYPE: UNO
// SKETCH_NAME: Simuino Template
//======================================
//                          Simuino Team

//======================================
// Customized Log Text
//======================================
// PINMODE_OUT: 2  "This is output pin 2"
// PINMODE_OUT: 3  "This is output pin 3"
// PINMODE_OUT: 4  "This is output pin 4"

// PINMODE_IN: 10  "This is input pin 10"
// PINMODE_IN: 11  "This is input pin 11"
// PINMODE_IN: 12  "This is input pin 12"

// DIGITALWRITE_LOW:  10  "Boom"
// DIGITALWRITE_HIGH: 10  "Crash"
// DIGITALWRITE_LOW:  11  "Led is off"
// DIGITALWRITE_HIGH: 11  "Led is on"
// DIGITALWRITE_LOW:  12  "Motor not turning"
// DIGITALWRITE_HIGH: 12  "Motor turning"

// DIGITALREAD:  9  "Read from nine"
// DIGITALREAD: 10  "Read from ten"


// ANALOGREAD: 4  "read analog four"
// ANALOGREAD: 5  "read analog five"

//======================================
// Scenario Breakpoints
//======================================
// SCENDIGPIN 10    1    0
// SCENDIGPIN 10   10    1
// SCENDIGPIN 10   80    0
// SCENDIGPIN 10  100    1
// SCENDIGPIN 11    1    0
// SCENDIGPIN 11   20    1
// SCENDIGPIN 11   30    0
// SCENDIGPIN 11   40    1
// SCENDIGPIN 12    1    0
// SCENDIGPIN 12   15    1
// SCENDIGPIN 12   27    0
// SCENDIGPIN 12  100    1

// SCENANAPIN  1    1   120
// SCENANAPIN  2    1    90 
// SCENANAPIN  1   80   322
// SCENANAPIN  2  120    98

//======================================
// Define Pins
//======================================
//--------------------------------------
// Input
//--------------------------------------
int inputPinA=10;
int inputPinB=11;
int inputPinC=12;

//--------------------------------------
// Output
//--------------------------------------
int outputPinA=2;
int outputPinB=3;
int outputPinC=4;

//--------------------------------------
// Analog Pins
//--------------------------------------
int analogPin1=1;
int analogPin2=2;
int analogPin3=3;

//--------------------------------------
// TX / RX 
//--------------------------------------
int txPin=1;
int trPin=0;

//======================================
void setup()
//======================================
{

  Serial.begin(9600); 
  //------------------------------------
  // Define Pinmodes
  //------------------------------------
  pinMode(outputPinA, OUTPUT);
  pinMode(outputPinB, OUTPUT);
  pinMode(outputPinC, OUTPUT);

  pinMode(inputPinA, INPUT);
  pinMode(inputPinB, INPUT);
  pinMode(inputPinC, INPUT);
}

void loop()
{
  int value,valueA,valueB,valueC;

  value = analogRead(analogPin1); 	
  Serial.print("Analog Value pin 1: ");
  Serial.println(value);

  valueA = digitalRead(inputPinA); 	
  Serial.print("Digital Value pin A: ");
  Serial.println(valueA);

  valueB = digitalRead(inputPinB); 	
  Serial.print("Digital Value pin B: ");
  Serial.println(valueB);

  valueC = digitalRead(inputPinC); 	
  Serial.print("Digital Value pin C: ");
  Serial.println(valueC);

  if (valueA == HIGH) {
     digitalWrite(outputPinA, HIGH); 
  } else {
     digitalWrite(outputPinA, LOW); 
  }
  
  switch (valueB)
  {
    case HIGH: 
      digitalWrite(outputPinB, HIGH);
      break;
    case LOW:
      digitalWrite(outputPinB, LOW);
      break;
  }

  if (valueC == HIGH) {
     digitalWrite(outputPinC, HIGH); }
}

