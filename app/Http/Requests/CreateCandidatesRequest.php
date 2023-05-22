<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCandidatesRequest extends FormRequest
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
                'candidate_names' => json_encode($this->input('candidate_names'), JSON_UNESCAPED_UNICODE),
                'candidate_points' => json_encode($this->input('candidate_points'), JSON_UNESCAPED_UNICODE),
                "group_ids" => $this->input('candidate_group_ids'),
                "hasFailed" => 1
            ]
        );
        return [];
    }

    /**
     * 候補情報を取得する
     */
    public function getCandidates(): array
    {
        return [
            "names" => $this->input("candidate_names"),
            "points" => $this->input("candidate_points"),
            "group_ids" => $this->input("candidate_group_ids"),
        ];
    }

    /**
     * matrix_idを取得する
     */
    public function getMatrixId(): int
    {
        return $this->input("matrixId");
    }
}
