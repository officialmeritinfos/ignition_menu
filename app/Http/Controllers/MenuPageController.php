<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wordpress\MenuItem;

class MenuPageController extends Controller
{

    public function index()
    {
        $menus = MenuItem::with(['metadata', 'terms'])
            ->where('post_type', 'mp_menu_item')
            ->where('post_status', 'publish')
            ->orderBy('menu_order')
            ->get();

        return view('menus.index', compact('menus'));
    }

}
