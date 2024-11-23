<?php

use Tests\Support\AcceptanceTester;

class UpdateUsernameCest
{
    public function updateUsername(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        //Set session variable using helper service
        $I->sendPost('/services/set-session.php', [
            'key' => 'username',
            'value' => 'oldName'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        //Call to attempt to update to taken name
        $I->sendPost('/services/update-username.php', [
            'authorize' => 'gradeplus',
            'newname' => 'newName'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1,"error" => 0,"taken" => 0]);
    }

    public function cannotUpdateWhenNotSignedIn(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/update-username.php', [
            'authorize' => 'gradeplus',
            'newname' => 'newUsername'
        ]);
        // Expect to be redirected to login.php
        $I->seeInCurrentUrl("login.php");
    }

    public function cannotUseTakenName(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        //Set session variable using helper service
        $I->sendPost('/services/set-session.php', [
            'key' => 'username',
            'value' => 'oldName'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        //Call to get inital login table values
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);

        //Call to attempt to update to taken name
        $I->sendPost('/services/update-username.php', [
            'authorize' => 'gradeplus',
            'newname' => 'demo'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 0,"error" => 0,"taken" => 1]);
    }
}
