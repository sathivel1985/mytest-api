<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EntryRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'value' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'key',
        ];
    }

    /**
     * prepare the input data before validation
     */
    protected function prepareForValidation()
    {
        $request_data = collect($this->request->all())->map(function ($item, $key) {
            return ['name' => $key, 'value' => $item, 'updated_at' => Carbon::now()->timestamp];
        })
            ->first();
        if ($request_data) {
            $this->merge($request_data);
        }
    }
}
