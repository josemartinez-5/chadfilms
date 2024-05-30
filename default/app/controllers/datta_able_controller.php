<?php

class DattaAbleController extends AppController{

    public function index(){
        $this->title = "Plantilla Datta Able";
        $this->subtitle = "Contenido";
        //Se utiliza el template "datta_able" para este controlador
        View::template("datta_able");
    }

    public function show($id)
    {
        $this -> alumno_id = $id;
    }
}
