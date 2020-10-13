<?php


namespace MaxShuai\CrudApi\Http\Controllers;

use MaxShuai\CrudApi\Http\Models\CrudPanel;
use MaxShuai\CrudApi\Http\Traits\ApiResponse;

class ApiCRUDController extends CrudController
{

    use ApiResponse;

    /**
     * @var CrudPanel
     */
    public $crud;

    public function index()
    {
        $data = $this->crud->model
            ->orderBy(request('sort', 'id'), request('order_by', 'asc'))
            ->paginate(request('per_page', '15'))
            ->appends(request()->except('page'));

        if ($this->crud->hasOperationSetting("resource")) {
            $resources = $this->crud->getOperationSetting("resource")::collection($data);
            $data = $resources->toResponse($this->crud->getRequest())->getData();
            $data->meta->columns =$this->crud->columns();
            return $this->success($data);
        }
        if (!is_array($data)) {
            $data = $data->toArray();
        }
        $data['columns'] = $this->crud->columns();

        return $this->success($data);
    }

    public function create()
    {
        return $this->success($this->crud->fields());
    }

    public function store()
    {
        $this->crud->validateRequest();
        $data = [];
        foreach ($this->crud->fields() as $field) {
            if (request()->has($field['name'])) {
                $data[$field['name']] = request($field['name']);
            }
        }

        $this->crud->model->create($data);
    }

    public function show($id)
    {
        $data    = $this->crud->model->findOrFail($id);
        $columns = $this->crud->columns();
        if ($this->crud->hasOperationSetting("resource")) {
            $r    = $this->crud->getOperationSetting("resource");
            $data = new $r($data);
        }
        return $this->success(compact("data", "columns"));
    }

    public function edit($id)
    {
        $data    = $this->crud->model->findOrFail($id);
        $fields = $this->crud->fields();
        if ($this->crud->hasOperationSetting("resource")) {
            $r    = $this->crud->getOperationSetting("resource");
            $data = new $r($data);
        }
        return $this->success(compact("data", "fields"));
    }

    public function update($id)
    {
        $this->crud->validateRequest();
        $data = [];
        foreach ($this->crud->fields() as $field) {
            if (request()->has($field['name'])) {
                $data[$field['name']] = request($field['name']);
            }
        }

        $entity = $this->crud->model->findOrFail($id);
        $entity->update($data);
    }

    public function destroy($id)
    {
        return $this->crud->model->destroy($id);
    }
}
