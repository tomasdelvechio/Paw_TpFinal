<?php
namespace App\Controllers;

use App\Core\Controller;
use App\models\User;
use App\models\Tattoo;
use App\models\FAQ;
use App\models\Local;

class GeneralController extends Controller
{

    public function __construct()
    {
        $this->user = new User();
        $this->faq = new FAQ();
        $this->tattoo = new Tattoo();
        $this->local = new Local();
        $this->session = false;
        $this->id_local = $this->local->getLocal();
    }

    public function view($html, $variable = null, $isArtist = null) {
        $session = $this->session();
        $artists = $this->user->listArtists($this->id_local);
        $local = $this->local->getTxt($this->id_local);
        if (isset($_SESSION["id_user"])) {
            $user = $_SESSION["id_user"];
            $isArtist = $this->isArtist($user);
            $isAdministrator = $this->isAdministrator($user);
            return view($html, compact('session', 'artists', 'local', 'user', 'isArtist', 'isAdministrator', 'variable'));
        }
        return view($html, compact('session', 'artists', 'local', 'isArtist', 'variable'));
    }

    public function index() {
        return $this->view('index.views');
    }

    public function updPhotos() {
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            if ($this->user->havePermissions($id_user, 'tattoo.new')) {
                return $this->view('upload.photos');
            }
        }
        return $this->view('not_found');
    }
    public function updMedRec($id_user, $medical){
        return $this->user->updMedRec('medical_record' ,$id_user, $medical);
    }

    public function savePhotos() {
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user = $_SESSION["id_user"];
            if ($this->user->havePermissions($id_user, 'tattoo.new')) {
                $parameters = $this->parameters();
                if ($parameters) {
                    $array = $this->tattoo->validateInsert($parameters);
                } else {
                    $array = ["No se completaron todos los campos obligatorios", false];
                }
                $status = $array[count($array)-1];
                if ($status) {  #si salio bien la validacion
                    return $this->view('upload.photos');
                } else {
                    $variable["errors"] = $array;
                    return $this->view('errors.register', $variable);
                }
            }
        }
        return $this->view('not_found');
    }

    public function listTattoos() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $quantity = 6; //Cant de fotos por pág
        $beginning = ($page > 1) ? (($page * $quantity) - $quantity) : 0;
        $totalTattoos = $this->tattoo->countTattoos();
        if ($totalTattoos > 0) {
            if ($totalTattoos > $quantity) {
                $variable["total_pages"] = ceil($totalTattoos['total'] / $quantity);
                $variable["page"] = $page;
            }
            $variable["tattoos"] = $this->tattoo->getTattoos($beginning, $quantity);
        }
        return $this->view('list.tattoos', $variable);
    }

    public function viewTattoo() {
        return view();#'list.appointments', compact('appointments'));
    }

    public function listFaq() {
        $variable = array();
        $variable["faqs"] = $this->faq->listFaq();
        return $this->view('faq', $variable);
    }

    public function viewFaq($id_faq) {
        $variable = array();
        $variable["faq"] = $this->faq->find($id_faq);
        return $this->view('faq.views', $variable);
    }

    public function listTerms(){
        return view('terms.and.conditions');
    }

    public function parameters() {
        $boolean = true;
        $parameters["artist"] = $_SESSION["id_user"];
        if (isset($_POST["sector"])){
            $parameters["sector"] = $_POST["sector"];
        } else {
            $boolean = false;
        }
        if (isset($_FILES)){
            $parameters["image"] = $_FILES;
        } else {
            $boolean = false;
        }
        if (isset($_POST["description"])) {
            $parameters["txt"] = $_POST["description"];
        } else {
            $boolean = false;
        }
        if ($boolean) {
            return $parameters;
        } else {
            return false;
        }
    }

    public function session() {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["id_user"])) {
            $this->session = true;
        } else {
            $this->session = false;
        }
        return $this->session;
    }

    public function getIdLocal() {
        return $this->id_local;
    }

    public function isArtist($id_user) {
        return $this->user->isArtist($id_user, $this->id_local);
    }

    public function isAdministrator($id_user) {
        return $this->user->isAdmin($id_user, $this->id_local);
    }

    public function delFaq($id_faq){
        $this->faq->delFaq($id_faq);
        $variable["faqs"] = $this->faq->listFaq();
        return $this->view('faq', $variable);
    }

    public function editFaq($id_faq){
        $variable["faq"] = $this->faq->find($id_faq);
        return $this->view('faq.edit', $variable);
    }

    public function addFaq(){
        return $this->view('new.faq', null);
    }
    public function saveFaq(){
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user= $_SESSION["id_user"];
            $parameters["answer"]= $_POST['answer'];
            $parameters["question"]= $_POST['question'];
            $parameters["summary"]= $_POST['summary'];
            if ($this->user->havePermissions($id_user, 'faq.edit')) {

                $this->faq->newFaq($parameters);

                $variable["faqs"] =$this->faq->listFaq();
                return $this->view('faq', $variable);
            }
        }
        return $this->view('not_found');
    }

    public function updFaq(){
        session_start();
        if (isset($_SESSION["id_user"])) {
            $id_user= $_SESSION["id_user"];
            $id_faq = $_POST['id_faq'];
            $parameters["answer"]= $_POST['answer'];
            $parameters["question"]= $_POST['question'];
            $parameters["summary"]= $_POST['summary'];
            if ($this->user->havePermissions($id_user, 'faq.edit')) {
                $this->faq->updateFaq($id_faq, $parameters);

                $variable["faqs"] =$this->faq->listFaq();
                return $this->view('faq', $variable);
            }
        }
        return $this->view('not_found');
    }

}
