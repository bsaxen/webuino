//================================================
//  Developed by Benny Saxen
//
//  2011-12-11
//================================================
// SKETCH_NAME: Heat_Control
// BOARD_TYPE: UNO
//================================================
// Simuino log text customization
//================================================
// PINMODE_IN:  2   "PIN:Start"

// PINMODE_OUT: 6   "PIN: MS1"
// PINMODE_OUT: 7   "PIN: MS2"
// PINMODE_OUT: 8   "PIN: Step"
// PINMODE_OUT: 9   "PIN: Dir"
// PINMODE_OUT: 10  "PIN: Sleep"
// PINMODE_OUT: 11  "PIN: Led Pos Digit 1"
// PINMODE_OUT: 12  "PIN: Led Pos Digit 2"
// PINMODE_OUT: 13  "PIN: Led Error"

// DIGITALWRITE_LOW:   6  "MS1 Low"
// DIGITALWRITE_HIGH:  6  "MS1 High"
// DIGITALWRITE_LOW:   7  "MS2 Low"
// DIGITALWRITE_HIGH:  7  "MS2 High"
// DIGITALWRITE_LOW:   8  "Step low"
// DIGITALWRITE_HIGH:  8  "Step high"
// DIGITALWRITE_LOW:   9  "Dir low"
// DIGITALWRITE_HIGH:  9  "Dir high"
// DIGITALWRITE_LOW:  10  "Sleep"
// DIGITALWRITE_HIGH: 10  "Awake"
// DIGITALWRITE_LOW:  11  "Digit 1 off"
// DIGITALWRITE_HIGH: 11  "Digit 1 on"
// DIGITALWRITE_LOW:  12  "Digit 2 off"
// DIGITALWRITE_HIGH: 12  "Digit 2 on"
// DIGITALWRITE_LOW:  13  "No Errors"
// DIGITALWRITE_HIGH: 13  "ERROR"

// ANALOGREAD: 5  "read outdoor temp"
// ANALOGREAD: 4  "read indoor temp"

//-------- DIGITAL PIN settings ------------------
// Interrupt
int INTRPT    =  2;
// EasyDriver
int DIR       =  9; 
int STEP      =  8;
int SLEEP     = 10;
int MS1       =  6;
int MS2       =  7;
// Leds
int DIGIT1    = 11;
int DIGIT2    = 12;
int ERROR     = 13; 
//-------- ANALOGUE PIN settings
int TEMPOUTD  = 5;
int TEMPIND   = 4;
//------------------------------------------------
//Theory
// Utomhus sensor: Temp_ute(Celcius) = -2.13xResistor(kOhm)  + 8.57  
//
// Sensorn seriekopplad med 10 kOhm
//
// 10/14*5 = 3.57 v    max  vid 0 grader Celcius  => 3.57/5*1024 = 731
// 10/26*5 = 1.92 v   min  vid ca -20 grader Celcius  => 1.92/5*1024 = 393
// map(x,393,731,20,0)  from analogRead to minus degrees Celcius
// 2432 steps corresponds to 360 degrees

////// ED_v4  Step Mode Chart //////
//                                //
//   MS1 MS2 Resolution           //
//   L   L   Full step (2 phase)  //
//   H   L   Half step            //
//   L   H   Quarter step         //
//   H   H   Eighth step          //
//                                //
////////////////////////////////////

//================================================
int targetShuntPosition  = 0;
int currentShuntPosition = 0; 
int emergencyStop        = 0;
int minusCelcius         = 0;
int aTempValue           = 0;
int inTempValue          = 0;
int stepMode             = 2;

