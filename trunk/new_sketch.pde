//======================================
// THIS SKETCH HAS BEEN CREATED BY THE 
// WEBUINO SIMULATOR TOOL 
//--------------------------------------
// BOARD_TYPE: UNO
// SKETCH_NAME: Demo
//======================================
//                           Benny Saxen



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
// TX / RX 
//--------------------------------------
int txPin=1;
int trPin=0;

//======================================
void setup()
//======================================
{
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
  if (inputPinA == HIGH) {
     digitalWrite(outputPinA, HIGH); 
  } else {
     digitalWrite(outputPinA, LOW); 
  }
  
  switch (inputPinB)
  {
    case HIGH: 
      digitalWrite(outputPinB, HIGH);
      break;
    case LOW:
      digitalWrite(outputPinB, LOW);
      break;
  }

  if (inputPinC == HIGH) {
     digitalWrite(outputPinC, HIGH); }
}

