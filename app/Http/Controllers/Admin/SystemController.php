<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;

class SystemController extends Controller
{
    public function __construct(
        private Admin $admin
    ){}

    /**
     * @return Application|Factory|View
     */
    public function settings(): View|Factory|Application
    {
        return view('admin-views.settings');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
        ], [
            'f_name.required' => translate('First name is required'),
            'l_name.required' => translate('Last name is required'),
        ]);

        $admin = $this->admin->find(auth('admin')->id());
        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->image = $request->has('image') ? Helpers::update('admin/', $admin->image, 'png', $request->file('image')) : $admin->image;
        $admin->save();
        Toastr::success(translate('Admin information updated successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);
        $admin = $this->admin->find(auth('admin')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success(translate('Admin password updated successfully'));
        return back();
    }
}
