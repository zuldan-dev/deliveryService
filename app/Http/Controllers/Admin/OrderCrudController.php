<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\{ListOperation,CreateOperation,UpdateOperation,DeleteOperation};
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrderCrudController extends CrudController
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
        CRUD::setModel(Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('order', 'orders');
        $this->crud->removeButton('create');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
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
            'name' => 'dishes',
            'label' => 'Dishes',
            'type' => 'closure',
            'escaped' => false,
            'function' => function ($entry) {
                $count = $entry->dishes->count();
                $url = backpack_url('order/' . $entry->id . '/show');

                return '<a href="' . $url . '">' . $count . ' dishes</a>';
            },
        ])->after('driver_id');
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
            'name' => 'dishes',
            'label' => 'Dishes',
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
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation(): void
    {
        $order = Order::findOrFail(request()->route('id'));
        $attributes = in_array($order->status, [OrderStatusEnum::canceled->name, OrderStatusEnum::completed->name])
            ? ['disabled' => 'disabled'] : [];

        $this->crud->field([
            'name' => 'driver_id',
            'label' => 'Drivers',
            'type' => 'select',
            'entity' => 'driver',
            'attribute' => 'name',
            'model' => 'App\Models\Driver',
            'attributes' => $attributes,
        ]);
        $this->crud->field([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => OrderStatusEnum::getArray([OrderStatusEnum::pending->name]),
        ]);
    }

    /**
     * Common columns definition
     * @return void
     */
    private function setupColumns(): void
    {
        $this->crud->column('id')->type('number')->label('ID');
        $this->crud->column([
            'name' => 'user_id',
            'label' => 'User',
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User',
        ]);
        $this->crud->column([
            'name' => 'driver_id',
            'label' => 'Driver',
            'type' => 'select',
            'entity' => 'driver',
            'attribute' => 'name',
            'model' => 'App\Models\Driver',
        ]);
        $this->crud->column('status')->type('text')->label('Status');
        $this->crud->column('created_at')->type('datetime')->label('Created')->orderable(false);
        $this->crud->column('updated_at')->type('datetime')->label('Updated')->orderable(false);
    }
}
