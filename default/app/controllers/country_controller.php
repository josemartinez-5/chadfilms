<?php
class CountryController extends AppController {
    public function index()
    {
        $this->title = "Country";
        $this->subtitle = "Lista de países";
        $this->countries = (new Country())->find();
    }

    public function show($id)
    {
        $this->title = "Country";
        $this->country = (new Country())->find($id);
        $this->subtitle = "País: " . $this->country->country;
    }

    public function edit($id)
    {
        $this->title = "Country";
        $this->subtitle = "Editar país";
        $this->country = (new Country())->find($id);

        if(input::hasPost("country")){
            $params = Input::post("country");
            if($this->country->update($params)){
                Flash::valid("Se actualizó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function create()
    {
        $this->title = "Country";
        $this->subtitle = "Registro de nuevo país";
        $this->country = new Country();

        if(input::hasPost("country")){
            $params = Input::post("country");
            $country_new = new Country($params);
            if($country_new->save()){
                Flash::valid("Se guardó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }
}
?>
