<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class Module1Controller
{
    public function lesson1()
    {
       return Inertia::render('Module1/Lesson1');
    }
}
