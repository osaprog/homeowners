<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\Persons\PersonStoreRequest;
use App\Http\Requests\Persons\PersonsUploadRequest;
use App\Models\Person;
use App\Repositories\PersonRepository;
use App\Services\ImportService;
use \Illuminate\Http\RedirectResponse;

#[AllowDynamicProperties] class PersonsController extends Controller
{
    public function __construct(PersonRepository $personRepository, ImportService $importService)
    {
        $this->personRepository = $personRepository;
        $this->importService = $importService;
    }

    public function index()
    {
        return view('welcome', ['persons' => Person::all()]);
    }

    public function store(PersonStoreRequest $personStoreRequest)
    {
        $this->personRepository->store((array)$personStoreRequest);
    }

    public function upload(PersonsUploadRequest $personsUploadRequest): RedirectResponse|null
    {
        if ($personsUploadRequest->validated()) {
            $csvContents = file($personsUploadRequest->file('file_upload')->getRealPath());
            $allPersons = $this->importService->processImport($csvContents);
            $this->storePersons($allPersons);
            return redirect()->route('home', ['persons' => Person::all(), 'success' => 'Persons imported successfully!']);
        }
    }

    public function delete()
    {
        $this->personRepository->deleteAll();
          return redirect()->route('home', ['persons' => Person::all(), 'success' => 'Persons deleted successfully!']);
    }

    private function storePersons(array $allPersons){
        foreach ($allPersons as $persons) {
            foreach ($persons as $person) {
                $this->personRepository->store($person);
            }
        }
    }
}
