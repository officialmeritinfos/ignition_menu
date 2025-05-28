<?php

use App\Livewire\MenuView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-remote-wp', function () {
    try {
        $posts = DB::connection('wordpress')->table('posts')
            ->where('post_type', 'mp_menu_item')
            ->where('post_status', 'publish')
            ->get();

        return response()->json($posts);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/menu', MenuView::class)->name('menu')->lazy();
