<?php
class StoreController extends AppController {
    public function index()
    {
        $this->title = "Store";
        $this->subtitle = "Lista de tiendas";
        $this->stores = (new Store())->find();
    }

    public function create()
    {
        $this->title = "Store";
        $this->subtitle = "Registro de nueva tienda";
        $this->store = new Store();
        $this->address = new Address();

        if(input::hasPost("store")){
            $params = Input::post("store");
            $this->store = new Store($params);
            if($this->store->create()){
                Flash::valid("Se guardó nueva tienda");
                $this->address = $this->store->getAddress();
                $params_address = Input::post("address");
                if($this->address->save($params_address)){
                    Flash::valid("Se guardó dirección de la tienda");
                } else {
                    Flash::error("Error al guardar dirección");
                }
            } else {
                Flash::error("Error al guardar nueva tienda");
            }
        }
    }

    public function show($id)
    {
        $this->title = "Store";
        $this->store = (new Store())->find($id);
        $this->subtitle = "Tienda: " . $this->store->nombre;
    }

    public function edit($id)
    {
        $this->title = "Store";
        $this->subtitle = "Editar tienda";
        $this->store = (new Store)->find($id);
        $this->address = $this->store->getAddress();

        if(input::hasPost("store")){
            $params = Input::post("store");
            $params_address = Input::post("address");
            if($this->store->update($params)){
                Flash::valid("Se actualizó información de la tienda");
                if($this->address->update($params_address)){
                    Flash::valid("Se actualizó información de dirección");
                } else {
                    Flash::error("Error al guardar dirección");
                }
            } else {
                Flash::error("Error al guardar tienda");
            }
        }
    }
}
