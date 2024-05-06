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
    public function store(array $request): bool
    {
        DB::beginTransaction();

        try {
            Person::create([
                'title' => $request['title'],
                'firstname' =>  $request['firstname'] ?? null,
                'lastname' =>  $request['lastname'],
                'initial' =>  $request['initial'] ?? null,
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
