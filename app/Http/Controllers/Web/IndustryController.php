<?php

namespace App\Http\Controllers\Web;

use App\Domain\Infrastructure\SDE\Models\Blueprint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndustryController
{
    public function index()
    {
        $bps = Blueprint::query()->where('_key', 23912)->with('type')->first();

        // $bps->activities = $this->materialInfo($bps);

        return $bps->toArray();
        // dd($bps);
        // return Inertia::render('Industry', ['blueprints' => $bps]);
    }
}
