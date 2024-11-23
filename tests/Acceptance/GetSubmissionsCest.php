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
            'invite_code' => 'ABCDEF'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1, "error" => 0]);
    }

    public function getIndividualSubmission(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        //Call to return one submission
        $I->sendPost('/services/get-individual-submission.php', [
            'authorize' => 'gradeplus',
            'invite_code' => 'ABCDEF',
            'student_name' => 'student',
            'assignment_id' => '2'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1, "error" => 0]);
    }
}
