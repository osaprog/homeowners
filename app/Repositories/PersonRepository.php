<?php

namespace App\Repositories;

use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Http\RedirectResponse;
use Throwable;

class PersonRepository implements PersonRepositoryInterface
{
    public function store(array $data): bool
    {
        DB::beginTransaction();

        try {
            Person::create([
                'title' => $data['title'],
                'firstname' =>  $data['firstname'] ?? null,
                'lastname' =>  $data['lastname'],
                'initial' =>  $data['initial'] ?? null,
            ]);

            DB::commit();
            return true;

        } catch (Throwable $e) {
            DB::rollback();
            return false;
        }
    }

    public function get(): Collection
    {
        return Person::all();
    }

    public function deleteAll(){
        return Person::truncate();
    }
}
