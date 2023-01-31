<?php

namespace App\Http\Requests\CrTable;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Factory as ValidationFactory;

/**
 * Class StoreCrTablesRequest.
 */
class StoreCrTablesRequest extends FormRequest
{
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'check_slug_name',
            function ($attribute, $value, $parameters) {
                $pattern = '/^[a-z_]+$/';
                preg_match_all($pattern, $value, $result);
                if($result[0] == false){
                    return false;
                }
                else if($result[0][0] != $value){
                    return false;
                }
                return true;
            },
            'Slug has characters that are not allowed'
        );

    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasAllAccess();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $res = [
            'slug' => ['required', 'check_slug_name'],
            'name' => ['required'],
            'form_type' => ['required', Rule::in([0, 5])],
            'export_type' => ['required', Rule::in([0, 5])],
            'excel_file' => ['required', 'mimes:xlsx']
        ];

        return $res;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'slug.unique' => __('The Slug has already been taken.'),
            'name.unique' => __('The Name has already been taken.'),
            // 'permissions.*.exists' => __('One or more permissions were not found or are not allowed to be associated with this role type.'),
        ];
    }
}
