<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DevicesController extends Controller
{
    //
    public function registerDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'device' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Check if input data is filled correctly'], 406);
        }

        //find user that will be associated with this device
        $user = User::where('email', $request->input('email'))->first();

        if (!isset($user)) {
            return response()->json(['User not found'], 401);
        }

        //check if user login credentials are correct
        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['Incorrect password'], 401);
        }

        //find device, if not found then register that device
        $device = Device::where('device_name', $request->input('device'))->first();

        if (isset($device)) {
            return response()->json(['Device already exist']);
        }

        $device = Device::create([
            'device_name' => $request->input('device'),
            'custom_device_name' => $request->input('device'),
            'user_id' => $user->id,
            'status' => 'Online'
        ]);

        return response()->json(['Device registered successfully']);
    }

    public function changeDeviceName(Request $request)
    {
        if (!$request->method('post')) {
            return redirect()->route('dashboard')->with('flash', [
                'type' => 'danger',
                'message' => 'Could not save new device name.'
            ]);
        }

        $device = Device::where('device_name', $request->input('device_name'))->first();

        if (!isset($device)) {
            return redirect()->route('dashboard')->with('flash', [
                'type' => 'danger',
                'message' => 'Device does not exist.'
            ]);
        }

        $device->custom_device_name = $request->input('new_device_name');
        if ($device->save()) {
            return redirect()->route('dashboard')->with('flash', [
                'type' => 'success',
                'message' => 'Device name changed successfully.'
            ]);
        }

        return redirect()->route('dashboard')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not save new device name. Please try again.'
        ]);
    }
}
