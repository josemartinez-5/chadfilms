<?php

// controllers/alumnos_controller.php
// URL: KumbiaPHP/alumnos
class AlumnosController extends AppController{

    // views/alumnos/index.phtml
    public function index(){
        $this->title = "Alumnos";
        $this->subtitle = "Lista de alumnos";
        //Se utiliza el template "default" para este controlador
        View::template("default");
    }

    // views/alumnos/show.phtml
    // URL: KumbiaPHP/alumnos/view
    public function show($id)
    {
        $this -> alumno_id = $id;
    }
}