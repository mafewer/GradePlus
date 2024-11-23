<?php

use Tests\Support\AcceptanceTester;

class DeleteUserCest
{
    public function deleteUser(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        //Set session variable using helper service
        $I->sendPost('/services/set-session.php', [
            'key' => 'username',
            'value' => 'demo'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);

        $I->sendPost('/services/delete-user.php', [
            'authorize' => 'gradeplus'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1,"error" => 0]);
    }

    public function cannotDeleteWhenNotSignedIn(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/delete-user.php', [
            'authorize' => 'gradeplus'
        ]);
        // Expect to be redirected to login.php
        $I->seeInCurrentUrl("login.php");
    }
}
