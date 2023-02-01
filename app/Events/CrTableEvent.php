<?php

namespace App\Events;

use App\Models\CrTable;
use Illuminate\Queue\SerializesModels;

/**
 * Class CrTableEvent.
 */
class CrTableEvent
{
    use SerializesModels;

    /**
     * @var
     */
    public $crTable;

    /**
     * @param $crTable
     */
    public function __construct(CrTable $crTable)
    {
        $this->crTable = $crTable;
    }
}
