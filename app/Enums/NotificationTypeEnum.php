<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case commentReply = 'commentReply';
    case communityCommentReply = 'communityCommentReply';
}
