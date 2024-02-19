<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dish;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\{ListOperation,CreateOperation,UpdateOperation,DeleteOperation};
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DishCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DishCrudController extends CrudController
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
        CRUD::setModel(Dish::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dish');
        CRUD::setEntityNameStrings('dish', 'dishes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        $this->setupColumns();
        $this->crud->column([
            'name' => 'restaurants',
            'label' => 'Restaurants',
            'type' => 'closure',
            'escaped' => false,
            'function' => function ($entry) {
                $count = $entry->restaurants->count();
                $url = backpack_url('dish/' . $entry->id . '/show');

                return '<a href="' . $url . '">In ' . $count . ' restaurants</a>';
            },
        ])->after('price');
        $this->crud->orderBy('id');
    }

    /**
     * Define what happens when the Show operation is loaded.
     *
     * @return void
     */
    protected function setupShowOperation(): void
    {
        $this->setupColumns();
        $this->crud->column([
            'name' => 'restaurants',
            'label' => 'Restaurants',
            'type' => 'closure',
            'escaped' => false,
            'function' => function ($entry) {
                $restaurants = $entry->restaurants;

                if ($restaurants->isNotEmpty()) {
                    return $restaurants->pluck('name')->implode(',<br/>');
                }

                return '';
            },
        ])->after('price');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation([
            'name' => 'required|min:3',
            'price' => 'required|numeric',
        ]);

        $this->crud->field('name')->type('text')->label('Dish Name');
        $this->crud->field('price')->type('number')->label('Dish Price')->prefix('$');
        $this->crud->field([
            'name' => 'restaurants',
            'label' => 'Restaurants',
            'type' => 'select_multiple',
            'entity' => 'restaurants',
            'attribute' => 'name',
            'model' => 'App\Models\Restaurant',
            'pivot' => true,
        ]);
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

    /**
     * Common columns definition
     *
     * @return void
     */
    private function setupColumns(): void
    {
        $this->crud->column('id')->type('number')->label('ID');
        $this->crud->column('name')->type('text')->label('Dish Name');
        $this->crud->column([
            'name' => 'price',
            'label' => 'Dish Price',
            'type' => 'number',
            'prefix' => '$',
            'decimals' => 2,
        ]);
        $this->crud->column('created_at')->type('datetime')->label('Created')->orderable(false);
        $this->crud->column('updated_at')->type('datetime')->label('Updated')->orderable(false);
    }
}
