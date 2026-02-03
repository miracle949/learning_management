<?php

require_once "../app/models/User.php";

class AuthController
{
    public function login()
    {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $userModel = new User();

            $user = $userModel->login($email);

            if($user && password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['grade_level'] = $user['grade_level'];
                $_SESSION['section'] = $user['section'];

                header("Location: /learning_management/public/?url=dashboard");
                exit;
            }

        }

        require "../app/view/login.php";
    }

    public function signup()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $student_id = $_POST["student_id"];
            $firstname = $_POST["firstname"];
            $middle = $_POST["middle"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];
            $grade_level = $_POST["grade_level"];
            $section = $_POST["section"];

            $userModel = new User();

            if ($password !== $confirm_password) {

                die("Don't match the password");

            } else {

                $password_HASH = password_hash($password, PASSWORD_DEFAULT);

                $userModel->signup($student_id, $firstname, $middle, $lastname, $email, $username, $password_HASH, $grade_level, $section);

                header("Location: /learning_management/public/?url=login");
                exit;
            }

        }

        require "../app/view/signup.php";
    }

    public function logout(){
        session_destroy();
        
        header("Location: /learning_management/public/?url=login");
    }
}