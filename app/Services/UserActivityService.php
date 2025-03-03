<?php

namespace App\Services;

use App\Models\UserActivity;

class UserActivityService
{
  public function storeUserActivity(
    int $userId,
    int $mangaId,
    int $chapterId
  ): ?UserActivity {
    if (!UserActivity::where([
      'user_id' => $userId,
      'manga_id' => $mangaId,
      'chapter_id' => $chapterId,
    ])->exists()) {
      return UserActivity::create([
        'user_id' => $userId,
        'manga_id' => $mangaId,
        'chapter_id' => $chapterId,
      ]);
    }

    return null;
  }
}
