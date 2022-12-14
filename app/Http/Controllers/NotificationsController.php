<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Services\CrudServices\CreateServices;
use App\Services\CrudServices\DeleteServices;
use App\Services\CrudServices\UpdateServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class NotificationsController extends Controller
{
    /**
     * @return mixed
     */
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('read', 'notifications');

        $notifications = Notification::paginate(10);

        $method = 'read';

        return view('admin/notifications/index', compact(
            'method',
            'notifications'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', 'notifications');

        $notifications = Notification::all();
        $method = 'create';

        return view('admin/notifications/index', compact(
            'notifications',
            'method'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param CreateServices $create
     * @return Response
     */
    public function store(Request $request, CreateServices $create)
    {
        $this->authorize('create', 'notifications');

        $create->createNotifications($request);

        return redirect('/admin/notifications');
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $ID
     * @return Response
     */
    public function edit(int $ID)
    {
        $this->authorize('update', 'notifications');

        $notification = Notification::find($ID);
        $method = 'edit';

        return view('admin/notifications/index', compact(
            'notification',
            'method'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $ID
     * @param UpdateServices $update
     * @return Response
     */
    public function update(Request $request, int $ID, UpdateServices $update)
    {
        $this->authorize('update', 'notifications');

        $update->updateNotification($request, $ID);

        return redirect('/admin/notifications');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $ID
     * @param DeleteServices $delete
     * @return Response
     */
    public function destroy(Request $request, int $ID, DeleteServices $delete)
    {
        $this->authorize('delete', 'notifications');

        $delete->deleteNotification($request, $ID);

        return redirect()->back();
    }

    /**
     * Remove the several resource from storage.
     *
     * @param Request $request
     * @param DeleteServices $delete
     * @return Response
     */
    public function destroySeveral(Request $request, DeleteServices $delete)
    {
        $this->authorize('delete', 'notifications');

        $delete->deleteSeveralNotification($request);

        return redirect()->back();
    }

    /**
     * @param int $ID
     * @return Response
     */
    public function view(int $ID)
    {
        DB::beginTransaction();
            NotificationUser::where('user_id', Auth::user()
                ->id)->where('notifications_id', $ID)
                ->get()[0]
                ->update(['notification_status' => 'off']);
        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifica????o marcada como vista!'
        ], 201);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function viewSeveral(Request $request)
    {
        DB::beginTransaction();
            foreach($request->ids as $ID):
                NotificationUser::where('user_id', Auth::user()
                    ->id)->where('notifications_id', $ID)
                    ->get()[0]
                    ->update(['notification_status' => 'off']);
            endforeach;
        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifica????es marcadas como vista!'
        ], 201);
    }
}
