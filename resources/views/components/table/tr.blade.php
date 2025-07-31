@props(['user'])

<div>
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="1" />
            </div>
        </td>
        <td class="d-flex align-items-center">
            <!--begin:: Avatar -->
            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <a href="../../demo8/dist/apps/user-management/users/view.html">
                    <div class="symbol-label">
                        {{-- <img src="{{ asset('storage/media/avatars/300-6.jpg') }}" alt="{{ $user->name }}" class="w-100" /> --}}
                    </div>
                </a>
            </div>
            <!--end::Avatar-->
            <!--begin::User details-->
            <div class="d-flex flex-column">
                <a href="../../demo8/dist/apps/user-management/users/view.html" class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
                <span>{{ $user->email }}</span>
            </div>
            <!--begin::User details-->
        </td>
        <td>
            <div class="badge badge-light fw-bold">{{ $user->nim }}</div>
        </td>
        <td>{{ $user->created_at }}</td>
        <td class="text-center">
            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3" wire:click="edit({{ $user->id }})">Edit</a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu-->
        </td>
    </tr>
</div>