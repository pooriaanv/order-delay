<?php

namespace App\Repositories\Order;

use App\Models\Trip;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class TripRepository extends BaseEloquentRepository implements TripRepositoryInterface
{

    function getModel(): Model
    {
        return new Trip();
    }
}
