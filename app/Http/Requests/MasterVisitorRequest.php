<?php

namespace App\Http\Requests;

use App\Helpers\PhoneHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MasterVisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone_number')) {
            $this->merge([
                'phone_number' => PhoneHelper::normalize_phone($this->phone_number),
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->id;
        return [
			'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('visitors', 'email')->ignore($id)
            ],
			'phone_number' => [
                'required',
                Rule::unique('visitors', 'phone_number')->ignore($id)
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function store(MasterVisitorRequest $request)
    {
        // The incoming request is valid...

        // Retrieve the validated input data...
        $validated = $request->validated();
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'image' => 'Profile',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => ':attribute harus diisi.',
            'email.required'    => ':attribute harus diisi.',
            'email.email'       => ':attribute tidak valid.',
            'email.unique'      => ':attribute sudah digunakan.',
            'phone_number.required' => ':attribute harus diisi.',
            'phone_number.unique' => ':attribute sudah digunakan.',
            'image.image'       => ':attribute harus berupa gambar.',
            'image.mimes'       => 'Format :attribute harus jpeg, png, jpg.',
            'image.max'         => 'Ukuran :attribute maksimal gambar 2MB.',
        ];
    }
}
