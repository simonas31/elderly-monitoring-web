<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\User;
use App\Notifications\SendFallAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityLogsController extends Controller
{
    /**
     * Api post method that saves activity log information
     */
    public function movementDetected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fell' => 'required|boolean',
            'device_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Check if input data is filled correctly'], 406);
        }

        $device = Device::where('device_name', $request->input('device_name'))->first();

        if (!isset($device)) {
            return response()->json(['Could not find device'], 406);
        }

        //notify the user that person fell
        if ($request->input('fell')) {
            $user = User::find($device->user_id);
            $user->notify(new SendFallAlertNotification());

            //find all other associated users with current user
            $associated_users = User::where('user_id', $user->id)->get();
            foreach ($associated_users as $associated_user) {
                $associated_user->notify(new SendFallAlertNotification());
            }
        }

        ActivityLog::create([
            'device_id' => $device->id,
            'fell' => $request->input('fell')
        ]);

        return response()->json(['OK']);
    }

    /**
     * Api get method that retrieves persons activity log information
     */
    public function getStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Check if input data is filled correctly'], 406);
        }

        $logs = DB::table('activity_logs')->where('device_name', '=', $request->input('device_name'))->get()->toArray();

        return response()->json($logs);
    }
}
