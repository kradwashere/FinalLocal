<?php

namespace App\Http\Requests;

use Hashids\Hashids;
use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $hashids = new Hashids('krad',10);
        if($this->scholar_id){
            $scholar_id = $hashids->decode($this->scholar_id);
            $scholar_id = $scholar_id[0];
        }else{
            $scholar_id = null;
        }

        if($this->update){
            return [
                'scholar_id' => 'sometimes|required',
                'level_id' => 'sometimes|required|integer',
                'lists' => 'sometimes|required|min:1',
                'files.*' => 'sometimes|required|mimes:pdf,docx|max:2000'
            ];
        }else{
            return [
                'academic_year' => 'sometimes|required|string',
                'level_id' => 'sometimes|required|integer',
                'semester_id' => 'sometimes|required|integer|unique:scholar_enrollments,semester_id,NULL,'.$this->id.',level_id,'.$this->level_id.',scholar_id,'.$scholar_id,
                'scholar_id' => 'sometimes|required',
                'subcourse' => 'sometimes|required|integer',
                'lists' => 'sometimes|required|min:1',
                'is_locked' => 'sometimes|required|integer',
                'files.*' => 'sometimes|required|mimes:pdf,docx|max:2000'
            ];
        }
    }

    public function messages()
    {
        $message = [
            'semester_id.unique' => 'Please check semester, already enrolled previously.',
        ];
        return $message;
    }
}
