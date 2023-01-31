<?php

namespace App\Events;

use App\Models\CrField;
use Illuminate\Queue\SerializesModels;

/**
 * Class CrFieldEvent.
 */
class CrFieldEvent
{
    use SerializesModels;

    /**
     * @var
     */
    public $crField;

    /**
     * @param $crField
     */
    public function __construct(CrField $crField)
    {
        $this->crField = $crField;
    }
}
