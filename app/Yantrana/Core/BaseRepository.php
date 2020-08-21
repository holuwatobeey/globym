<?php

namespace App\Yantrana\Core;

use App\Yantrana\__Laraware\Core\CoreRepository;

class BaseRepository extends CoreRepository
{
    /**
     * Fetch pagination count.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function getPaginationCount()
    {
        $count = (int) getStoreSettings('pagination_count');
        
        return ($count)
                ? $count
                : config('__tech.loadItemType');
    }
}
