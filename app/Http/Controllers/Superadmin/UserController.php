<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\UsersDataTable;
use App\Facades\UtilityFacades;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        if (Auth::user()->can('manage-user')) {
            return $dataTable->render('superadmin.users.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
// Agency address, phone, email, contact_person_name, country, city, state, zip_code, long, lat

// agency documents, wallet, bank details
    public function create()
    {
        if (Auth::user()->can('create-user')) {
            $roles              = Role::pluck('name', 'name');
            $domains            = Domain::pluck('domain', 'domain')->all();
            $databasePermission = UtilityFacades::getsettings('database_permission');
            return view('superadmin.users.create', compact('roles', 'domains', 'databasePermission'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create-user')) {
            try {
                \DB::beginTransaction();
                if (UtilityFacades::getsettings('domain_config') == 'on') {
                    $request->merge(['domains' => $request->domains . '.' . parse_url(env('APP_URL'), PHP_URL_HOST)]);
                }
                request()->validate([
                    'name'          => 'required|max:50',
                    'email'         => 'required|email|unique:users,email',
                    'password'      => 'same:confirm-password',
                    'domains'       => 'required|max:50|unique:domains,domain',
                    'country_code'  => 'required',
                    'dial_code'     => 'required',
                    'phone'         => 'required',
                    'contact_person_name' => 'required',
                    'address'       => 'required',
                    'country'       => 'required',
                    'city'          => 'required',
                    'county'         => 'required',
                    'zip_code'      => 'required',
                ]);
                $users                       = $request->all();
                $users['password']           = Hash::make($users['password']);
                $users['type']               = 'Agency';
                $users['email_verified_at']  = Carbon::now();
                $users['phone_verified_at']  = Carbon::now();
                $users['plan_id']            = 1;
                $users['country_code']       = $request->country_code;
                $users['dial_code']          = $request->dial_code;
                $users['phone']              = str_replace(' ', '', $request->phone);
                $users['address']            = $request->address;
                $users['created_by']         = Auth::user()->id;
                $users['contact_person_name'] = $request->contact_person_name;
                $users['country']            = $request->country;
                $users['city']               = $request->city;
                $users['county']              = $request->state;
                $users['zip_code']           = $request->zip_code;
                $user                        = User::create($users);
                $user->assignRole('Agency');
                if (UtilityFacades::getsettings('database_permission') == '1') {
                    $tenant     = Tenant::create([
                        'id'    => $user->id,
                    ]);
                    $domain     = Domain::create([
                        'domain'        => $request->domains,
                        'actual_domain' => $request->domains,
                        'tenant_id'     => $tenant->id,
                    ]);
                    $user->tenant_id   = $tenant->id;
                    $user->created_by  = Auth::user()->id;
                    $user->save();
                } else {
                    $tenant = Tenant::create([
                        'id'                    => $user->id,
                        'tenancy_db_name'       => $request->db_name,
                        'tenancy_db_username'   => $request->db_username,
                        'tenancy_db_password'   => $request->db_password,
                    ]);
                    $domain = Domain::create([
                        'domain'        => $request->domains,
                        'actual_domain' => $request->domains,
                        'tenant_id'     => $tenant->id,
                    ]);
                    $user->tenant_id    = $tenant->id;
                    $user->created_by   = Auth::user()->id;
                    $user->save();
                }
                \DB::commit();

                // tenant database setting store
                $plan   = Plan::where('id', '1')->first();
                tenancy()->initialize($user->tenant_id);
                $plans  = [
                    "plan_id"           => $plan->id,
                    "name"              => $plan->name,
                    "price"             => $plan->price,
                    "duration"          => $plan->duration,
                    "durationtype"      => $plan->durationtype,
                    "description"       => $plan->description,
                    "max_users"         => $plan->max_users,
                    "max_roles"         => $plan->max_roles,
                    "max_documents"     => $plan->max_documents,
                    "max_blogs"         => $plan->max_blogs,
                    "discount_setting"  => ($plan->discount_setting == 'on') ? 'on' : 'off',
                    "discount"          => $plan->discount_setting == 'on' ? $plan->discount : null,
                    "tenant_id"         => $plan->tenant_id,
                    "active_status"     => $plan->active_status,
                    "created_at"        => $plan->created_at,
                    "updated_at"        => $plan->updated_at,
                ];
                $planSetting = json_encode($plans);
                Setting::updateOrCreate(
                    ['key'      => 'plan_setting'],
                    ['value'    => $planSetting]
                );
                tenancy()->end();

                return redirect()->route('users.index')->with('success', __('User created successfully.'));
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with('errors', 'Please check database name, database user name and database password.');
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit-user')) {
            $user           = User::find($id);
            $roles          = Role::pluck('name', 'name');
            $domains        = Domain::pluck('domain', 'domain');
            $userDomain     = Domain::where('tenant_id', $user->tenant_id)->first();
            $userRole       = $user->roles->pluck('name', 'name');
            $plan           = Plan::pluck('name', 'id');

            // tenant database setting store
            tenancy()->initialize($user->tenant_id);
            $planSettings    = UtilityFacades::getsettings('plan_setting');
            tenancy()->end();

            return view('superadmin.users.edit', compact('user', 'roles', 'domains', 'userDomain', 'userRole', 'plan', 'planSettings'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit-user')) {
            $user     = User::find($id);
            $tenantId = Domain::where('tenant_id', $user->tenant_id)->first();
            if (UtilityFacades::getsettings('domain_config') == 'on') {
                $request->merge(['domains' => $request->domains . '.' . parse_url(env('APP_URL'), PHP_URL_HOST)]);
            }
            request()->validate([
                'name'          => 'required|max:50',
                'email'         => 'required|email|unique:users,email,' . $id,
                'domains'       => 'required|max:50|unique:domains,domain,' . $tenantId->id,
                'phone'         => 'required',
                'country_code'  => 'required',
                'dial_code'     => 'required',
            ]);
            $user               = User::find($id);
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->password     = Hash::make($request->password);
            $user->plan_id      = $request->plan_id;
            $user->country_code = $request->country_code;
            $user->dial_code    = $request->dial_code;
            $user->phone        = str_replace(' ', '', $request->phone);
            $user->save();
            $domain     = Domain::where('tenant_id', $user->tenant_id)->first();
            if ($domain) {
                $domain->domain         = $request->domains;
                $domain->actual_domain  = $request->domains;
                $domain->save();
            }
            tenancy()->initialize($user->tenant_id);
            $users                  = User::where('tenant_id', $user->tenant_id)->first();
            $users->name            = $request->name;
            $users->email           = $request->email;
            $users->password        = Hash::make($request->password);
            $users->plan_id         = $request->plan_id;
            $users->country_code    = $request->country_code;
            $users->dial_code       = $request->dial_code;
            $users->phone           = str_replace(' ', '', $request->phone);
            $users->save();
            tenancy()->end();

            // tenant database setting store
            $plan   = Plan::where('id', $request->plan_id)->first();
            tenancy()->initialize($user->tenant_id);
            $plans  = [
                "plan_id"           => $plan->id,
                "name"              => $plan->name,
                "price"             => $plan->price,
                "duration"          => $plan->duration,
                "durationtype"      => $plan->durationtype,
                "description"       => $plan->description,
                "max_users"         => $plan->max_users,
                "max_roles"         => $plan->max_roles,
                "max_documents"     => $plan->max_documents,
                "max_blogs"         => $plan->max_blogs,
                "discount_setting"  => ($plan->discount_setting == 'on') ? 'on' : 'off',
                "discount"          => $plan->discount_setting == 'on' ? $plan->discount : null,
                "tenant_id"         => $plan->tenant_id,
                "active_status"     => $plan->active_status,
                "created_at"        => $plan->created_at,
                "updated_at"        => $plan->updated_at,
            ];
            $planSetting    = json_encode($plans);
            Setting::updateOrCreate(
                ['key'      => 'plan_setting'],
                ['value'    => $planSetting]
            );
            tenancy()->end();

            return redirect()->route('users.index')->with('success', __('User updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete-user')) {
            $user  = User::find($id);
            if (Auth::user()->type == 'Super Admin') {
                $domain         = Domain::where('tenant_id', $user->tenant_id)->first();
                $requestDomain  = RequestDomain::where('email', $user->email)->first();
                if ($domain) {
                    $domain->delete();
                }
                if ($requestDomain) {
                    $requestDomain->delete();
                }
            }
            if ($user->id != 1) {
                $user->delete();
            }
            return redirect()->route('users.index')->with('success', __('User deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function impersonate($id)
    {
        if (Auth::user()->can('impersonate-user')) {
            $user           = User::find($id);
            $currentDomain  = $user->tenant->domains->first()->actual_domain;
            $redirectUrl    = '/home';
            $token          = tenancy()->impersonate($user->tenant, 1, $redirectUrl);
            return redirect("http://$currentDomain/tenant-impersonate/{$token->token}");
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function userStatus(Request $request, $id)
    {
        $user   = User::find($id);
        $input  = ($request->value == "true") ? 1 : 0;
        if ($user) {
            $user->active_status = $input;
            $user->save();
        }
        return response()->json([
            'is_success'    => true,
            'message'       => __('User status changed successfully.')
        ]);
    }
}
