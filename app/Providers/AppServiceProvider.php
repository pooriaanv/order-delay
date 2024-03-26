<?php

namespace App\Providers;

use App\Events\ReportCreated;
use App\Listeners\PushToQueue;
use App\Repositories\Order\DelayReportRepository;
use App\Repositories\Order\DelayReportRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Order\TripRepository;
use App\Repositories\Order\TripRepositoryInterface;
use App\Repositories\Queue\QueueRepository;
use App\Repositories\Queue\QueueRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vendor\VendorRepository;
use App\Repositories\Vendor\VendorRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\SanctumAuthService;
use App\Services\CalculateDeliveryTime\CalculateDeliveryTimeService;
use App\Services\CalculateDeliveryTime\RemoteCalculateDeliveryTimeService;
use App\Services\Order\DbDelayQueue;
use App\Services\Order\DelayQueue;
use App\Services\Order\DelayQueueWithLock;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthService::class, SanctumAuthService::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(TripRepositoryInterface::class, TripRepository::class);
        $this->app->bind(DelayReportRepositoryInterface::class, DelayReportRepository::class);
        $this->app->bind(QueueRepositoryInterface::class, QueueRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);

        $this->app->bind(CalculateDeliveryTimeService::class, RemoteCalculateDeliveryTimeService::class);
        $this->app->bind(DelayQueue::class, function () {
            return new DelayQueueWithLock($this->app->make(DbDelayQueue::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
