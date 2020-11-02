<?php

// CryptoFilter.php

namespace App\Filters;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

//initialising possible crypto filters

class CryptoFilter extends AbstractFilter
{
    protected $filters = [
        'classification' => ClassificationFilter::class
    ];
}
