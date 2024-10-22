<?php

use Tests\Support\AcceptanceTester;

class DeleteCourseCest
{
    public function DeleteCourse(AcceptanceTester $I)
    {
        //Remove the course from the database
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/delete-course.php', [
            'authorize' => 'gradeplus',
            'invitecode' => 'ABCDEF'
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
