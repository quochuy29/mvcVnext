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
        $users = new User();
        // $country = new Countries();
        // $model['users'] = $users->select('countries.name as nameCountry,users.*')->join('countries', 'countries.id', '=', 'users.country_id')->get();
        // $model['countries'] = $country->get();
      $model = $users->paginate(5);
        echo json_encode($model);
    }

    public function create()
    {
        $request = new Request();


        $message = [
            'name.required' => "Hãy nhập vào tên thú cưng",
            'name.min' => "Tên người dùng ít nhất 3 kí tự",
            'email.required' => "Hãy nhập email",
            'email.email' => "Email nhập không đúng định dạng",
            'avatar.required' => 'Hãy chọn ảnh thú cưng',
            'avatar.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
            'avatar.max' => 'File ảnh không được quá 2MB',
            'phone_number.required' => "Hãy nhập số điện thoại",
            'phone_number.regex' => "Số điện thoại không đúng định dạng",
            'gender.required' => "Hãy chọn giới tính",
            'birth_date.required' => "Hãy chọn ngày sinh",
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
                'birth_date' => 'required',
                'avatar' => 'required|mimes:jpg,bmp,png,jpeg|max:2048',
            ],
            $message
        );

        if ($validator->fails()) {
            echo json_encode(["status" => 422, "errors" => $validator->errors()]);
        }
    }
}
