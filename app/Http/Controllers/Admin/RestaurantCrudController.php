<?php

namespace App\Http\Controllers\Admin;

use App\Models\Restaurant;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\{ListOperation,CreateOperation,UpdateOperation,DeleteOperation};
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RestaurantCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RestaurantCrudController extends CrudController
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
        CRUD::setModel(Restaurant::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/restaurant');
        CRUD::setEntityNameStrings('restaurant', 'restaurants');
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
        $this->crud->column('address')->type('text')
            ->label('Restaurant Address')->orderable(false)->after('name');
        $this->crud->column([
            'name' => 'dishes',
            'label' => 'Restaurant Dishes',
            'type' => 'closure',
            'escaped' => false,
            'function' => function ($entry) {
                $count = $entry->dishes->count();
                $url = backpack_url('restaurant/' . $entry->id . '/show');

                return '<a href="' . $url . '">' . $count . ' dishes</a>';
            },
        ])->after('address');
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
        $this->crud->column('address')->label('Restaurant Address')
            ->type('textarea')->after('name');
        $this->crud->column([
            'name' => 'dishes',
            'label' => 'Restaurant Dishes',
            'type' => 'closure',
            'escaped' => false,
            'function' => function ($entry) {
                $dishes = $entry->dishes;

                if ($dishes->isNotEmpty()) {
                    return $dishes->pluck('name')->implode(',<br/>');
                }

                return '';
            },
        ])->after('address');
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
            'name' => 'required|min:2',
        ]);
        $this->crud->field('name')->type('text')->label('Restaurant Name');
        $this->crud->field('address')->type('textarea')->label('Restaurant Address');
        $this->crud->field([
            'name' => 'dishes',
            'label' => 'Restaurant Dishes',
            'type' => 'select_multiple',
            'entity' => 'dishes',
            'attribute' => 'name',
            'model' => 'App\Models\Dish',
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
     * @return void
     */
    private function setupColumns(): void
    {
        $this->crud->column('id')->type('number')->label('ID');
        $this->crud->column('name')->type('text')->label('Restaurant Name');
        $this->crud->column('created_at')->type('datetime')->label('Created')->orderable(false);
        $this->crud->column('updated_at')->type('datetime')->label('Updated')->orderable(false);
    }
}
