<?php

use Tests\Support\AcceptanceTester;

class CustomSqlCest {
    public function executeCustomSql(AcceptanceTester $I) {
        // Set HTTP headers
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send a valid custom SQL command with proper authorization
        $I->sendPost('/services/custom-sql.php', [
            'authorize' => 'gradeplus',
            'command' => "INSERT INTO courses(course_name, course_banner, instructor_name, course_code, instructor_dname) 
                            VALUES ('Communication Networks', 'banner.png', 'Daniel Dolomount', 'ECE 6610', 'Daniel Dolomount')"
        ]);

        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(['success' => 1]);

        $I->seeInSource('success');
    }

    public function missingSqlCommand(AcceptanceTester $I) {
        // Set HTTP headers
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send a request with no 'command' field
        $I->sendPost('/services/custom-sql.php', [
            'authorize' => 'gradeplus'
        ]);

        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(['success' => 0]);

        $I->seeInSource('success');
    }

    public function invalidAuthorization(AcceptanceTester $I) {
        // Set HTTP headers
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send a request with an incorrect 'authorize' value
        $I->sendPost('/services/custom-sql.php', [
            'authorize' => 'invalid',
            'command' => "UPDATE users SET active = 1 WHERE id = 1"
        ]);

        $I->seeInCurrentUrl('illegal.php');
    }
}
