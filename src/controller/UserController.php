<?php

namespace MVC\controller;

use App\core\Controller;
use App\core\Request;
use App\core\Validator;
use MVC\model\Countries;
use MVC\model\User;

class UserController extends Controller
{
    public function index()
    {
        $model = new User();
        return $this->render('user');
    }

    public function getUser()
    {
        $request = new Request();
        $users = new User();
        $country = new Countries();

        $model['countries'] = $country->get();
        $model['users'] = $users->select('countries.name as nameCountry,users.*')->join('countries', 'countries.id', '=', 'users.country_id');
        if (!empty($request->all()['search'])) {
            $model['users'] = $model['users']->where('users.name', 'like', '%' . $request->all()['search'] . '%');
        }
        $model['users'] =  $model['users']->paginate(5);
        $model['page'] = $users->page;
        $model['totalPage'] = $users->totalPage;

        echo json_encode($model);
    }

    public function create()
    {
        $request = new Request();

        $users = new User();

        $message = [
            'name.required' => "Hãy nhập vào tên người dùng",
            'name.min' => "Tên người dùng ít nhất 3 kí tự",
            'email.required' => "Hãy nhập email",
            'email.email' => "Email nhập không đúng định dạng",
            'avatar.required' => 'Hãy chọn ảnh người dùng',
            'avatar.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
            'avatar.max' => 'File ảnh không được quá 2MB',
            'avatar.image' => 'Chỉ nhận file hình ảnh',
            'phone_number.required' => "Hãy nhập số điện thoại",
            'phone_number.regex' => "Số điện thoại không đúng định dạng",
            'gender.required' => "Hãy chọn giới tính",
            'birth_date.required' => "Hãy chọn ngày sinh",
            'birth_date.date' => "Ngày sinh không đúng định dạng",
            'country_id.required' => "Hãy chọn quê quán"
        ];
        $validator = new Validator();
        $validator->validate(
            $request->all(),
            [
                'name' => 'required|min:3',
                'email' => 'required|email',
                'phone_number' => 'required|regex:/^(09|03|07|08|05)[0-9]{8,9}$/',
                'gender' => 'required',
                'country_id' => 'required',
                'birth_date' => 'required|date:y-m-d',
                'avatar' => 'required|mimes:jpg,bmp,png,jpeg|image|size:2048',
            ],
            $message
        );

        if ($validator->fails()) {
            echo json_encode(["errors" => $validator->errors()]);
        } else {
            $filename = uniqid() . "-" . $request->all()['avatar']["name"];
            $target_path = getcwd() . DIRECTORY_SEPARATOR;
            move_uploaded_file($request->all()['avatar']["tmp_name"], $target_path . '/public/upload/' . basename($filename));
            $path = '/public/upload/' . $filename;
            $request->changeFile($path);
            $users->insert($request->all());
            echo json_encode(["message" => "Thêm thành công"]);
        }
    }

    public function getUserId($id)
    {
        $users = new User();
        $country = new Countries();

        $model['countries'] = $country->get();
        $model['dataUserId'] = $users->where('id', '=', $id)->first();
        echo json_encode($model);
    }

    public function update($id)
    {
        $request = new Request();
        $users = new User();
        $model = $users->where('id', '=', $id)->first();

        $message = [
            'name.required' => "Hãy nhập vào tên người dùng",
            'name.min' => "Tên người dùng ít nhất 3 kí tự",
            'email.required' => "Hãy nhập email",
            'email.email' => "Email nhập không đúng định dạng",
            'phone_number.required' => "Hãy nhập số điện thoại",
            'phone_number.regex' => "Số điện thoại không đúng định dạng",
            'gender.required' => "Hãy chọn giới tính",
            'birth_date.required' => "Hãy chọn ngày sinh",
            'birth_date.date' => "Ngày sinh không đúng định dạng",
            'country_id.required' => "Hãy chọn quê quán"
        ];

        $validate = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^(09|03|07|08|05)[0-9]{8,9}$/',
            'gender' => 'required',
            'country_id' => 'required',
            'birth_date' => 'required|date:y-m-d'
        ];
        if (empty($model->avatar)) {
            $avatarMessage = [
                'avatar.required' => 'Hãy chọn ảnh người dùng',
                'avatar.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
                'avatar.max' => 'File ảnh không được quá 2MB',
                'avatar.image' => 'Chỉ nhận file hình ảnh',
            ];

            $avatarValidate = ['avatar' => 'required|mimes:jpg,bmp,png,jpeg|image|size:2048'];

            $message = array_merge($message, $avatarMessage);

            $validate = array_merge($validate, $avatarValidate);
        }

