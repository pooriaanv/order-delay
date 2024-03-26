<?php

namespace App\Repositories\Order;

use App\Models\DelayReport;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class DelayReportRepository extends BaseEloquentRepository implements DelayReportRepositoryInterface
{
    function getModel(): Model
    {
        return new DelayReport();
    }

    /**
     * @param $orderId
     *
     * @return bool
     */
    public function hasPendingReport($orderId): bool
    {
        return $this->model->newQuery()
            ->where('order_id', '=', $orderId)
            ->where('status', '=', DelayReport::STATUS_PENDING)
            ->exists();
    }

    /**
     * @param $agentId
     *
     * @return bool
     */
    public function agentHasPendingReport($agentId): bool
    {
        return $this->model->newQuery()
            ->where('agent_id', '=', $agentId)
            ->where('status', '=', DelayReport::STATUS_PENDING)
            ->exists();
    }

    /**
     * @return DelayReport|null
     */
    public function getFirstAvailable(): ?DelayReport
    {
        return $this->model
            ->whereNull('agent_id')
            ->where('status', '=', DelayReport::STATUS_PENDING)
            ->oldest()
            ->first();
    }

    /**
     * @param DelayReport $report
     * @param             $agentId
     */
    public function assignToAgent(DelayReport $report, $agentId): void
    {
        $report->update(['agent_id' => $agentId]);
    }
}
