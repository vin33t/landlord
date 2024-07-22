<?php

namespace App\DataTables\Admin;

use App\Facades\UtilityFacades;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->addColumn('role', function (User $user) {
                $out = '';
                if (!empty($user->getRoleNames())) {
                    foreach ($user->getRoleNames() as $v) {
                        $out = '<label class="badge rounded-pill bg-primary p-2 px-3">' . $v . '</label>';
                    }
                }
                return $out;
            })
            ->editColumn('email_verified_at', function (User $user) {
                if ($user->email_verified_at) {
                    return '<span class="badge rounded-pill bg-info p-2 px-3">' . __('Verified') . '</span>';
                } else {
                    return '<span class="badge rounded-pill bg-warning p-2 px-3">' . __('Unverified') . '</span>';
                }
            })
            ->editColumn('phone_verified_at', function (User $user) {
                if ($user->phone_verified_at) {
                    return '<span class="badge rounded-pill bg-info p-2 px-3">' . __('Verified') . '</span>';
                } else {
                    return '<span class="badge rounded-pill bg-warning p-2 px-3">' . __('Unverified') . '</span>';
                }
            })
            ->editColumn('active_status', function (User $user) {
                $checked = ($user->active_status == 1) ? 'checked' : '';
                $status   = '<label class="form-switch">
                             <input class="form-check-input chnageStatus" name="custom-switch-checkbox" ' . $checked . ' data-id="' . $user->id . '" data-url="' . route('user.status', $user->id) . '" type="checkbox">
                             </label>';
                return $status;
            })
            ->addColumn('action', function (User $user) {
                return view('admin.users.action', compact('user'));
            })
            ->rawColumns(['role', 'action', 'email_verified_at', 'phone_verified_at', 'active_status']);
    }

    public function query(User $model)
    {
        $user = Auth::user()->id;
        if (tenant('id') == null) {
            return   $model->newQuery()->select(['users.*', 'domains.domain'])
                ->join('domains', 'domains.tenant_id', '=', 'users.tenant_id')->where('type', 'Agency');
        } else {
            return $model->newQuery()->where('type', '!=', 'Agency')->where('created_by', $user);
        }
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __('_MENU_ entries per page'),
                "searchPlaceholder" => __('Search...'), "search" => ""
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }')
            ->parameters([
                "dom" =>  "
                               <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
                             <'dataTable-container'<'col-sm-12'tr>>
                             <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
                               ",
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-light-primary no-corner me-1 add_module', 'action' => " function ( e, dt, node, config ) {
                        window.location = '" . route('users.create') . "';
                   }"],
                    [
                        'extend' => 'collection', 'className' => 'btn btn-light-secondary me-1 dropdown-toggle', 'text' => '<i class="ti ti-download"></i> Export', "buttons" => [
                            ["extend" => "print", "text" => '<i class="fas fa-print"></i> Print', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                            ["extend" => "csv", "text" => '<i class="fas fa-file-csv"></i> CSV', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                            ["extend" => "excel", "text" => '<i class="fas fa-file-excel"></i> Excel', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                            ["extend" => "pdf", "text" => '<i class="fas fa-file-pdf"></i> PDF', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ],
                    ],
                    ['extend' => 'reset', 'className' => 'btn btn-light-danger me-1'],
                    ['extend' => 'reload', 'className' => 'btn btn-light-warning'],
                ],
                "scrollX" => true,
                "drawCallback" => 'function( settings ) {
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=tooltip]")
                      );
                      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                      });
                      var popoverTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=popover]")
                      );
                      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                        return new bootstrap.Popover(popoverTriggerEl);
                      });
                      var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                      var toastList = toastElList.map(function (toastEl) {
                        return new bootstrap.Toast(toastEl);
                      });
                }'
            ])->language([
                'buttons' => [
                    'create' => __('Create'),
                    'export' => __('Export'),
                    'print' => __('Print'),
                    'reset' => __('Reset'),
                    'reload' => __('Reload'),
                    'excel' => __('Excel'),
                    'csv' => __('CSV'),
                ]
            ]);
    }

    protected function getColumns()
    {

        return [
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('role')->title(__('Role')),
            Column::make('email_verified_at')->title(__('Email Verified Status')),
            Column::make('phone_verified_at')->title(__('Phone Verified Status')),
            Column::make('created_at')->title(__('Created At')),
            Column::make('active_status')->title(__('Status')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->width('20%'),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}