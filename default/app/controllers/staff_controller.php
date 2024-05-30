<?php
class StaffController extends AppController {
    public function index()
    {
        $this->title = "Staff";
        $this->subtitle = "Miembros del staff";
        $this->staff = (new Staff())->find();
    }

    public function create()
    {
        $this->title = "Staff";
        $this->subtitle = "Registro de nuevo miembro del staff";
        $this->staff = new Staff();
        $this->address = new Address();

        if (input::hasPost("staff")) {
            $params = Input::post("staff");
            if(isset($params['password'])){
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            $this->staff = new Staff($params);
            if ($this->staff->create()) {
                Flash::valid("Se guardó nuevo miembro del staff");
                $this->address = $this->staff->getAddress();
                $params_address = Input::post("address");
                if($this->address->save($params_address)){
                    Flash::valid("Se guardó dirección del staff");
                } else {
                    Flash::error("Error al guardar dirección");
                }
            } else {
                Flash::error("Error al guardar nuevo miembro del staff");
            }
        }
    }

    public function show($id)
    {
        $this->title = "Staff";
        $this->staff = (new Staff())->find($id);
        $this->subtitle = "Miembro de staff: " . $this->staff->first_name . " " . $this->staff->last_name;
    }

    public function edit($id)
    {
        $this->title = "Staff";
        $this->subtitle = "Editar miembro del staff";
        $this->staff = (new Staff())->find($id);
        $this->address = $this->staff->getAddress();

        if (input::hasPost("staff")) {
            $params = Input::post("staff");
            if(isset($params['password'])){
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            $params_address = Input::post("address");
            if ($this->staff->update($params)) {
                Flash::valid("Se actualizó información del staff");
                if($this->address->update($params_address)){
                    Flash::valid("Se actualizó información de dirección");
                } else {
                    Flash::error("Error al guardar dirección");
                }
            } else {
                Flash::error("Error al guardar staff");
            }
        }
    }
}
?>