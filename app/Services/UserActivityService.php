<?php

namespace App\Services;

use App\Models\UserActivity;

class UserActivityService
{
  public function storeUserActivity(
    int $userId,
    int $mangaId,
    int $chapterId,
  ): UserActivity {
    return UserActivity::updateOrCreate(
      [
        'user_id' => $userId,
        'manga_id' => $mangaId,
      ],
      ['chapter_id' => $chapterId]
    );
  }
}
