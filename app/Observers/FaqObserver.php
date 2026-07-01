<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Cache;

class FaqObserver
{
    /**
     * Handle the Faq "saved" event.
     */
    public function saved(Faq $faq): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Faq "deleted" event.
     */
    public function deleted(Faq $faq): void
    {
        $this->clearCache();
    }

    /**
     * Clear associated cache keys.
     */
    private function clearCache(): void
    {
        Cache::forget('faqs_list');
    }
}
