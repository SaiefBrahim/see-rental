<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;

class UnitController extends Controller
{
    public function __construct(
        private Unit $unit
    ){}

    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $units = $this->unit->latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.unit.index',compact('units'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'unit_type' => 'required|unique:units',
        ]);

        $unit = $this->unit;
        $unit->unit_type = $request->unit_type;

        $unit->save();
        Toastr::success(translate('New Unit Type added'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $unit = $this->unit->find($id);

        return view('admin-views.unit.edit',compact('unit'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $unit = $this->unit->find($id);
        $request->validate([
            'unit_type' => 'required|unique:units,unit_type,'.$unit->id,
        ]);

        $unit->unit_type = $request->unit_type;
        $unit->save();

        Toastr::success(translate('Unit Type updated successfully'));
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $unit = $this->unit->find($id);
        $unit->delete();

        Toastr::success(translate('Unit Type removed'));
        return back();
    }
}
