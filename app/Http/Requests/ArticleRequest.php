<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            'main_filename' => 'required|file|mimes:jpg,jpeg,png,gif',
            'map_query' => 'required|max:50',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif',
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes()
    {
        return [
          'title' => 'お店の名前',
          'body' => '説明',
          'main_filename' => 'メインとなる写真',
          'map_query' => 'お店の場所',
          'files.*' => 'その他の写真',
          'tags' => 'タグ',
        ];
    }

    public function passedValidation()
    {
        $this->tags = collect(json_decode($this->tags))
          ->slice(0, 5)
          ->map(function ($requestTag) {
            return $requestTag->text;
          });
    }
}
