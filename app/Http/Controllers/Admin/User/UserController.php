<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\giohang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function newUser() {
        return view('pages.admin.users.new-users');
    }

    public function createUsers(Request $request)
    {
        
        // Thông báo validation
        $messages = [
            'required' => 'Vui lòng nhập :attribute.',
            'email' => 'Địa chỉ email không hợp lệ.',
            'unique' => 'Địa chỉ email đã tồn tại.',
            'image' => 'Tệp phải là hình ảnh.',
            'mimes' => 'Định dạng ảnh không hợp lệ.',
            'max' => 'Kích thước ảnh không được vượt quá :max kilobytes.',
        ];
    
        // Validate request data
        $request->validate([
            'ND_Ten' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'password' => 'required|min:6',
            'ND_avt' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], $messages);
    
        if($request->files->has('user-img')) {
            $file = $request->file('user-img');
            $userImg = $request->file('user-img')->getClientOriginalName();
      
            $avtdb = '/storage/images/users/'. $userImg ;
            $path = 'public/storage/images/users/';
      
            $file->move(base_path($path), $userImg );
            $avatarUrl = $avtdb;
          } else {
            $avatarUrl = '/storage/images/admin/user_default.png';
          }
        $user = User::firstOrNew(['email' => $request->input('email')], [
            'ND_VT' => $request->input('ND_VT'),
            'ND_Ho' => $request->input('ND_Ho'),
            'ND_Ten' => $request->input('ND_Ten'),
            'ND_SDT' => $request->input('ND_SDT'),
            'ND_Diachi' => $request->input('ND_Diachi'),
            'password' => Hash::make($request->input('password')),
            'ND_avt' => $avatarUrl,
        ]);
        //check tt
        if ($user->exists) {
            session()->flash('add-failed', 'Người dùng đã tồn tại');
        }
    
        $user->save();
        giohang::create(['ND_id' => $user->id]);
    
        Session::flash('create-success', 'Tạo người dùng thành công');
        return redirect()->route('admin-users');
    }
    public function getupdateUsers($id) {
        $user = User::find($id);
        
        return view('pages.admin.users.update-users', compact('user'));
    }

    public function updateUsers($id,Request $request) {
        $user = User::find($id);
        if ($request->hasFile('user-img')) {
            $oldImg = $user->ND_avt;
            $oldImagePath = public_path('storage/images/users/' . $oldImg);
            if (file_exists($oldImagePath)) {
              unlink($oldImagePath); // Xóa ảnh cũ
          }
            $file = $request->file('user-img');
            $userImg = $file->getClientOriginalName();
        
            $avtdb = '/storage/images/users/'. $userImg;
            $path = 'public/storage/images/users/';
      
            $file->move(base_path($path), $userImg);
            $avatarUrl = $avtdb;
          } else {
            $avatarUrl  = $user->ND_avt;
        $avatar = $request->file('ND_avt');
        if ($avatar) {
            $avatarPath = $avatar->store('public/images/users');
            $avatarUrl = Storage::url($avatarPath);
            $filePath = public_path($user->ND_avt);
            if (File::exists($filePath)) {
              File::delete($filePath);
            }
          } else if($user->ND_avt) {
            $avatarUrl = $user->ND_avt;
          } else {
            $avatarUrl = Storage::url('/images/admin/user_default.png');
          }
          $ND_VT = $request->input('ND_VT');
          $ND_Ho = $request->input('ND_Ho');
          $ND_Ten = $request->input('ND_Ten');
          $ND_SDT = $request->input('ND_SDT');
          $email = $request->input('email');
          $ND_Diachi = $request->input('ND_Diachi');
          $password = $request->input('password');
          
          DB::table('users')
            ->where('id', $id)
            ->update([
              'ND_VT' => $ND_VT,
              'ND_Ho' => $ND_Ho,
              'ND_Ten' => $ND_Ten,
              'ND_SDT' => $ND_SDT,
              'email' => $email,
              'ND_Diachi' => $ND_Diachi,
              'ND_avt' => $avatarUrl,
              'password' => Hash::make($password)
            ]);
          Session::flash('update-success', 'Cập nhật người dùng thành công.');
          return redirect()->route('admin-users',compact('password'));
    }   
   
    }
    public function delete(Request $request)
    {
        $user_id = $request->input('user_id');
    
        
        $hasDetailsUser = DB::table('giohang')
        ->where('ND_id', '=', $user_id)
        ->exists();

 
        if ($hasDetailsUser ) {
        DB::table('giohang')
            ->where('ND_id', '=', $user_id)
            ->delete();
        }

    
        DB::table('users')
            ->where('id', '=', $user_id)
            ->delete();

        Session::flash('delete-success', 'Xóa người dùng thành công');
        return redirect()->route('admin-users');
    }

    public function searchUser(Request $request) {
        $ND_Ten = $request->input('ND_Ten');
        $data = DB::table('users')
        ->join('vaitro', 'users.ND_VT', '=', 'vaitro.VT_Ma')
        ->where('users.ND_Ten', 'LIKE', '%'.  $ND_Ten .'%')
        ->select('users.*','vaitro.VT_Ten')
        ->get();

        return view('pages.admin.users.search-users',compact('ND_Ten','data'));
    }
}

