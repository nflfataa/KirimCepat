<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'zones' => Zone::query()->orderBy('id')->get(),
        ]);
    }
}
