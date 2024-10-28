<?php

use Tests\Support\AcceptanceTester;

class GetReviewByIdCest {
    public function GetReviewById(AcceptanceTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Reset the database to a known state
        $I->sendPost('/services/reset-demo.php', [
            'authorize' => 'gradeplus'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["success" => 1]);

        // Retrieve a specific review by ID (assuming review with ID 1 exists)
        $I->sendPost('/services/individual-review-retrieve.php', [
            'authorize' => 'gradeplus',
            'review_id' => 1
        ]);

        // Validate the response structure
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "success" => 1,
            "error" => 0,
            "review" => [
                "reviewer" => "daniel",
                "reviewee" => "emma",
                "assignment_id" => 4,
                "assignment_name" => "A1",
                "review" => NULL
            ]
        ]);

        $I->seeInSource('success');
    }
}
