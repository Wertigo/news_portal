<?php
namespace App\Tests;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * @param string $response
     * @param int $rating
     * @param bool $increment
     * @return bool
     */
    public function seeThatResponseContainNewRating($response, $rating, $increment = true): bool
    {
        $response = json_decode($response, true);
        $newRating = $response['rating'] ?? null;

        if (null === $newRating) {
            return false;
        }

        if ($increment) {
            $rating += 1;
        } else {
            $rating -= 1;
        }

        return $rating === $newRating;
    }
}
