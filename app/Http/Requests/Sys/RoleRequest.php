<?php
/** 
 * 角色表单验证类
 * 
 *     命令：php artisan make:request Sys/RoleRequest
 *     
 *     调用控制器使用方式：
 *         function store(RoleRequest $request) {
 *              if ($error = $request->validated()) {
                    return $error;
                }
 *         }
 *         
 */
namespace App\Http\Requests\Sys;

// use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class RoleRequest extends FormRequest implements ValidatesWhenResolved
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
            'name' => 'required|string|max:20',
            'remark' => 'string|max:100'
        ];
    }

    /** 
     * 自定义错误返回信息
     * 
     * @return    [type]      [description]
     */
    public function messages()
    {
        return [
            'name.required' => '请输入角色名称,最长20个字'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = $validator->messages()->first();
        throw new HttpResponseException(response()->json(['message'=>$error,'code'=>'500','data'=>$error], 500));
    }
}
