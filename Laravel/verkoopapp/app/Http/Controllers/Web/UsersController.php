<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function getUsersList()
    {
        $users = User::where('login_type', '!=', 'admin')->get();

        return view('users.index', ['users' => $users]);
    }

    public function getUserDetails($id)
    {
        $user = User::where('id', $id)->first();

        return view('users.edit', ['user' => $user]);
    }

    public function addUser(Request $request)
    {
        $rules = array(
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $msgs = $validator[$i];
            }
            $data = [
                'message' => $msgs,
                'data' => (object) array(),
            ];

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $email = $request->input('email');
        $checkemail = User::where('email', $email)->first();
        if ($checkemail) {
            $data['message'] = 'Email already used!';

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $username = $request->input('username');
        $password = $request->input('password');
        $confirm = $request->input('confirm_password');
        if ($password != $confirm) {
            $data['message'] = 'Password do not match!';

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $is_active = $request->input('is_active');
        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => $is_active,
        ]);
        $data['success'] = true;

        return response()->json($data);
    }

    public function updateUser(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
            'email' => 'required|email',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $msgs = $validator[$i];
            }
            $data = [
                'message' => $msgs,
                'data' => (object) array(),
            ];

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $email = $request->input('email');
        $checkemail = User::where('email', $email)->first();
        if ($checkemail && $checkemail->email != $email) {
            $data['message'] = 'Email already used!';

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $user_id = $request->input('user_id');
        $username = $request->input('username');
        $password = $request->input('password');
        $confirm = $request->input('confirm_password');
        if ($password != $confirm) {
            $data['message'] = 'Password do not match!';

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $is_active = $request->input('is_active');
        $user = User::find($user_id);
        $user->username = $username;
        $user->email = $email;
        if ($password != '') {
            $user->password = Hash::make($password);
        }
        $user->is_active = $is_active;
        $user->updated_by = 1;
        $user->save();
        $data['success'] = true;

        return response()->json($data);
    }

    public function deleteUser(Request $request)
    {
        $rules = array(
          'user_id' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $msgs = $validator[$i];
            }
            $data = [
              'message' => $msgs,
              'data' => (object) array(),
            ];

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        $user_id = $request->input('user_id');
        $deleted = User::where('id', $user_id)->delete();
        if ($deleted) {
            return response()->json(['status' => 200, 'message' => 'Deleted successfully']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Something went wrong'], 400);
        }
    }

    public function getAdminProfile()
    {
        $admin = User::where('login_type', 'admin')->first();

        return view('profile.edit', ['admin' => $admin]);
    }

    public function updateAdminInfo(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $msgs = $validator[$i];
            }
            $data = [
                'message' => $msgs,
                'data' => (object) array(),
            ];

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $user_id = $request->input('user_id');
        $admin = User::find($user_id);
        $admin->email = $request->input('email');
        $admin->first_name = $request->input('first_name');
        $admin->last_name = $request->input('last_name');
        $admin->save();
        $data['message'] = 'Profile updated successfully!';

        return response()->json($data, Response::HTTP_OK);
    }

    public function updateAdminPassword(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
            'current_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $msgs = $validator[$i];
            }
            $data = [
                'message' => $msgs,
                'data' => (object) array(),
            ];

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $user_id = $request->input('user_id');
        $admin = User::find($user_id);
        $current_password = $request->input('current_password');
        if (!Hash::check($current_password, $admin->password)) {
            $data['message'] = 'Incorrect current password!';

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $password = $request->input('password');
        $confirm = $request->input('confirm_password');
        if ($password != $confirm) {
            $data['message'] = 'Password do not match!';

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $admin->password = Hash::make($password);
        $admin->save();
        $data['message'] = 'Password updated successfully!';

        return response()->json($data, Response::HTTP_OK);
    }
}
