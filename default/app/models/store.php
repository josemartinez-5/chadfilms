<?php
class Store extends ActiveRecord{
    public function initialize()
    {
        $this->has_many('staff', 'model: Staff', 'fk: store_id');
        $this->belongs_to('address', 'model: Address', 'fk: address_id');

        $this->validates_presence_of('nombre',['message'=>'Debe ingresar un nombre']);

        $this->validates_numericality_of('cp',
            ['message'=>'El código postal solo contiene dígitos']);
        $this->validates_length_of('cp',5,5,
            ['too_long'=>'El código postal debe tener cinco cifras',
                'too_short'=>'El código postal debe tener cinco cifras']);

        //Temporal (?)
        $this->validates_presence_of('manager_staff_id',
            ['message'=>'Debe ingresar el ID del manager']);

        //Temporal
        $this->validates_presence_of('address_id',['message'=>'Debe seleccionar una dirección']);
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
?>