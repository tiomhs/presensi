<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" class="form-control form-control-solid w-250px ps-13" placeholder="Search roles" wire:model.live.debounce.250ms="search" data-kt-search-element="input"/>
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <button type="button" class="btn btn-primary" wire:click="create">
                                <i class="ki-duotone ki-plus fs-2"></i>Add Role</button>
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::Group actions-->
                            <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                                <div class="fw-bold me-5">
                                <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected</div>
                                <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete Selected</button>
                            </div>
                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_users .form-check-input" />
                                        </div>
                                    </th>
                                    <th class="min-w-125px">Name</th>
                                    <th class="text-center min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            {{-- @dd($users) --}}
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach($roles as $role)
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
                                            {{-- @dump($role) --}}
                                            <!--begin::User details-->
                                            <div class="d-flex flex-column">
                                                <a href="../../demo8/dist/apps/user-management/users/view.html" class="text-gray-800 text-hover-primary mb-1">{{ $role->name }}</a>
                                                <span>{{ $role->name }}</span>
                                            </div>
                                            <!--begin::User details-->
                                        </td>

                                        <td class="text-center">
                                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    {{-- <a href="{{ route('dashboard.roles.edit', $role->id) }}" class="menu-link px-3">Edit</a> --}}
                                                    <a href="#" class="menu-link px-3" wire:click.prevent="edit({{ $role->id }})">Edit</a>
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
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->

                          {{--ini adalah pagination --}}
                        <div class="row align-items-center">
                            <!-- Select Jumlah Tampil -->
                            <div class="col-sm-12 col-md-5 mb-2 mb-md-0">
                                <div class="dataTables_length" id="kt_customers_table_length">
                                    <label class="d-flex align-items-center">
                                        <span class="me-2">Tampilkan</span>
                                        <select wire:model.live="perPage" name="kt_customers_table_length" aria-controls="kt_customers_table" class="form-select form-select-sm form-select-solid w-auto">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        <span class="ms-2">data</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers d-flex justify-content-md-end justify-content-center" id="kt_customers_table_paginate">
                                    <ul class="pagination mb-0">
                                        <li class="paginate_button page-item previous" id="kt_customers_table_previous">
                                             {{ $roles->links() }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>



    {{-- modal --}}
    <div class="modal fade" id="add_role_modal"
     tabindex="-1"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true"
     wire:key="modal-{{ $isEdit ? $roleId : 'create' }}">
          <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_user_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $isEdit ? 'Edit Role' : 'Add Role' }}</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Form-->
                    {{-- @dump($isEdit) --}}
                    <form id="kt_modal_add_user_form" class="form" wire:submit.prevent="{{ $isEdit ? 'update' : 'submit' }}" >
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                            <input type="hidden" name="role_id" wire:model="roleId" />
                            <!-- Name -->
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Name</label>
                               <input type="text" 
                                wire:model="name"
                                class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                placeholder="Name"
                               />

                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Actions -->
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">{{ $isEdit ? 'Update' : 'Submit' }}</span>
                            </button>
                        </div>
                    </form>

                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
    </div>


    @push('scripts')
        <script>
            window.addEventListener('close-modal', () => {
                const modalEl = document.getElementById('add_role_modal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
            
           window.addEventListener('open-modal', () => {
                setTimeout(() => {
                    const modalEl = document.getElementById('add_role_modal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl); // ← pakai getOrCreate biar nggak dobel
                    modal.show();
                }, 200); // kasih delay biar data Livewire udah kebaca
            });


            window.addEventListener('edit-ready', () => {
                setTimeout(() => {
                    const modalEl = document.getElementById('add_role_modal');
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }, 500); // kasih delay biar Livewire sempet render
            });


            window.addEventListener('refresh-form', () => {
                Livewire.dispatch('refresh');
            });


            Livewire.on('toast', ({ type, message }) => {
                toastr[type](message); // "type" bisa 'success', 'error', dll
            });
        </script>
    @endpush
</div>