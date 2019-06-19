<?php

namespace App\Helper;

use App\Entity\Post;

class PostHelper
{
    /**
     * @param bool $exceptDrafts
     * @return array
     */
    public static function getStatuses($exceptDrafts = false)
    {
        if ($exceptDrafts) {
            return [
                Post::STATUS_TEXT_PUBLISHED => Post::STATUS_PUBLISHED,
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