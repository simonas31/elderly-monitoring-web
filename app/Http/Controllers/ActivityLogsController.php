<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\User;
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
            $user = User::where('id', $device->user_id)->first();
            $user->sendSMS('Fall detected. Please check up on elder.');

            //find all other associated users with current user
            $associated_users = User::where('parent_user_id', '=', $user->id)->get();
            foreach ($associated_users as $associated_user) {
                $associated_user->sendSMS('Fall detected. Please check up on elder.');
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
    public function getStatistics(Request $request, $device_name)
    {
        $device = Device::where('device_name', $device_name)->first();

        $logs = DB::table('activity_logs')->where('device_id', '=', $device->id)->get()->toArray();

        return response()->json($logs);
    }
}
