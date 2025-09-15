<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanApplicationRequest;
use App\Http\Requests\UpdateLoanStatusRequest;
use App\Models\LoanApplication;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class LoanApplicationController extends Controller
{
    public function index(Request $request): Response
    {
        $q = LoanApplication::query();

        if ($search = $request->input('search')) {
            $q->where(function ($s) use ($search) {
                $s->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('vehicle_make', 'like', '%' . $search . '%')
                    ->orWhere('vehicle_model', 'like', '%' . $search . '%');
            });
        }

        if ($type = $request->input('vehicle_type')) $q->where('vehicle_type', $type);
        if ($status = $request->input('status')) $q->where('status', $status);

        $items = $q->orderBy('submitted_at', 'desc')->paginate(12)->withQueryString();

        return Inertia::render('applications/List', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return Inertia::render('applications/Apply');
    }

    public function store(LoanApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $app = LoanApplication::create(array_merge($data, ['submitted_at' => now()]));

        return to_route('applications.show', $app->id);
    }

    public function show($id): Response
    {
        $app = LoanApplication::findOrFail($id);
        return Inertia::render('applications/Detail', ['application' => $app->toArray()]);
    }

    public function updateStatus(UpdateLoanStatusRequest $request, $id): RedirectResponse
    {
        $app = LoanApplication::findOrFail($id);
        $val = $request->validated();
        $app->update(['status' => $val['status']]);
        return back();
    }

    public function destroy($id)
    {
        $app = LoanApplication::findOrFail($id);
        $app->delete();
        return back();
    }
}
