<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

interface PersonRepositoryInterface{
    public function store(array $request): bool;
    public function get(): Collection;
}
