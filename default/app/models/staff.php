<?php
class Staff extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('store', 'model: Store', 'fk: store_id');
        $this->belongs_to('address', 'model: Address', 'fk: address_id');

        $this->validates_presence_of('first_name',['message'=>'Debe ingresar un nombre']);

        $this->validates_presence_of('last_name',['message'=>'Debe ingresar un apellido']);

        //Temporal
        $this->validates_presence_of('address_id',['message'=>'Debe seleccionar una dirección']);

        $this->validates_presence_of('store_id',['message'=>'Debe seleccionar una tienda']);

        $this->validates_email_in('email',['message'=>'El email no tiene un formato correcto']);

        $this->validates_presence_of('username',['message'=>'Debe ingresar un nombre de usuario']);

        /* $this->validates_length_of('password',40,14,
            ['too_short'=>'La contraseña debe tener mínimo 14 caracteres']); */
    }

    public function after_create()
    {
        $address = new Address();
        $address->city_id = 0;
        $address->create();

        $this->address_id = $address->id;
        $this->save();
    }
}