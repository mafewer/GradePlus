<?php

use Tests\Support\AcceptanceTester;

class GetAssignmentsCest
{
    // Helper function to reset the database
    private function resetDatabase(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 1,
            'error'   => 0
        ]);
    }

    // Test for retrieving assignments from a valid course
    public function fetchValidCourseAssignments(AcceptanceTester $I)
    {
        $this->resetDatabase($I);  // Ensure a clean slate

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-assignments.php', [
            'authorize'   => 'gradeplus',
            'invite_code' => 'ABCDEF'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 1,
            'error'   => 0,
            'illegal' => 0,
            'message' => 'Assignments retrieved successfully.'
        ]);
    }

    // Test for handling a non-existent course code
    public function fetchNonExistentCourseAssignments(AcceptanceTester $I)
    {
        $this->resetDatabase($I);  // Ensure a clean state

        $invite_code = '0000000';

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-assignments.php', [
            'authorize'   => 'gradeplus',
            'invite_code' => $invite_code
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 0,
            'error'   => 1,
            'illegal' => 0,
            'message' => "Invite code '$invite_code' does not exist."
        ]);
    }
}
