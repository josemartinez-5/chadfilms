<?php
class CategoryController extends AppController {
    public function index()
    {
        $this->title = "Category";
        $this->subtitle = "Lista de categorías";
        $this->categories = (new Category())->find();
    }

    public function edit($id)
    {
        $this->title = "Category";
        $this->subtitle = "Editar categoría";
        $this->category = (new Category())->find($id);

        if(input::hasPost("category")){
            $params = Input::post("category");
            if($this->category->update($params)){
                Flash::valid("Se actualizó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function create()
    {
        $this->title = "Category";
        $this->subtitle = "Registro de nueva categoría";
        $this->category = new Category();

        if(input::hasPost("category")){
            $params = Input::post("category");
            if($this->category->save($params)){
                Flash::valid("Se guardó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function show($id)
    {
        $this->title = "Category";
        $this->category = (new Category())->find($id);
        $this->subtitle = "Categoría: " . $this->category->name;
    }
}