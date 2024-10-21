<?php

use Tests\Support\AcceptanceTester;

class RegisterUserCest {
    public function RegisterUser(AcceptanceTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        //Reset the database so I know exactly what is in it
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);

        //Test that I can make a new account that isn't in the system.
        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username' => 'mafewer',
            'dname' => 'Matthew Fewer',
            'email' => 'mafewer@mun.ca',
            'password' => 'testPassword',
            'usertype' => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1, "exists" => 0, "error" => 0, "empty" => 0, "invalid_email" => 0]);

        $I->seeInSource('success');

    }
}