<?php

use App\Livewire\MenuView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', MenuView::class)->name('menu')->lazy();
