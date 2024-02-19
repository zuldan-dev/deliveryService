<?php

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\{ListOperation,CreateOperation,UpdateOperation,DeleteOperation};
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DriverCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DriverCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup(): void
    {
        CRUD::setModel(Driver::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/driver');
        CRUD::setEntityNameStrings('driver', 'drivers');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        $this->crud->column('id')->type('number')->label('ID');
        $this->crud->column('name')->type('text')->label('Driver Name');
        $this->crud->column('car')->type('text')->label('Car Model');
        $this->crud->column('contacts')->type('text')->label('Contacts')->orderable(false);
        $this->crud->column([
            'name' => 'revenue',
            'label' => 'Driver Income',
            'type' => 'number',
            'prefix' => '$',
            'decimals' => 2,
            'orderable' => false,
        ]);
        $this->crud->column('created_at')->type('datetime')->label('Created')->orderable(false);
        $this->crud->column('updated_at')->type('datetime')->label('Updated')->orderable(false);
        $this->crud->orderBy('id');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|min:2',
            'car' => 'required|min:2',
            'revenue' => 'required|numeric',
        ]);
        $this->crud->field('name')->type('text')->label('Driver Name');
        $this->crud->field('car')->type('text')->label('Car Model');
        $this->crud->field('contacts')->type('textarea')->label('Contacts');
        $this->crud->field('revenue')->type('number')
            ->label('Driver Income')->prefix('$')->default('15.00');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
