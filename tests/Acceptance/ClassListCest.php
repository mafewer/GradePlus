<?php

use Tests\Support\AcceptanceTester;

class ClassListCest
{
    public function classList(AcceptanceTester $I)
    {
        // Step 1: Reset the database
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 1,
            'error' => 0
        ]);

        // Step 2: Fetch students for course CS 3301
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-students.php', [
            'authorize' => 'gradeplus',
            'course_code' => 'CS 3301'
        ]);

        // Check the response is valid JSON
        $I->seeResponseIsJson();

        // Ensure two students are returned
        $I->seeResponseContainsJson([
            'success' => 1,
            'error' => 0,
            'illegal' => 0,
            'data' => [
                [
                    'profilePicture' => "",  // Adjust if necessary
                    'dname' => 'mafewer',
                    'username' => 'mafewer'
                ],
                [
                    'profilePicture' => "",  // Adjust if necessary
                    'dname' => 'ddolomount',
                    'username' => 'ddolomount'
                ]
            ],
            'message' => 'Students retrieved successfully.'
        ]);

        // Confirm 'success' is in the response source
        $I->seeInSource('success');

        // This is is to ensure that we get an error when there is a non-existant class entered.
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-students.php', [
            'authorize' => 'gradeplus',
            'course_code' => 'ECE 3301'
        ]);

        $course_code = 'ECE 3301';

        // Check the response is valid JSON
        $I->seeResponseIsJson();

        // Ensure two students are returned
        $I->seeResponseContainsJson([
            'success' => 0,
            'error' => 1,
            'illegal' => 0,
            'data' => [],
            'message' => "Course code '$course_code' does not exist."
        ]);

    }
}
