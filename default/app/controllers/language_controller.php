<?php
class LanguageController extends AppController
{
    public function index()
    {
        $this->title = "Language";
        $this->subtitle = "Lista de lenguajes";
        $this->languages = (new Language())->find();
    }

    public function show($id)
    {
        $this->title = "Películas por lenguaje";
        $this->language = (new Language())->find($id);
        $this->subtitle = "Películas en: " . $this->language->name;
    }

    public function create()
    {
        $this->title = "Language - Create";
        $this->subtitle = "Registro de nuevo lenguaje";
        $this->language = new Language();

        if(input::hasPost("language")){
            $params = Input::post("language");
            if($this->language->create($params)){
                Flash::valid("Se guardó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function edit($id)
    {
        $this->title = "Language - Edit";
        $this->subtitle = "Editar lenguaje";
        $this->language = (new Language)->find($id);

        if(input::hasPost("language")){
            $params = Input::post("language");
            if($this->language->update($params)){
                Flash::valid("Se actualizó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }
}
