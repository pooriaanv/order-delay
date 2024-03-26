<?php

namespace App\Repositories\Order;

use App\Models\DelayReport;
use App\Repositories\BaseRepositoryInterface;

interface DelayReportRepositoryInterface extends BaseRepositoryInterface
{
    public function hasPendingReport($orderId): bool;

    public function agentHasPendingReport($agentId): bool;

    public function getFirstAvailable(): ?DelayReport;

    public function assignToAgent(DelayReport $report, $agentId): void;
}
