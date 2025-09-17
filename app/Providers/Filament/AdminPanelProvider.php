<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile()
            ->databaseNotifications()
            ->navigationGroups([
                'User Management',
                'Inventory',
                'Business Partners',
                'Sales & Purchases',
                'Finance & Reports',
            ])
            ->resources([
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\RoleResource::class,
                \App\Filament\Resources\CategoryResource::class,
                \App\Filament\Resources\ProductResource::class,
                \App\Filament\Resources\CustomerResource::class,
                \App\Filament\Resources\SupplierResource::class,
                \App\Filament\Resources\SupplierProductResource::class,
                \App\Filament\Resources\ProductVariantResource::class,
                \App\Filament\Resources\OrderResource::class,
                \App\Filament\Resources\OrderItemResource::class,
                \App\Filament\Resources\PurchaseResource::class,
                \App\Filament\Resources\PurchaseItemResource::class,
                \App\Filament\Resources\SaleResource::class,
                \App\Filament\Resources\SaleItemResource::class,
                \App\Filament\Resources\WarehouseResource::class,
                \App\Filament\Resources\StockMovementResource::class,
                \App\Filament\Resources\ExpenseResource::class,
                \App\Filament\Resources\AuditLogResource::class,
            ])
            ->pages([
                \App\Filament\Pages\DashboardPage::class,
            ])
            ->widgets([
                \App\Filament\Widgets\SalesOverviewChart::class,
                \App\Filament\Widgets\PurchasesOverviewChart::class,
                \App\Filament\Widgets\ExpensesOverviewChart::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\LowStockProductsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make()
            ]);
    }
}
