<?php
class Address extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('city', 'model: City', 'fk: city_id');

        $this->validates_presence_of('address',['message'=>'Debe ingresar calle y número']);

        $this->validates_presence_of('community',['message'=>'Debe ingresar la colonia o comunidad']);

        $this->validates_presence_of('state',['message'=>'Debe ingresar el estado']);

        $this->validates_presence_of('postal_code',['message'=>'Debe ingresar el código postal']);
        $this->validates_numericality_of('postal_code',
            ['message'=>'El código postal solo contiene dígitos']);
        $this->validates_length_of('postal_code',5,5,
            ['too_long'=>'El código postal debe tener cinco cifras',
                'too_short'=>'El código postal debe tener cinco cifras']);
    }
    public function getCompleteAddress(){
        $addresses_sql = 'SELECT address.id, CONCAT(address.address,", C.P. ",address.postal_code) AS direccion';
        $addresses_sql .= ' FROM address WHERE address.address2 IS NULL UNION SELECT address.id,';
        $addresses_sql .= ' CONCAT(address.address,", ",address.address2,", C.P. ",address.postal_code) AS direccion';
        $addresses_sql .= ' FROM address WHERE address.address2 IS NOT NULL;';
        return (new Address())->find_all_by_sql($addresses_sql);
    }
}
?>