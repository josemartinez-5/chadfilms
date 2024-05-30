<?php

class CustomerController extends AppController
{
    public function index()
    {
        $this->title = "Customers";
        $this->subtitle = "Lista de clientes";
        $this->customers = (new Customer())->find();
    }

    public function create()
    {
        $this->title = "Customers";
        $this->subtitle = "Registro de nuevo cliente";
        $this->customer = new Customer();
        $this->address = new Address();

        if (input::hasPost("customer")) {
            $params = Input::post("customer");
            if(isset($params['password'])){
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            $this->customer = new Customer($params);
            if ($this->customer->create()) {
                Flash::valid("Se guardó nuevo cliente");
                $this->address = $this->customer->getAddress();
                $params_address = Input::post("address");
                if ($this->address->save($params_address)) {
                    Flash::valid("Se guardó dirección del cliente");
                }
                else{
                    Flash::error("Error al guardar dirección del cliente");
                }
            } else {
                Flash::error("Error al guardar nuevo cliente");
            }
        }
    }

    public function show($id)
    {
        $this->customer = (new Customer())->find($id);
        $this->title = "Customer";
        $this->subtitle = "Cliente: " . $this->customer->first_name . " " . $this->customer->last_name;;
    }

    public function edit($id)
    {
        $this->title = "Customer";
        $this->subtitle = "Editar cliente";
        $this->customer = (new Customer())->find($id);
        $this->address = $this->customer->getAddress();

        if (input::hasPost("customer")) {
            $params = Input::post("customer");
            if(isset($params['password'])){
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            $params_address = Input::post("address");
            if ($this->customer->update($params)) {
                Flash::valid("Se actualizó información del cliente");
                if($this->address->update($params_address)){
                    Flash::valid("Se actualizó información de dirección");
                } else {
                    Flash::error("Error al guardar dirección");
                }
            } else {
                Flash::error("Error al guardar cliente");
            }
        }
    }

    public function direccion_edit($id)
    {
        $this->title = "Customer";
        $this->customer = (new Customer())->find($id);
        $this->subtitle = "Editar dirección de: " . $this->customer->first_name . " " .
            $this->customer->last_name;
        $this->address = ($this->customer->address_id) ? $this->customer->getAddress() : new Address();

        if (input::hasPost("address")) {
            $params = Input::post("address");
            if ($this->customer->address_id) {
                if ($this->address->update($params)) {
                    Flash::valid("Se actualizó");
                } else {
                    Flash::error("Error al guardar en BD");
                }
            } else {
                if ($this->address->save($params)) {
                    $this->customer->address_id = $this->address->id;
                    if ($this->customer->update()) {
                        Flash::valid("Se actualizó");
                    } else {
                        Flash::error("Error al guardar en BD");
                    }
                }
            }
        }
    }

    public function checkout()
    {
        $this->title = "Checkout - ".$this->user['first_name'].' '.$this->user['last_name'];
        $this->subtitle = "BUY EVERYTHING NOW !!!";
        $this->customer = (new Customer)->find($this->user['id']);
        $this->cart = $this->customer->getCart();
        $this->films = $this->cart->getItems();

        if (Input::hasPost('remove')){
            View::select(NULL,NULL);
            $film_id = Input::post('remove')['item'];
            $filmToRemove =
                (new RentalItems)->find_first(
                    'conditions: rental_id = '.$this->cart->id.' AND inventory_id = '.$film_id);
            if ($filmToRemove->delete()){
                $this->cart = $this->customer->getCart();
                echo json_encode(
                    ["total" => $this->cart->total,
                        "count" => count($this->cart->getRentalItems())
                    ]);
            }
        }

        elseif (Input::hasPost('pay')){
            View::select(NULL,NULL);
            if ($this->cart->makePayment())
                echo "Success";
        }
    }

    public function purchases()
    {
        $this->title = "Purchases - ".$this->user['first_name'].' '.$this->user['last_name'];
        $this->subtitle = "YOU MUST BUY MORE !!!";
        $this->customer = (new Customer)->find($this->user['id']);
        $this->purchases = $this->customer->getPurchases();
    }
}