        $validator = new Validator();
        $validator->validate(
            $request->all(),
            $validate,
            $message
        );

        if ($validator->fails()) {
            echo json_encode(["errors" => $validator->errors()]);
        } else {
            if (!empty($request->all()['avatar']['name'])) {
                $filename = uniqid() . "-" . $request->all()['avatar']["name"];
                $target_path = getcwd() . DIRECTORY_SEPARATOR;
                move_uploaded_file($request->all()['avatar']["tmp_name"], $target_path . '/public/upload/' . basename($filename));
                $path = '/public/upload/' . $filename;
                $request->changeFile($path);
            } else {
                $request->changeFile($model->avatar);
            }

            $users->update($request->all(), $id);
            echo json_encode(["message" => "Cập nhật thành công"]);
        }
    }

    public function copy($id)
    {
        $request = new Request();
        $users = new User();
        $model = $users->where('id', '=', $id)->first();

        $message = [
            'name.required' => "Hãy nhập vào tên người dùng",
            'name.min' => "Tên người dùng ít nhất 3 kí tự",
            'email.required' => "Hãy nhập email",
            'email.email' => "Email nhập không đúng định dạng",
            'phone_number.required' => "Hãy nhập số điện thoại",
            'phone_number.regex' => "Số điện thoại không đúng định dạng",
            'gender.required' => "Hãy chọn giới tính",
            'birth_date.required' => "Hãy chọn ngày sinh",
            'birth_date.date' => "Ngày sinh không đúng định dạng",
            'country_id.required' => "Hãy chọn quê quán"
        ];

        $validate = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^(09|03|07|08|05)[0-9]{8,9}$/',
            'gender' => 'required',
            'country_id' => 'required',
            'birth_date' => 'required|date:y-m-d'
        ];
        if (empty($model->avatar)) {
            $avatarMessage = [
                'avatar.required' => 'Hãy chọn ảnh người dùng',
                'avatar.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
                'avatar.max' => 'File ảnh không được quá 2MB',
                'avatar.image' => 'Chỉ nhận file hình ảnh',
            ];

            $avatarValidate = ['avatar' => 'required|mimes:jpg,bmp,png,jpeg|image|size:2048'];

            $message = array_merge($message, $avatarMessage);

            $validate = array_merge($validate, $avatarValidate);
        }

        $validator = new Validator();
        $validator->validate(
            $request->all(),
            $validate,
            $message
        );

        if ($validator->fails()) {
            echo json_encode(["errors" => $validator->errors()]);
        } else {
            if (!empty($request->all()['avatar']['name'])) {
                $filename = uniqid() . "-" . $request->all()['avatar']["name"];
                $target_path = getcwd() . DIRECTORY_SEPARATOR;
                move_uploaded_file($request->all()['avatar']["tmp_name"], $target_path . '/public/upload/' . basename($filename));
                $path = '/public/upload/' . $filename;
                $request->changeFile($path);
            } else {
                $request->changeFile($model->avatar);
            }

            $users->insert($request->all());
            echo json_encode(["message" => "Sao chép thành công"]);
        }
    }

    public function delete($id)
    {
        $users = new User();
        $users->where('id','=',$id)->delete();
        
        echo json_encode(['message' => "Xoá người dùng thành công"]);
    }
}
