<?php

use Tests\Support\AcceptanceTester;

class UpdateEmailCest
{
    public function updateEmail(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        //Set session variable using helper service
        $I->sendPost('/services/set-session.php', [
            'key' => 'username',
            'value' => 'user'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        $I->sendPost('/services/update-email.php', [
            'authorize' => 'gradeplus',
            'newemail' => 'email@email.com'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1,"error" => 0]);
    }

    public function cannotUpdateWhenNotSignedIn(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/update-email.php', [
            'authorize' => 'gradeplus',
            'newname' => 'newUsername'
        ]);
        // Expect to be redirected to login.php
        $I->seeInCurrentUrl("login.php");
    }

    public function cannotUseTakenEmail(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        //Set session variable using helper service
        $I->sendPost('/services/set-session.php', [
            'key' => 'username',
            'value' => 'name'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        //Call to get inital login table values
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);

        $I->sendPost('/services/update-email.php', [
            'authorize' => 'gradeplus',
            'newemail' => 'demo@gradeplus.com'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 0,"error" => 0,"taken" => 1]);
    }
}
