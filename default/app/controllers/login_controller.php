<?php

Load::model("customer");
class LoginController extends Controller {
    public function index() {
        $this->title = "Login";
        $this->subtitle = "LOG IN RIGHT NOW";
        // Template a usar
        View::template("datta_able");
        if (Input::hasPost('login')) {
            $login = Input::post('login');
            $email = $login["email"];

            // Se inicia el Auth verificando el email con el modelo usuarios
            $auth = new Auth("model", "class: Customer", "email: " . $email);
            if ($auth->authenticate()) {
                $user = (new Customer)->find(Auth::get('id'));
                if (password_verify($login["password"],$user->password)){
                    //Si el usuario es válido, se redirige a la página inicial
                    Redirect::to("/");
                }
                else{
                    Flash::error("Error: Email o contraseña incorrectos");
                    Auth::destroy_identity();
                }
            } else {
                Flash::error("Error: Email o contraseña incorrectos");
            }
        }
    }
}