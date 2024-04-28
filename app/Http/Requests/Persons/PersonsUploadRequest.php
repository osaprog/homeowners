<?php
namespace App\Http\Requests\Persons;

use Illuminate\Foundation\Http\FormRequest;
class PersonsUploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return
            [
                'file_upload' => 'required|file|mimes:csv'
            ];
    }
}
