<?php

use Tests\Support\AcceptanceTester;

class DeenrollStudentCest
{
    public function DeenrollStudent(AcceptanceTester $I)
    {
        //Remove the Student from Course
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/de-enroll-user.php', [
            'authorize' => 'gradeplus',
            'invitecode' => 'ABCDEF',
            'username' => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 1]);

        $I->seeInSource('success');

        //Reset Again
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 1, 'error' => 0]);
    }
}
