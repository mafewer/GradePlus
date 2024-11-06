<?php

use Tests\Support\AcceptanceTester;

class PinCourseCest {
    public function PinCourse(AcceptanceTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        $I->sendPost('/services/pin-course.php', [
            'authorize' => 'gradeplus',
            'username' => 'User',
            'invitecode' => 'INT123',
            'pinned' => 1
        ]);


        $I->seeResponseIsJson();

        // Asserting the JSON response contains success => 1
        $I->seeResponseContainsJson(['success' => 1]);

        // Ensuring the word 'success' is in the HTML source
        $I->seeInSource('success');
    }
}