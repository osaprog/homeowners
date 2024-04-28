<?php
namespace App\Http\Requests\Persons;

use Illuminate\Foundation\Http\FormRequest;
class PersonStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'title' => ['required', 'max:50'],
            'first_name' => ['nullable', 'max:50'],
            'initial' => ['nullable'],
            'last_name' => ['required', 'max:50'],
        ];
    }
}
