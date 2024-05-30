<?php
class CityController extends AppController {
    public function index()
    {
        $this->title = "City";
        $this->subtitle = "Lista de ciudades";
        $this->cities = (new City())->find();
    }

    public function edit($id)
    {
        $this->title = "City";
        $this->subtitle = "Editar ciudad";
        $this->city = (new City())->find($id);
        //echo '<pre>'; var_dump($this->city->image); echo '</pre>';

        if(input::hasPost("city")){
            $params = Input::post("city");
            if(($_FILES["pic"]["name"] && $this->city->saveWithPhoto($params)) ||
                $this->city->update($params)){
                Flash::valid("Se actualizó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function create()
    {
        $this->title = "City";
        $this->subtitle = "Registro de nueva ciudad";
        $this->city = new City();

        if(input::hasPost("city")){
            $params = Input::post("city");
            if(($_FILES["pic"]["name"] && $this->city->saveWithPhoto($params)) ||
                $this->city->save($params)){
                Flash::valid("Se guardó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function show($id)
    {
        $this->title = "City";
        $this->city = (new City())->find($id);
        $this->subtitle = "Ciudad: " . $this->city->city;
    }
}
