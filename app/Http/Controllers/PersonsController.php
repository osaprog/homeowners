<?php

namespace App\Http\Controllers;

use App\Http\Requests\Persons\PersonStoreRequest;
use App\Http\Requests\Persons\PersonsUploadRequest;
use App\Models\Person;
use App\Repositories\PersonRepository;
use App\Repositories\PersonRepositoryInterface;
class PersonsController extends Controller

{
    protected $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function index()
    {
        return view('welcome', ['persons' => Person::all()]);
    }

    public function store(PersonStoreRequest $personStoreRequest)
    {
        $this->personRepository->store($personStoreRequest);
    }


    public function upload(PersonsUploadRequest $peronsUploadRequest)
    {
        if ($peronsUploadRequest->validated()) {
            $csvContents = file($peronsUploadRequest->file('file_upload')->getRealPath());
            $allPersons = $this->processImport($csvContents);
            $this->storePersons($allPersons);
            return redirect()->route('home', ['persons' => Person::all(), 'success' => 'Persons imported successfully!']);
        }
    }

    public function delete()
    {
        $this->personRepository->deleteAll();
          return redirect()->route('home', ['persons' => Person::all(), 'success' => 'Persons deleted successfully!']);
    }

    public function processImport($csvContents){
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

    private function storePersons($allPersons){
        foreach ($allPersons as $persons) {
            foreach ($persons as $person) {
                $this->personRepository->store($person);
            }
        }
    }
    private function processOnePerson($person){
        if (str_word_count($person) == 1){
            $personToAdd['title'] = trim($person);
        }
        if (str_word_count($person) == 2){
            $personData = explode(" ", $person);
            $personData = array_values(array_filter($personData));
            $personToAdd['title'] = trim($personData[0]);
            $personToAdd['lastname'] = str_replace(",","",trim($personData[1]));
            unset($personData);
        }
        if (str_word_count($person) == 3){
            $personData = explode(" ", $person);
            $personData = array_values(array_filter($personData));
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
}
