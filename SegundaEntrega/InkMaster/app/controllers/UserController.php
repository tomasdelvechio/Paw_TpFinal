<?php
namespace App\Controllers;

use App\Core\Controller;
use App\models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->user = new User();
        $this->generalController = new GeneralController();
        $this->session = false;
    }

    public function register() {
        return $this->generalController->view('register');
    }

    public function saveUser() {
        $array = $this->user->validateInsert($this->parameters());
        $status = $array[count($array)-1];
        if ($status) {  #si salio bien la validacion
            $variable["parameters"] = $array;
            return $this->generalController->view('register', $variable);
        } else {
            $variable["errors"] = $array;
            return $this->generalController->view('errors.register', $variable);
        }
    }

    public function logIn() {
        session_start();
        if (!isset($_SESSION["id_user"])) {
            return $this->generalController->view('login');
        }
        return $this->generalController->view('not_found');
    }

    public function autentication() {
        session_start();
        if (!isset($_SESSION["id_user"])) {
            $id_user = $_POST["id_user"];
            $password = $_POST["password"];
            $result = $this->user->autentication($id_user, $password);
            if ($result) {
                $variable["msgWelcome"] = "bienvenido $id_user ! ";
                $_SESSION["id_user"] = $id_user;
            } else {
                $variable["msgWelcome"] = "usuario inválido";
            }
            return $this->generalController->view('index.views', $variable);
        }
        return $this->generalController->view('not_found');
    }

    public function logOut() {
        session_start();
        if (isset($_SESSION["id_user"])) {
            $_SESSION = array();
            return $this->generalController->view('index.views');
        }
        return $this->generalController->view('index.views');
    }

    public function listUsers() {
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            if ($this->generalController->user->havePermissions($id_user, 'user.list')) {
                $variable["artists"] = $this->user->listUsers();
                return $this->generalController->view('list.users', $variable);
            }
        }
        return $this->generalController->view('not_found');
    }

    public function viewUser($id_user_v) {
        $id_user_v = str_replace("%20", " ", $id_user_v);
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            if ($this->generalController->user->havePermissions($id_user, 'user.view')) {
                if ($this->generalController->isAdministrator($id_user) || $id_user_v == $id_user) {
                    $user = $this->user->findUser($id_user_v);
                    $variable["user"] = $user;
                    return $this->generalController->view('view.user', $variable);
                }
            }
        }
        return $this->generalController->view('not_found');
    }

    public function editUser($id_user_v) {
        $id_user_v = str_replace("%20", " ", $id_user_v);
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            if ($this->generalController->user->havePermissions($id_user, 'user.edit')) {
                $user = $this->user->findUser($id_user_v);
                $variable["user"] = $user;
                return $this->generalController->view('edit.user', $variable);
            }
        }
        return $this->generalController->view('not_found');
    }

    public function uptUser() {
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            if ($this->generalController->user->havePermissions($id_user, 'user.edit')) {
                $parameters = $this->comparacion($id_user);
                $array = $this->user->validateUpdate($id_user, $parameters);
                $status = $array[count($array)-1];
                if ($status) {  #si salio bien la validacion
                    $user = $this->user->findUser($id_user);
                    $variable["user"] = $user;
                    return $this->generalController->view('view.user', $variable);
                } else {
                    $variable["errors"] = $array;
                    return $this->generalController->view('errors.register', $variable);
                }
            }
        }
        return $this->generalController->view('not_found');
    }

    public function delUser() {
        return view();#'list.appointments', compact('appointments'));
    }

    public function listArtists() {
        $variable["artists"] = $this->user->listArtists($this->generalController->getIdLocal());
        return $this->generalController->view('list.artists', $variable);
    }

    public function viewArtist($id_artist) {
        $id_artist = str_replace("%20", " ", $id_artist);
        $variable["artist"] = $this->user->findArtist($id_artist);
        return $this->generalController->view('view.artist', $variable);
    }

    public function comparacion($id_user) {
        $parameters = array();
        $old = $this->user->findUser($id_user);
        if ($old["first_name"] != $_POST["first_name"]) $parameters["first_name"] = $_POST["first_name"];
        if ($old["last_name"] != $_POST["last_name"]) $parameters["last_name"] = $_POST["last_name"];
        if ($old["born"] != $_POST["born"]) $parameters["born"] = $_POST["born"];
        if ($old["nro_doc"] != $_POST["nro_doc"]) $parameters["nro_doc"] = $_POST["nro_doc"];
        if ($old["phone"] != $_POST["phone"]) $parameters["phone"] = $_POST["phone"];
        if ($old["direction"] != $_POST["direction"]) $parameters["direction"] = $_POST["direction"];
        if ($old["email"] != $_POST["email"]) $parameters["email"] = $_POST["email"];
        if (isset($_FILES["photo_edit"])) $parameters["photo"] = $_FILES["photo_edit"];
        if (!empty($_POST["pathology"]) && $old["pathology"] != $_POST["pathology"]) $parameters["pathology"] = $_POST["pathology"];
        if (isset($_POST["artist"]) || isset($_POST["txt"])) {
            $parameters["artist"] = true;
            if ($old["txt"] != $_POST["txt"]) $parameters["txt"] = $_POST["txt"];
        }
        return $parameters;
    }

    public function parameters() {
        $parameters = array();
        if (isset($_POST["id_user"])) $parameters["user"] = $_POST["id_user"];
        if (isset($_POST["password"])) $parameters["password"] = $_POST["password"];
        if (isset($_POST["confirm_password"])) $parameters["confirm_password"] = $_POST["confirm_password"];
        if (isset($_POST["first_name"])) $parameters["first_name"] = $_POST["first_name"];
        if (isset($_POST["last_name"])) $parameters["last_name"] = $_POST["last_name"];
        if (isset($_POST["born"])) $parameters["born"] = $_POST["born"];
        if (isset($_POST["nro_doc"])) $parameters["nro_doc"] = $_POST["nro_doc"];
        if (isset($_POST["phone"])) $parameters["phone"] = $_POST["phone"];
        if (isset($_POST["direction"])) $parameters["direction"] = $_POST["direction"];
        if (isset($_POST["email"])) $parameters["email"] = $_POST["email"];
        if (isset($_FILES)) $parameters["photo"] = $_FILES;
        if (isset($_POST["pathology"])) $parameters["pathology"] = $_POST["pathology"];
        if (isset($_POST["artist"])) {
            $parameters["artist"] = true;
            $parameters["local"] = $this->generalController->getIdLocal();
            if (isset($_POST["txt"])) $parameters["txt"] = $_POST["txt"];
        }
        return $parameters;
    }
}