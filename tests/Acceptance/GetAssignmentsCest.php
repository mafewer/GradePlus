<?php

use Tests\Support\AcceptanceTester;

class GetAssignmentsCest
{
    public function GetAssignments(AcceptanceTester $I)
    {
        // Ensure we are hitting the correct endpoint
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);
        
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-assignments.php', [
            'authorize' => 'gradeplus',
            'course_code' => 'ECE 6400'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 1, 'error' => 0, "illegal"=>0, 'data'=>[
            [
                'assignment_name' => 'A1',
                'assignment_file' => null,
                'description' => 'I am a description 1',
                'due_date' => null,
            ],
            [
                'assignment_name' => 'A2',
                'assignment_file' => null,
                'description' => 'I am a description 3',
                'due_date' => null,
            ]
        ], 'message' => "Assignments retrieved successfully."]);

        $course_code = 'ECE 3301';

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-assignments.php', [
            'authorize' => 'gradeplus',
            'course_code' => 'ECE 3301'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 0, 'error' => 1, "illegal"=>0, 'data'=>[], 'message' => "Course code '$course_code' does not exist."]);
    }
}