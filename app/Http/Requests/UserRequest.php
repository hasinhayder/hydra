<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        if ($this->isMethod('PUT')) {
            var_dump($this->isMethod('PUT'));
            return auth('sanctum')->check();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        if ($this->isMethod('PUT')) {
            return [];
        }

        if ($this->path() == 'api/login') {
            return [
                'email' => 'required|email',
                'password' => 'required',
            ];
        }

        return [
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'nullable|string',
        ];
    }
}
