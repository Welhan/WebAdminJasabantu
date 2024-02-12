<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('generate_menu')) {
    function generate_menu()
    {
        $sql = DB::table('mst_menu')
            ->select('mst_menu.ID', 'mst_menu.MenuName', 'mst_menu.MenuIcon')
            ->distinct()
            ->leftJoin('mst_submenu', 'mst_submenu.MenuID', '=', 'mst_menu.ID')
            ->where('mst_menu.MenuAccess', 1)
            ->get();
        return $sql;
    }
}
if (!function_exists('generate_submenu')) {
    function generate_submenu($menuID)
    {
        $sql = DB::table('mst_submenu')
            ->select('ID', 'SubmenuName', 'MenuLink')
            ->where('MenuID', $menuID)
            ->where('ActiveStatus', 1)
            ->orderBy('ID', 'ASC')
            ->get();

        return $sql;
    }
}
