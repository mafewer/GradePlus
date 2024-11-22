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
            'course_code' => 'ECE 6400'
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

        $course_code = 'ECE 3301';

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-assignments.php', [
            'authorize'   => 'gradeplus',
            'course_code' => $course_code
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 0,
            'error'   => 1,
            'illegal' => 0,
            'message' => "Course code '$course_code' does not exist."
        ]);
    }
}
