<?php
class Customer extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('store', 'model: Store', 'fk: store_id');
        $this->belongs_to('address', 'model: Address', 'fk: address_id');

        $this->has_many('rentals','model: Rental','fk: customer_id');
        $this->has_many('payments','model: Payment','fk: customer_id');
        $this->has_many('filmComments', 'model: FilmComments', 'fk: user_id');

        $this->validates_presence_of('first_name',['message'=>'Debe ingresar un nombre']);

        $this->validates_presence_of('last_name',['message'=>'Debe ingresar un apellido']);

        $this->validates_email_in('email',['message'=>'El email no tiene un formato correcto']);

        /* $this->validates_presence_of('password',['message'=>'Debe ingresar una contraseña']);
        $this->validates_length_of('password',50,14,
            ['too_short'=>'La contraseña debe tener mínimo 14 caracteres']); */

        $this->validates_presence_of('store_id',['message'=>'Debe seleccionar una tienda']);
    }

    public function after_create()
    {
        $address = new Address();
        $address->city_id = 0;
        $address->create();

        $this->address_id = $address->id;
        $this->save();
    }

    /**
     * Recupera o crea un carro (instancia de Rental) para el cliente.
     *
     * @return Rental
     */
    public function getCart(){
        $cart = (new Rental)->find(
            'conditions: customer_id = '.$this->id.' AND payment_id IS NULL'
        );
        if(count($cart) === 0){
            $cart = new Rental();
            $cart->customer_id = $this->id;
            $cart->total = 0;
            $cart->create();
            return $cart;
        }elseif (count($cart) > 1) { //Hay más de un carrito por alǵun motivo
            $goodCart = array_pop($cart);
            foreach ($cart as $c):
                $c->delete();
            endforeach;
            return $goodCart;
        }
        return $cart[0];
    }

    /**
     * Revisa si una película ha sido adquirida por el cliente
     *
     * @param int $id ID de la película
     * @return bool
     */
    public function filmPurchased($id){
        $purchases = $this->getPurchases();
        foreach ($purchases as $p) {
            foreach ($p->getRentalItems() as $ri) {
                if ($ri->inventory_id === $id)
                    return true;
            }
        }
        return false;
    }

    /**
     * Recupera las compras realizadas por el cliente.
     *
     * @return array|bool
     */
    public function getPurchases(){
        return (new Rental)->find(
            'conditions: customer_id = '.$this->id.' AND payment_id IS NOT NULL'
        );
    }
}
