<?php

namespace App\Helper;

use App\Entity\Post;

class PostHelper
{
    /**
     * @param bool $exceptPublished
     * @return array
     */
    public static function getStatuses($exceptPublished = false)
    {
        if ($exceptPublished) {
            return [
                Post::STATUS_TEXT_DRAFT => Post::STATUS_DRAFT,
                Post::STATUS_TEXT_MODERATION_CHECK => Post::STATUS_MODERATION_CHECK,
                Post::STATUS_TEXT_DECLINED => Post::STATUS_DECLINED,
            ];
        }

        return [
            Post::STATUS_TEXT_DRAFT => Post::STATUS_DRAFT,
            Post::STATUS_TEXT_MODERATION_CHECK => Post::STATUS_MODERATION_CHECK,
            Post::STATUS_TEXT_PUBLISHED => Post::STATUS_MODERATION_CHECK,
            Post::STATUS_TEXT_DECLINED => Post::STATUS_DECLINED,
        ];
    }
}