//================================================
//  Function Declarations
//================================================
void turn_cw(int delta);
void turn_ccw(int delta);
int  set_shunt_position(int from, int to);
void blinkErrorLed(int n);
int faultCode = 0;
//================================================
void stateChange()
//================================================
{
      digitalWrite(DIGIT1, HIGH); 
      digitalWrite(DIGIT2, HIGH); 
      delay(1000);
      digitalWrite(DIGIT1, LOW); 
      digitalWrite(DIGIT2, LOW); 
}
//================================================
void setup()
//================================================
{
  Serial.begin(9600); 

  attachInterrupt(0, stateChange, CHANGE);

  pinMode(INTRPT,   INPUT);   

  pinMode(DIR,     OUTPUT);   
  pinMode(STEP,    OUTPUT);
  pinMode(SLEEP,   OUTPUT); 
  pinMode(DIGIT1,  OUTPUT);
  pinMode(DIGIT2,  OUTPUT);
  pinMode(ERROR,   OUTPUT);
  pinMode(MS1,     OUTPUT);   
  pinMode(MS2,     OUTPUT);


  // Full step
  //digitalWrite(MS1, LOW);  
  //digitalWrite(MS2, LOW); 
  // Half step
  digitalWrite(MS1, HIGH);  
  digitalWrite(MS2, LOW); 
  // Quarter step
  //digitalWrite(MS1, LOW);  
  //digitalWrite(MS2, HIGH); 
  // Eighth step
  //digitalWrite(MS1, HIGH);  
  // digitalWrite(MS2, HIGH); 
}
	 


//================================================ 
void loop()
//================================================
{
 

  stepMode = 2;

  Serial.print("Step Mode:");
  Serial.println(stepMode);

  aTempValue = analogRead(TEMPOUTD); // 4 - 16 kOhm
  Serial.print("SensorOUT:");
  Serial.println(aTempValue);

  inTempValue = analogRead(TEMPIND); // 4 - 16 kOhm
  Serial.print("SensorIN:");
  Serial.println(inTempValue);

  minusCelcius        = map(aTempValue,393,731,20,0);
  Serial.print("Celcius(-):");
  Serial.println(minusCelcius);
  targetShuntPosition = map(minusCelcius,0,10,15,30);
  if(targetShuntPosition < 0)
    {
     Serial.print("Lower Limit Angle:");
     targetShuntPosition = 0;
    }

  else if(targetShuntPosition > 90)
    {
     Serial.print("Upper Limit Angle:");
     targetShuntPosition = 90;
    }
  else
    {
      Serial.print("Angle:");
    }

  Serial.println(targetShuntPosition);
  
  Serial.print(currentShuntPosition); 
  Serial.print("--->");
  Serial.println(targetShuntPosition);
  if(targetShuntPosition < 90 || targetShuntPosition > 0)
    {
      currentShuntPosition = set_shunt_position(currentShuntPosition,targetShuntPosition);
    }
  else
    { 
      blinkErrorLed(faultCode);
    }
  delay(3000); 
}


//================================================
void blinkErrorLed(int n)
//================================================
{
  int i;
  for(i=1;i<=n;i++)
    {
      digitalWrite(ERROR, HIGH); 
      delay(500);
      digitalWrite(ERROR, LOW); 
      delay(500);
    }
}
//================================================
int set_shunt_position(int from, int to)
//================================================
{
  int delta=0;

  // This should not happen
  if(  to > 90 ||   to < 0) return(0);
  if(from > 90 || from < 0) return(0);

  if(from == to) 
    {
      blinkErrorLed(1);
      return(to);
    }

  if(from > to)
    {
      delta = from-to;
      turn_ccw(delta);
    }  
  if(from < to)
    {
      delta = to-from;
      turn_cw(delta);
    }
  return(to);
}
//================================================
void turn_cw(int delta)
//================================================
{
  int i,steps = 0;
  steps = map(delta,0,90,0,50);

  Serial.print("Steps:");
  Serial.println(steps);

  digitalWrite(DIR, LOW);                 
  digitalWrite(SLEEP, HIGH); // Set the Sleep mode to AWAKE.
  for(i=0;i<=steps;i++)
    {
      digitalWrite(STEP, LOW); 
      digitalWrite(STEP, HIGH);
      delayMicroseconds(1400);       
    }  
  digitalWrite(SLEEP, LOW); // Set the Sleep mode to SLEEP.   
}

//================================================
void turn_ccw(int delta)
//================================================
{
  int i,steps = 0;
  steps = map(delta,0,90,0,50);

  Serial.print("Steps:");
  Serial.println(steps);

    
  digitalWrite(DIR, HIGH);                 
  digitalWrite(SLEEP, HIGH); // Set the Sleep mode to AWAKE.
  for(i=0;i<=steps;i++)
    {
      digitalWrite(STEP, LOW);    
      digitalWrite(STEP, HIGH);    
      delayMicroseconds(1400);        
    }  
  digitalWrite(SLEEP, LOW); // Set the Sleep mode to SLEEP.    
}


//================================================
// End of Sketch
//================================================
