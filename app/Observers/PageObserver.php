<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    /**
     * Handle the Page "saved" event.
     */
    public function saved(Page $page): void
    {
        $this->clearCache($page);
    }

    /**
     * Handle the Page "deleted" event.
     */
    public function deleted(Page $page): void
    {
        $this->clearCache($page);
    }

    /**
     * Clear associated cache keys.
     */
    private function clearCache(Page $page): void
    {
        Cache::forget('pages_active');
        Cache::forget('page_detail_' . $page->slug);
        
        // Also forget the old slug if it was modified
        if ($page->isDirty('slug')) {
            Cache::forget('page_detail_' . $page->getOriginal('slug'));
        }
    }
}
