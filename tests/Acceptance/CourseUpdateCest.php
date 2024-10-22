<?php

use Tests\Support\AcceptanceTester;

class CourseUpdateCest
{
    public function CourseUpdate(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/course-update.php', [
            'authorize' => 'gradeplus',
            'coursename' => 'Communication Principles',
            'coursecode' => 'ECE6600',
            'invitecode' => 'ABCDEF',
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => 1]);

        $I->seeInSource('success');
    }
}
