<?php

use Tests\Support\AcceptanceTester;

class UpdateDnameCest
{
    public function updateDname(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        //Set session variable using helper service
        $I->sendPost('/services/set-session.php', [
            'key' => 'username',
            'value' => 'name'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        $I->sendPost('/services/update-dname.php', [
            'authorize' => 'gradeplus',
            'newdname' => 'newName'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1,"error" => null]);
    }

    public function cannotUpdateWhenNotSignedIn(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/update-dname.php', [
            'authorize' => 'gradeplus',
            'newname' => 'newUsername'
        ]);
        // Expect to be redirected to login.php
        $I->seeInCurrentUrl("login.php");
    }
}
