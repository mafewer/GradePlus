<?php

use Tests\Support\AcceptanceTester;

class ClassListCest {

    // Helper function to reset the database
    private function resetDatabase(AcceptanceTester $I) {
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

    // Test for fetching students of a valid course
    public function fetchValidCourseStudents(AcceptanceTester $I) {
        $this->resetDatabase($I);  // Ensure a clean slate

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-students.php', [
            'authorize'   => 'gradeplus',
            'invite_code' => 'GHIJK'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 1,
            'error'   => 0,
            'illegal' => 0,
            'data'    => [
                [
                    'profile_picture' => null,
                    'dname'          => 'Daniel',
                    'username'       => 'ddolomount',
                    'email'         => 'ddolomount@mun.ca'
                ],
                [
                    'profile_picture' => null,
                    'dname'          => 'Matthew',
                    'username'       => 'mafewer',
                    'email' => 'mafewer@mun.ca'
                ]
            ],
            'message' => 'Students retrieved successfully.'
        ]);
    }

    // Test for handling a non-existent course code
    public function fetchNonExistentCourse(AcceptanceTester $I) {
        $this->resetDatabase($I);

        $course_code = 'CS 3301';

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/services/get-students.php', [
            'authorize'   => 'gradeplus',
            'invite_code' => $course_code
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => 0,
            'error'   => 1,
            'illegal' => 0,
            'data'    => [],
            'message' => "Course code '$course_code' does not exist."
        ]);
    }
}
