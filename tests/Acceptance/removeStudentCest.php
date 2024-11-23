<?php

use Tests\Support\AcceptanceTester;

class DeenrollmentCest
{
    public function successfulDeenrollment(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $postData = [
            'authorize' => 'gradeplus',
            'username' => 'asamanta',
            'invite_code' => 'ABCDEF',
        ];

        $I->sendPost('/services/remove-student.php', $postData);

        // Verify the response
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 1, 'error' => 0]);

    }

    public function deenrollNonExistingStudent(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        // Prepare test data for a student who is not enrolled
        $postData = [
            'authorize' => 'gradeplus',
            'username' => 'James Bond',
            'invite_code' => 'ABCDEF'
        ];

        $I->sendPost('/services/remove-student.php', $postData);

        // Verify the response
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 0, 'error' => 0]);
    }

    public function invalidAuthorization(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        // Prepare test data with invalid authorization
        $postData = [
            'authorize' => 'hacker',
            'username' => 'student',
            'invite_code' => 'ABCDEF'
        ];

        // Send the POST request
        $I->sendPost('/services/remove-student.php', $postData);

        // Check for redirection to illegal.php
        $I->seeInCurrentUrl('illegal.php');
    }

}
