<?php

use Tests\Support\AcceptanceTester;

class RegisterUserCest {

    // Helper function to reset the database before running tests
    private function resetDatabase(AcceptanceTester $I) {
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);
        $I->seeResponseContainsJson(["success" => 1]);  // Ensure the reset worked
    }

    // Test for registering a unique user
    public function RegisterUniqueUser(AcceptanceTester $I) {
        $this->resetDatabase($I);  // Reset the database

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username' => 'mafewer',
            'dname'    => 'Matthew Fewer',
            'email'    => 'mafewer@mun.ca',
            'password' => 'testPassword',
            'usertype' => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 1, 
            "exists"  => 0, 
            "error"   => 0, 
            "empty"   => 0, 
            "invalid_email" => 0
        ]);
    }

    // Test for trying to register a duplicate user
    public function RegisterRepeatUser(AcceptanceTester $I) {
        $this->resetDatabase($I);

        // Register the first user
        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => 'mafewer',
            'dname'     => 'Matthew Fewer',
            'email'     => 'mafewer@mun.ca',
            'password'  => 'testPassword',
            'usertype'  => 'student'
        ]);

        // Attempt to register the same user again
        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => 'mafewer',
            'dname'     => 'Marcus Fewer',
            'email'     => 'marcus@mun.ca',
            'password'  => 'testPassword_1',
            'usertype'  => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0, 
            "exists"  => 1, 
            "error"   => 0, 
            "empty"   => 0, 
            "invalid_email" => 0
        ]);
    }

    // Test for registering a user with an empty username
    public function RegisterUserEmptyUsername(AcceptanceTester $I) {
        $this->resetDatabase($I);

        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => '',
            'dname'     => 'Marcus Fewer',
            'email'     => 'marcus@mun.ca',
            'password'  => 'testPassword_1',
            'usertype'  => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0, 
            "exists"  => 0, 
            "error"   => 0, 
            "empty"   => 1, 
            "invalid_email" => 0
        ]);
    }

    // Test for trying to register with an already-used username
    public function RegisterRepeatUsername(AcceptanceTester $I) {
        $this->resetDatabase($I);

        // Register the user initially
        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => 'mafewer',
            'dname'     => 'Matthew Fewer',
            'email'     => 'mafewer@mun.ca',
            'password'  => 'testPassword',
            'usertype'  => 'student'
        ]);

        // Attempt to register with the same username but different email
        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => 'mafewer',
            'dname'     => 'Marcus Fewer',
            'email'     => 'marcus@mun.ca',
            'password'  => 'testPassword_1',
            'usertype'  => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0, 
            "exists"  => 1, 
            "error"   => 0, 
            "empty"   => 0, 
            "invalid_email" => 0
        ]);
    }

    // Test for invalid email address (missing @)
    public function RegisterBadEmail(AcceptanceTester $I) {
        $this->resetDatabase($I);

        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => 'marcus',
            'dname'     => 'Marcus Fewer',
            'email'     => 'marcusmun.ca',
            'password'  => 'testPassword_1',
            'usertype'  => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0, 
            "exists"  => 0, 
            "error"   => 0, 
            "empty"   => 0, 
            "invalid_email" => 1
        ]);
    }

    // Test for invalid email address (no TLD)
    public function RegisterBadEmail2(AcceptanceTester $I) {
        $this->resetDatabase($I);

        $I->sendPost('/services/register.php', [
            'authorize' => 'gradeplus',
            'username'  => 'marcus',
            'dname'     => 'Marcus Fewer',
            'email'     => 'marcus@munca',
            'password'  => 'testPassword_1',
            'usertype'  => 'student'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0, 
            "exists"  => 0, 
            "error"   => 0, 
            "empty"   => 0, 
            "invalid_email" => 1
        ]);
    }
}
