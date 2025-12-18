<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function permalinksSetting(){
        return view('backend.pages.settings.permalinks');
    }

    public function generalSettings()
    {
        return view('backend.pages.settings.general');
    }

    public function cacheManagement()
    {
        return view('backend.pages.settings.cacheManagement');
    }

    public function sitemapSettings()
    {

    }
}
