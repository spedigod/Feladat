<?php declare(strict_types=1);

# pont kalkulator
class pointCalculator {

    private array $egyetemek;
    private array $egyetemAdatok;
    private $egyetem;
    private $kar;
    private $szak;
    private array $kotelezoTargyak= ['magyar nyelv és irodalom','történelem','matematika'];
    private array $egyetemKotelezo = [];
    private array $egyetemKotelezoenValaszthato = [];
    private array $pontSzamitashoz = [];
    private array $osszesSikeresVizsgaEredmeny = [];

    
    
    public function __construct(array $egyetemekTomb){
        $this->egyetemek = $egyetemekTomb;
    }

    public function calculatePoints(array $exampleData){
        // Letezik e a tanulo altal valasztott egyetem a tombben
        if (array_key_exists($exampleData['valasztott-szak']['egyetem'], $this->egyetemek)) {
            // Kivalasztjuk azt az egyetemet
            $this->egyetem = $exampleData['valasztott-szak']['egyetem'];
            $this->kar = $exampleData['valasztott-szak']['kar'];
            $this->szak = $exampleData['valasztott-szak']['szak'];
            $this->egyetemAdatok[$this->egyetem] = $this->egyetemek[$this->egyetem];
            $this->egyetemKotelezo = $this->egyetemAdatok[$this->egyetem][$this->kar][$this->szak]['kovetelmenyek']['kotelezo-targy'];
            $this->egyetemKotelezoenValaszthato = $this->egyetemAdatok[$this->egyetem][$this->kar][$this->szak]['kovetelmenyek']['kotelezoen-valaszthato'];
            
            // Vegigporgetjuk a tanulo erettsegi tantargyait
            for ($key = 0; $key < count($exampleData['erettsegi-eredmenyek']); $key++) { 
                
                // Megnezzuk sikeres e az adott targy
                if ($exampleData['erettsegi-eredmenyek'][$key]['eredmeny'] >= '20%') {
                    // Hozzaadjuk a sikeres tombhoz a tantargyat
                    $this->osszesSikeresVizsgaEredmeny[$exampleData['erettsegi-eredmenyek'][$key]['nev']] = ['tipus' => $exampleData['erettsegi-eredmenyek'][$key]['tipus'], 'eredmeny' => $exampleData['erettsegi-eredmenyek'][$key]['eredmeny']];
                
                // Megnezzuk van e olyan targy ami sikertelen es ott van a kotelezok kozott
                } elseif (($exampleData['erettsegi-eredmenyek'][$key]['eredmeny'] < '20%') && in_array($exampleData['erettsegi-eredmenyek'][$key]['nev'], $this->kotelezoTargyak)) {
                    // Ha van ilyen
                    return "hiba, nem lehetséges a pontszámítás a ". $exampleData['erettsegi-eredmenyek'][$key]['nev'] . " tárgyból elért 20% alatti eredmény miatt";

                }
                // A maradekot ami nem sikerult de nem is alap targy azt elhanyagoljuk innentol
            }
            // Vegignezzuk a sikeres tantargyak listajat megfelel e a tanulo minden elvartnak
            if (count(array_intersect($this->kotelezoTargyak, array_keys($this->osszesSikeresVizsgaEredmeny))) != 3) {
                // Nincs meg mind3 alap erettsegi targy
                return "hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt";

            } elseif (count(array_diff(array_keys($this->egyetemKotelezo), array_keys($this->osszesSikeresVizsgaEredmeny))) > 0) {
                // Az egyetem altal kovetelt erettsegik kozul hianyzik legalabb 1
                return "hiba, nem lehetseges a pontszamitas mivel az egyetem altal kovetelt targy hianyzik";

            } elseif (count(array_intersect($this->egyetemKotelezoenValaszthato, array_keys($this->osszesSikeresVizsgaEredmeny))) == 0) {
                // Nem tett 1 olyan targyol se sikeres erettsegit amibol minimum 1-et kotelezoen valasztania kell
                return "hiba, nem lehetseges a pontszamitas mivel 1 kotelezoen valaszthato targybol sem tett sikeres erettsegit";

            } elseif (count(array_intersect($this->egyetemKotelezoenValaszthato, array_keys($this->osszesSikeresVizsgaEredmeny))) >= 1) {
                // Egynel tobb kotelezoen valaszthato tantagybol tett sikeres erettsegit, igy azt vesszuk figyelembe pontszamitasnal amelyik jobb lett
                for ($i = 0; $i < count($this->egyetemKotelezoenValaszthato); $i++) { 
                    if (in_array($this->egyetemKotelezoenValaszthato[$i], array_keys($this->osszesSikeresVizsgaEredmeny))) {
                        $this->pontSzamitashoz[$i][$this->egyetemKotelezoenValaszthato[$i]] = $this->osszesSikeresVizsgaEredmeny[$this->egyetemKotelezoenValaszthato[$i]];
                    }
                }
                for ($i = 1; $i < count($this->pontSzamitashoz); $i++) {
                    // A rosszabb eredmenyu erettsegit nem vesszuk figyelembe
                    if ($this->pontSzamitashoz[$i][implode(array_keys($this->pontSzamitashoz[$i]))]['eredmeny'] < $this->pontSzamitashoz[$i+1][implode(array_keys($this->pontSzamitashoz[$i+1]))]['eredmeny']) {
                        unset($this->pontSzamitashoz[$i]);
                    } else {
                        unset($this->pontSzamitashoz[$i+1]);
                    }
                }
                $this->pontSzamitashoz[][implode(array_keys($this->egyetemKotelezo))] = $this->osszesSikeresVizsgaEredmeny[implode(array_keys($this->egyetemKotelezo))];

                // Tomb rendezese 
                $this->pontSzamitashoz = array_unique($this->pontSzamitashoz, SORT_REGULAR);
                $tomb = array_values($this->pontSzamitashoz);

                // Osszpontok kiszamitasa
                $osszPontok = 0;
                for ($i = 0; $i < count($this->pontSzamitashoz); $i++) {
                    $osszPontok += substr($tomb[$i][implode(array_keys($tomb[$i]))]['eredmeny'], 0, -1);
                    
                }
                // Megduplazzuk az Osszpontokat
                $osszPontok = $osszPontok * 2;

                // Tobbletpontok szamitasa
                $tobbletPontok = 0;
                for ($i = 0; $i < count($this->pontSzamitashoz); $i++) { 
                    if ($tomb[$i][implode(array_keys($tomb[$i]))]['tipus'] == 'emelt') {
                        $tobbletPontok += 50;
                    }
                    if ($exampleData['tobbletpontok'][$i]['tipus'] == strtoupper('B2')) {
                        $tobbletPontok += 28;
                    }
                    if ($exampleData['tobbletpontok'][$i]['tipus'] == strtoupper('C1')) {
                        $tobbletPontok += 40;
                    }
                }
                // Max 100 lehet a tobbletpont
                if ($tobbletPontok > 100) {
                    $tobbletPontok = 100;
                }

                // Pontok veglegesitese es visszakuldese
                return $osszPontok + $tobbletPontok ." ($osszPontok alappont + $tobbletPontok többletpont)";

            } else {
                return 'Hiba';
            }
        } else {
            // Nem szerepel a beirt iskola a tablazatban
            return "Hiba, nincs a listaban az altalad beirt egyetem!";
        }
    }
}