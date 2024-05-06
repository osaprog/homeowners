<?php
namespace App\Services;

class ImportService {

    private function getPersonData(string $person): array
    {
        $personData = explode(" ", $person);
        return array_values(array_filter($personData));
    }
    private function processOnePerson(string $person): array
    {
        $personToAdd = [];
        if (str_word_count($person) == 1){
            $personToAdd['title'] = trim($person);
        }

        if (str_word_count($person) == 2){
            $personData = $this->getPersonData($person);
            $personToAdd['title'] = trim($personData[0]);
            $personToAdd['lastname'] = str_replace(",","",trim($personData[1]));
            unset($personData);
        }

        if (str_word_count($person) == 3){
            $personData = $this->getPersonData($person);
            $personToAdd['title'] = trim($personData[0]);

            if( strpos($personData[1], ".")) {
                $personToAdd['initial'] = trim($personData[1]);
            }else{
                $personToAdd['firstname'] = trim($personData[1]);
            }
            $personToAdd['lastname'] = str_replace(",","", trim($personData[2]));
            unset($personData);
        }

        return $personToAdd;

    }

    public function processImport(array $csvContents): array {
        $singlePersonsToAdd = [];
        $combinedPersonsToAdd = [];
        unset($csvContents[0]);
        foreach ($csvContents as $line => $csvContent) {
            if(preg_match('(&|and)', $csvContent, $matches) === 1) {
                $persons = explode($matches[0], $csvContent, 2);
                foreach ($persons as $key => $person) {
                    $combinedPersonsToAdd[$line][$key] = $this->processOnePerson($person);
                }

            } else {
                $singlePersonsToAdd[$line][] = $this->processOnePerson($csvContent);
            }
        }
        // Fill Lastnames
        foreach ($combinedPersonsToAdd as $key => $persons) {
            if (!array_key_exists('lastname', $persons[0])) {
                $combinedPersonsToAdd[$key][0]['lastname'] = $combinedPersonsToAdd[$key][1]['lastname'];
            }
        }

        return $combinedPersonsToAdd + $singlePersonsToAdd;
    }
}
