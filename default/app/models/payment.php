<?php

class Payment extends ActiveRecord
{
    public function initialize()
    {
        $this->belongs_to('customer', 'model: Customer', 'fk: customer_id');
        //En realidad sólo debería corresponder a un registro de Rental
        $this->has_many('rentals', 'model: Rental', 'fk: payment_id');
    }
}
