<?php namespace App\Http\Requests;

use Auth;
use Common\Core\BaseFormRequest;
use Validator;

class ModifyPlaylist extends BaseFormRequest
{

    public function messages() {
        return [
            'unique_name' => 'You have already created a playlist with this name.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Validator::extend('uniqueName', function ($attribute, $value) {
            $playlist = $this->route('playlist');
            return !$playlist || $playlist->owner_id === Auth::id();
        });

        $rules =  [
            'name' => 'string|min:3|max:255|unique_name',
            'description' => 'min:20|max:170|nullable',
            'public' => 'boolean',
            'collaborative' => 'boolean',
        ];

        if ($this->method() === 'POST') {
            $rules['name'] = 'required|' . $rules['name'];
        }

        return $rules;
    }
}
