<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMatrixRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        session(
            [
                'matrixName' => $this->input('matrixName') ?? "",
                'factorNames' => json_encode($this->input('factorNames'), JSON_UNESCAPED_UNICODE),
                'factorWeights' => json_encode($this->input('factorWeights'), JSON_UNESCAPED_UNICODE),
                'factorLength' => is_null($this->input('factorNames')) ? 0 : count($this->input('factorNames')),
                "hasFailed" => 1
            ]
        );

        return [
            'matrixName' => 'required|max:50',
            'factorNames' => 'required',
            'factorWeights' => 'required',
            'factorNames' => 'required',
            'factorWeights' => 'required',
        ];
    }

    // matrixの名前を取得
    public function getMatrix(): string
    {
        return $this->input('matrixName');
    }
    
    // user_idを取得
    public function getUserId(): int
    {
        return $this->user()->id;
    }

    // factorのフィールドを取得
    public function getFactors(): array
    {
        return [
            'factorNames' => $this->input('factorNames'),
            'factorWeights' => $this->input('factorWeights'),
        ];
    }
}
