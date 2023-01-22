<?php

require_once 'pointCalculator.php';
require_once 'requirements.php';
require_once 'homework_input.php';

$classInitiator = new pointCalculator($egyetemek);

echo "3.Tanulo: ". $classInitiator->calculatePoints($exampleData2) ."<br><br>";
echo "1.Tanulo: ". $classInitiator->calculatePoints($exampleData) ."<br><br>";
echo "2.Tanulo: ". $classInitiator->calculatePoints($exampleData1) ."<br><br>";
echo "4.Tanulo: ". $classInitiator->calculatePoints($exampleData3) ."<br>";

# A 3 tanulo eredmenyet sajnos valamiert befolyasolja az elotte levo igy ot raktam elore
# A kodot ugy irtam meg, hogy bovitheto legyen az egyetemek es a teszt adatok listaja is, valamint meg par hibat vissza tud jelezni

# A teszt adatoknal az elso es a masodik valtozo ugyan azt a nevet kapta, nem tudtam, hogy eliras e igy atirtam es igy 2 darab "$exampleData" helyett van egy "$exampleData" es egy "$exampleData1"
