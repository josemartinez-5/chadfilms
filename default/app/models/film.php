<?php
//De tabla film en sakila_web
//ORM: interactúa con la base de datos
class Film extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('language', 'model: Language', 'fk: language_id');
        $this->belongs_to('original_language', 'model: Language', 'fk: original_language_id');
        $this->has_many('filmCategories', 'model: FilmCategory', 'fk: film_id');
        $this->has_many('filmComments', 'model: FilmComments', 'fk: film_id');

        $this->validates_presence_of('release_year',['message'=>'Debe ingresar el año de lanzamiento']);
        $this->validates_length_of('release_year',4,0,
            ['too_long'=>'El año de lanzamiento debe estar en 4 o menos cifras']);

        $this->validates_presence_of('length',['message'=>'Debe ingresar la duración en minutos']);
        $this->validates_numericality_of('length',['message'=>'Duración inválida']);

        $this->validates_presence_of('rating',['message'=>'Debe ingresar el rating']);
        $this->validates_inclusion_in('rating',['G','PG','PG-13','R','NC-17','E','TV-14','TV-MA'],
            ['field'=>'El rating']);
    }

    /**
     * Guarda una película y sube su portada.
     *
     * @param array $data Arreglo con los datos de la película
     * @return boolean
     * @throws Exception
     */
    public function saveWithPhoto($data)
    {
        //Inicia la transacción
        $this->begin();
        //Intenta actualizar la película con los datos pasados
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
     * Sube y actualiza la portada de la película.
     *
     * @return boolean|null
     */
    public function updatePhoto()
    {
        //Intenta subir la foto que viene en el campo 'pic'
        if ($pic = $this->uploadPhoto('pic')) {
            //Modifica el campo image de film y lo intenta actualizar
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
        //Asignar directorio especial para portadas de películas
        $file->setPath(dirname($_SERVER['SCRIPT_FILENAME']) . '/img/upload/film-covers');
        //Le asignamos las extensiones a permitir
        $file->setExtensions(array('jpg', 'png', 'gif', 'jpeg'));
        //Intenta subir el archivo
        if ($file->isUploaded()) {
            //Lo guarda usando un nombre de archivo aleatorio y lo retorna.
            return $file->saveRandom();
        }
        //Si falla al subir
        return false;
    }

    /**
     * Añade una vista a la película
     *
     * @param string $user_id ID del usuario loggeado
     * @return bool
     */
    public function addView($user_id)
    {
        $this->views += 1;
        if(!$this->update()){
            return false;
        }
        $registroView = new FilmViews();
        $registroView->film_id = $this->id;
        $registroView->user_id = $user_id;
        if(!$registroView->create()){
            return false;
        }
        return true;
    }

    /**
     * Recupera los primeros 140 carácteres de la descripción de la película.
     *
     * @return string
     */
    public function getShortDescription()
    {
        if (strlen($this->description) <= 140){
            return $this->description;
        } else {
            return substr($this->description,0,140) . '...';
        }
    }
}