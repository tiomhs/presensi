<div>
    {{-- In work, do what you enjoy. --}}
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
                            {{-- <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <button type="button" class="btn btn-primary" wire:click="create">
                                <i class="ki-duotone ki-plus fs-2"></i>Add Events</button>
                            
                            </div> --}}
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
                                    <th class="min-w-125px text-center">Date</th>
                                    <th class="min-w-125px text-center">Role</th>
                                    <th class="min-w-125px text-center">Status</th>
                                    <th class="text-center min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            {{-- @dd($users) --}}
                            <tbody class="text-gray-600 fw-semibold">
                                {{-- @dd($events) --}}
                                @foreach($events as $event)
                                {{-- @dump($event) --}}
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
                                                <a href="../../demo8/dist/apps/user-management/users/view.html" class="text-gray-800 text-hover-primary mb-1">{{ $event->event->name }}</a>
                                                <span>{{ $event->event->location }}</span>
                                            </div>
                                            <!--begin::User details-->
                                        </td>

                                        <td class="text-center">
                                            <span class="badge badge-light-primary">{{ $event->event->date }}</span>
                                        </td>
                                        
                                        <td class="text-center">
                                            {{ $event->role->name }}
                                        </td>

                                        {{-- @dd($event->event) --}}
                                        {{-- @dd($event->eventComitte->status) --}}
                                        <td class="text-center">
                                            @if ($event->status)
                                                <span class="badge badge-light-success">Sudah Absen</span>
                                            @else
                                                <span class="badge badge-light-danger">Belum Absen</span>
                                            @endif
                                        </td>
                                        <td class="text-center {{ $event->status ? 'd-none': '' }}">
                                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    {{-- <a href="{{ route('dashboard.roles.edit', $role->id) }}" class="menu-link px-3">Edit</a> --}}
                                                    <a href="#" class="menu-link px-3" wire:click.prevent="absen({{ $event->event_id }})">Absen</a>
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
                                             {{-- {{ $events->links() }} --}}
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

    @push('scripts')
        <script>
            window.addEventListener('show-alert', function (event) {
                Swal.fire({
                    icon: event.detail[0].type,
                    title: event.detail[0].message,
                    position: 'center',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endpush
</div>
