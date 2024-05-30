<?php
class City extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('country', 'model: Country', 'fk: country_id');

        $this->validates_presence_of('city',['message'=>'Debe ingresar un nombre']);
    }

    public function before_save()
    {
        if ($this->id){
            $country = ((new City())->find($this->id))->getCountry();
            $country->no_ciudades -= 1;
            $country->update();
        }
    }

    public function after_save()
    {
        $country = $this->getCountry();
        $country->no_ciudades += 1;
        $country->update();
    }

    /**
     * Guarda una ciudad y sube su foto.
     *
     * @param array $data Arreglo con los datos de la película
     * @return boolean
     * @throws Exception
     */
    public function saveWithPhoto($data)
    {
        //Inicia la transacción
        $this->begin();
        //Intenta actualizar la ciudad con los datos pasados
        if ($this->save($data)) {
            //Intenta subir y actualizar la foto
            if ($this->updatePhoto()) {
                //Se confirma la transacción
                $this->commit();
                return true;
            }
        }
        //Si algo falla se regresa la transacción
        $this->rollback();
        return false;
    }

    /**
     * Sube y actualiza la foto de la ciudad.
     *
     * @return boolean|null
     */
    public function updatePhoto()
    {
        //Intenta subir la foto que viene en el campo 'pic'
        if ($pic = $this->uploadPhoto('pic')) {
            //Modifica el campo image de city y lo intenta actualizar
            $this->image = $pic;
            return $this->update();
        }
    }

    /**
     * Sube la foto y retorna el nombre del archivo generado.
     *
     * @param string $imageField Nombre de archivo recibido por POST
     * @return string|false
     */
    public function uploadPhoto($imageField)
    {
        //Usamos el adapter 'image'
        $file = Upload::factory($imageField, 'image');
        //le asignamos las extensiones a permitir
        $file->setExtensions(array('jpg', 'png', 'gif', 'jpeg'));
        //Intenta subir el archivo
        if ($file->isUploaded()) {
            //Lo guarda usando un nombre de archivo aleatorio y lo retorna.
            return $file->saveRandom();
        }
        //Si falla al subir
        return false;
    }
}
?>