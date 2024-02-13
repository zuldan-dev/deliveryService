{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="{{ trans('backpack::base.dashboard') }}" icon="la la-home" :link="backpack_url('dashboard')" />
<x-backpack::menu-dropdown title="Users Settings" icon="la la-users-cog">
    <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
</x-backpack::menu-dropdown>
