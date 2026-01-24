<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PublicLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $canonical = null,
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $ogType = null,
        public ?string $ogUrl = null,
        public ?string $ogImage = null,
        public ?string $twitterTitle = null,
        public ?string $twitterDescription = null,
        public ?string $twitterImage = null,
        public ?string $robots = null, // 追加：noindex用
        public ?string $jsonLd = null, // 追加：JSON-LDを埋め込む用（任意）
    ) {}

    public function render()
    {
        return view('components.public-layout');
    }
}
