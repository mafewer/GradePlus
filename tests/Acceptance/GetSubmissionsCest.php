<?php

use Tests\Support\AcceptanceTester;

class GetSubmissionsCest
{
    public function getAllSubmissions(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        $I->sendPost('/services/reset_demo.php', [
            'authorize' => 'gradeplus'
        ]);

        //Call to return all submissions
        $I->sendPost('/services/get-all-submissions.php', [
            'authorize' => 'gradeplus',
            'course_code' => 'ECE 6400'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1, "error" => 0, "data" => [['assignment_id' => '0', 'assignment_name' => 'A1', 'username' => 'demo', 'submitted_flag' => '1'], ['assignment_id' => '2', 'assignment_name' => 'A2', 'submitted_pdf' => null, 'username' => 'student', 'submitted_flag' => '1']]]);
    }

    public function getIndividualSubmission(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        //Call to return one submission
        $I->sendPost('/services/get-individual-submission.php', [
            'authorize' => 'gradeplus',
            'course_code' => 'ECE 6400',
            'student_name' => 'student',
            'assignment_id' => '2'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1, "error" => 0, "data" => [['assignment_id' => '2', 'course_code' => 'ECE 6400', 'assignment_name' => 'A2', 'username' => 'student', 'grade' => '0', 'max_grade' => '5', 'feedback' => '', 'submitted_pdf' => null, 'submitted_flag' => '1', 'submitted_date' => null]]]);
    }
}
