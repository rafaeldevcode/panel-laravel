<?php

namespace App\Http\Controllers;

use App\Services\CrudServices\UpdateServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsControllers extends Controller
{
    /**
     * @return mixed
     */
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $this->authorize('update', 'settings');

        $message = $request->session()->get('message');
        $type = $request->session()->get('type');
        $settings = DB::table('settings')->first();
        $images = $this->returnPathsImagesOfSettings($settings);

        return view('admin/settings/index', compact(
            'settings',
            'message',
            'type',
            'images'
        ));
    }

    /**
     * @param Request $request
     * @param UpdateServices $update
     * @return mixed
     */
    public function store(Request $request, UpdateServices $update)
    {
        $this->authorize('read', 'settings');

        $update->updateSettings($request);

        return redirect()->back();
    }

    /**
     * @param mixed $settings
     * @return array
     */
    private function returnPathsImagesOfSettings(mixed $settings): array
    {
        $site_logo = '/assets/images/logo.png';
        $site_logo_header = '/assets/images/logo_header.png';
        $site_favicon = '/assets/images/favicon.png';
        $site_bg_login = '/assets/images/login_bg.png';

        if($settings->site_logo !== 'logo.png'):
            $site_logo = "/storage/{$settings->site_logo}";
        endif;

        if($settings->site_logo_header !== 'logo_header.png'):
            $site_logo_header = "/storage/{$settings->site_logo_header}";
        endif;

        if($settings->site_favicon !== 'favicon.png'):
            $site_favicon = "/storage/{$settings->site_favicon}";
        endif;

        if($settings->site_bg_login !== 'login_bg.png'):
            $site_bg_login = "/storage/{$settings->site_bg_login}";
        endif;


        return [
            'site_logo'        => $site_logo,
            'site_logo_header' => $site_logo_header,
            'site_favicon'     => $site_favicon,
            'site_bg_login'    => $site_bg_login
        ];
    }
}
