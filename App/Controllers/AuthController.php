<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use \Core\View;
use Rakit\Validation\Validator;


/**
 * Home controller
 */
class AuthController extends Controller
{

    /**
     * Show the index page
     *
     * @return void
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function loginAction()
    {
        View::renderTemplate('Auth/login.html');
    }

    /**
     * Login user
     * @return void
     */
    public function loginPostAction()
    {
        $validator = new Validator;

        $validation = $validator->make($_POST, [
            'username' => 'required',
            'password' => 'required|min:3'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();

            $_SESSION['old_values'] = $_POST;
            $_SESSION['errors'] = $errors->firstOfAll();

            return header('Location: /sign-in');
        }

        $userByEmail = User::query()
            ->select("id", "username", "password")
            ->where('username', '=', $_POST['username'])
            ->first();

        if(!empty($userByEmail)) {
            $hash = $userByEmail->password;

            if(password_verify($_POST['password'], $hash)) {
                $_SESSION['user'] = [
                    'username' => $userByEmail->username,
                    'id'   => $userByEmail->id,
                ];

                return header('Location: /');
            }
        }

        $_SESSION['old_values'] = $_POST;
        $_SESSION['errors'] = ['username' => 'Username or password incorrect.'];

        header('Location: /sign-in');
    }

    /**
     * Log out user
     *
     * @return void
     */
    public function logoutAction()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }
}
