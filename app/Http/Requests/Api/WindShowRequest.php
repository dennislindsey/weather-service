<?php
/**
 * User: dennis
 * Date: 8/16/18
 */

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WindShowRequest extends FormRequest
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
            'zipCode' => 'required|regex:/\b\d{5}\b/',
        ];
    }

    /**
     * Add parameters to be validated
     *
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        return array_merge(
            parent::all($keys),
            $this->route()->parameters()
        );
    }

    /**
     * Retrieve an input item from the request.
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function input($key = null, $default = null)
    {
        return parent::input($key, $default) ?? data_get($this->route()->parameters(), $key, $default);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'zipCode.required' => 'A zip code is required',
            'zipCode.regex'    => 'Must provide a valid zip code',
        ];
    }
}
