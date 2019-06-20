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

        $this->assertNotEmpty($defaultStatuses);
        $this->assertNotEmpty($statusesExceptDrafts);
        $this->assertEquals(count($defaultStatuses), count($statusesExceptDrafts) + 1);
    }
}
