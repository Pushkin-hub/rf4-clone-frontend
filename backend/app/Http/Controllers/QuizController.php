<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function getQuestionByNumber(Request $request, $categoryID, $questionNumber): JsonResource
    {
        $options = DB::select('CALL get_question_by_number(?, ?)', [$categoryID, $questionNumber]);

        $question = [
            'questionID' => $options[0]->question_id,
            'questionText' => $options[0]-> question_text,
            'option' =>array_map(fn ($option) => [
                'optionID' => $option->option_id,
                'optionText' => $option->option_text
            ], $options)
        ];

        return response()->json([
            'question' => $question
        ]);
    }
}
