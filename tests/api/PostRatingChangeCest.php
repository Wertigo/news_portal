<?php namespace App\Tests;

use App\Entity\Post;
use App\Tests\ApiTester;
use Codeception\Scenario;

class PostRatingChangeCest
{
    /**
     * @var Post
     */
    private $post;

    /**
     * @param \App\Tests\ApiTester $I
     * @throws \Codeception\Exception\ModuleException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function _before(ApiTester $I)
    {
        $this->post = $I->createTestPost();
    }

    /**
     * @param \App\Tests\ApiTester $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function _after(ApiTester $I)
    {
        $I->deleteTestPost();
    }

    /**
     * @param \App\Tests\ApiTester $I
     * @param Scenario $scenario
     */
    public function postRatingUpTest(ApiTester $I, Scenario $scenario)
    {
        $oldRating = $this->post->getRating();

        $I->sendAjaxPostRequest("/api/post/rating-up/{$this->post->getId()}");
        $I->seeResponseIsJson();

        if (!$I->seeThatResponseContainNewRating($I->grabResponse(), $oldRating, true)) {
            $scenario->incomplete('Incorrect json in answer');
        }
    }

    /**
     * @param \App\Tests\ApiTester $I
     * @param Scenario $scenario
     */
    public function postRatingDownTest(ApiTester $I, Scenario $scenario)
    {
        $oldRating = $this->post->getRating();

        $I->sendAjaxPostRequest("/api/post/rating-down/{$this->post->getId()}");
        $I->seeResponseIsJson();

        if (!$I->seeThatResponseContainNewRating($I->grabResponse(), $oldRating, false)) {
            $scenario->incomplete('Incorrect json in answer');
        }
    }
}
