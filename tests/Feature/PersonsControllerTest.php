<?php

namespace Tests\Feature;

use App\Http\Controllers\PersonsController;
use App\Repositories\PersonRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\Mock;
use App\Repositories\PersonRepositoryInterface;
use Tests\TestCase;
use Mockery;
class PersonsControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_combined_persons(): void
    {
        $mockPersonRepository = Mockery::mock(PersonRepository::class);
        $mockPersonRepository->shouldReceive('store')->andReturn('Person stored successfully'); // Define expected behavior
        $personsController = new PersonsController($mockPersonRepository);
        $csvContents =
            [
                'homeowner',
                'Mr and Mrs Smith',
            ];
        $result = $personsController->processImport($csvContents);
        $this->assertEquals( [
          ['title' => 'Mr', 'lastname' => 'Smith'],
          ['title' => 'Mrs', 'lastname' => 'Smith']
        ]
        , $result[1]);
    }

    public function test_single_persons(): void
    {
        $mockPersonRepository = Mockery::mock(PersonRepository::class);
        $mockPersonRepository->shouldReceive('store')->andReturn('Person stored successfully'); // Define expected behavior
        $personsController = new PersonsController($mockPersonRepository);
        $csvContents =
            [
                'homeowner',
                'Mr Right',
            ];
        $result = $personsController->processImport($csvContents);
        $this->assertEquals( [
                ['title' => 'Mr', 'lastname' => 'Right'],
            ]
            , $result[1]);
    }

    public function test_combined_persons_with_fullname_and_single(): void
    {
        $mockPersonRepository = Mockery::mock(PersonRepository::class);
        $mockPersonRepository->shouldReceive('store')->andReturn('Person stored successfully'); // Define expected behavior
        $personsController = new PersonsController($mockPersonRepository);
        $csvContents =
            [
                'homeowner',
                'Mr Right and Mrs Right',
                'Mr Perfect',
            ];
        $result = $personsController->processImport($csvContents);

        $this->assertEquals(
            [
                ['title' => 'Mr', 'lastname' => 'Right'],
                ['title' => 'Mrs', 'lastname' => 'Right'],
            ]
            , $result[1]);
        $this->assertEquals(
            [
                ['title' => 'Mr', 'lastname' => 'Perfect'],
            ]
            , $result[2]);


    }
}
