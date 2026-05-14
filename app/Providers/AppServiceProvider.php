<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!app()->environment('local')) {
            \URL::forceScheme('https');
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => Vite::useBuildDirectory('build')
                ->withEntryPoints([
                    'resources/css/app.css',
                    'resources/js/app.js',
                ])
                ->toHtml(),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => Blade::render(
                "@livewire('car-model.delete-modal')"
            ),
        );
    }
}