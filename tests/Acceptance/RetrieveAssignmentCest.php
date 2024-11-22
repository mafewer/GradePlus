<?php

use Tests\Support\AcceptanceTester;

class GetAssignmentCest
{
    public function getAssignmentById(AcceptanceTester $I)
    {
        // Set the request headers
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send a POST request to the get-assignment endpoint with a valid assignment_id
        $I->sendPost('/services/get-individual-assignment.php', [
            'authorize' => 'gradeplus',
            'assignment_id' => 99999 // Use a valid assignment_id from your database
        ]);

        // Verify the response is in JSON format
        $I->seeResponseIsJson();

        // Ensure the response indicates success
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeResponseContainsJson(['error' => 1]);

        // Optionally verify that the 'illegal' flag is 0
        $I->seeResponseContainsJson(['illegal' => 0]);
    }
}
