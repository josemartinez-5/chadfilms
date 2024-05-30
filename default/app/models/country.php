<?php
class Country extends ActiveRecord{
    public function initialize()
    {
        $this->has_many('cities', 'model: City', 'fk: country_id');

        $this->validates_presence_of('country',['message'=>'Debe ingresar un nombre']);
    }
}
?>