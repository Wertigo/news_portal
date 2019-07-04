<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Helper\PostHelper;

class PostHelperTest extends TestCase
{
    public function testGetStatuses()
    {
        $defaultStatuses = PostHelper::getStatuses();
        $statusesExceptDrafts = PostHelper::getStatuses(true);

        // we have different statuses
        $this->assertNotEmpty($defaultStatuses);

        // one of them - draft, and we have others
        $this->assertNotEmpty($statusesExceptDrafts);

        // draft - only one status
        $this->assertEquals(count($defaultStatuses), count($statusesExceptDrafts) + 1);
    }
}
