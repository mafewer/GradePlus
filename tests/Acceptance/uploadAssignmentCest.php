<?php

use Tests\Support\AcceptanceTester;

class AssignmentUploadCest
{
    public function uploadAssignment(AcceptanceTester $I)
    {
        $invite_code = "ABCDEF";
        $course_code = "ECE 6400";
        $assignment_name = "A3";
        $description = "Description for A3";
        $due_date = "2024-10-30";
        $instructor = "rabbas"; // Valid instructor name

        // Prepare POST data
        $postData = [
            "authorize" => "gradeplus",
            "invite_code" => $invite_code,
            "course_code" => $course_code,
            "assignment_name" => $assignment_name,
            "description" => $description,
            "due_date" => $due_date,
            "instructor" => $instructor,
        ];

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost("/services/add_assignment.php", $postData);
        // Check if the response is JSON
        $I->seeResponseIsJson();
        // Check if the response contains the expected JSON
        $I->seeResponseContainsJson([
            "success" => 1,
            "error" => 0,
            "invalid_course" => 0
        ]);
    }

    public function invalidCourseTest(AcceptanceTester $I)
    {
        $invite_code = "0000000"; // Invalid course code
        $course_code = "ECE 6400";
        $assignment_name = "A3";
        $description = "Description for A3";
        $due_date = "2024-10-30";
        $instructor = "rabbas";

        $postData = [
            "authorize" => "gradeplus",
            "invite_code" => $invite_code,
            "course_code" => $course_code,
            "assignment_name" => $assignment_name,
            "description" => $description,
            "due_date" => $due_date,
            "instructor" => $instructor,
        ];

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost("/services/add_assignment.php", $postData);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0,
            "error" => 0,
            "invalid_course" => 1
        ]);
    }

    public function invalidInstructorTest(AcceptanceTester $I)
    {
        $invite_code = "ABCDEF";
        $course_code = "ECE 6400";
        $assignment_name = "A3";
        $description = "Description for A3";
        $due_date = "2024-10-30";
        $instructor = "Freddy Fazbear"; // Invalid instructor name

        $postData = [
            "authorize" => "gradeplus",
            "invite_code" => $invite_code,
            "course_code" => $course_code,
            "assignment_name" => $assignment_name,
            "description" => $description,
            "due_date" => $due_date,
            "instructor" => $instructor,
        ];

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost("/services/add_assignment.php", $postData);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 0,
            "error" => 0,
            "invalid_course" => 1
        ]);
    }

    public function missingAuthorizationTest(AcceptanceTester $I)
    {
        $invite_code = "ABCDEF";
        $course_code = "ECE 6400";
        $assignment_name = "A3";
        $description = "Description for A3";
        $due_date = "2024-10-30";
        $instructor = "rabbas";

        $postData = [
            "invite_code" => $invite_code,
            "course_code" => $course_code,
            "assignment_name" => $assignment_name,
            "description" => $description,
            "due_date" => $due_date,
            "instructor" => $instructor,
        ];

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost("/services/add_assignment.php", $postData);

        // Expected to redirect due to missing authorization
        $I->seeInCurrentUrl("illegal.php");
    }
}
