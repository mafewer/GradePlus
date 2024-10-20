<?php
use Tests\Support\AcceptanceTester;

class DeenrollmentCest
{

    public function successfulDeenrollment(AcceptanceTester $I)
    {
        $postData = [
            'authorize' => 'gradeplus',
            'studentname' => 'student',
            'coursecode' => 'ECE 6400'
        ];

        $I->sendPost('/services/remove-student.php', $postData);

        // Verify the response
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 1, 'error' => 0]);

    }

    public function deenrollNonExistingStudent(AcceptanceTester $I)
    {
        // Prepare test data for a student who is not enrolled
        $postData = [
            'authorize' => 'gradeplus',
            'studentname' => 'James Bond',
            'coursecode' => 'ECE 6400'
        ];

        $I->sendPost('/services/remove-student.php', $postData);

        // Verify the response
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 0, 'error' => 0]);
    }

    public function invalidAuthorization(AcceptanceTester $I)
    {
        // Prepare test data with invalid authorization
        $postData = [
            'authorize' => 'hacker',
            'studentname' => 'student',
            'coursecode' => 'ECE 6400'
        ];

        // Send the POST request
        $I->sendPost('/services/remove-student.php', $postData);

        // Check for redirection to illegal.php
        $I->seeInCurrentUrl('illegal.php');
    }

}
