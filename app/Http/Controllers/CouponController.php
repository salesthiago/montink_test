<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::paginate();
        return view('coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validate($request);
        $data['discount'] = str_replace('.', '', $data['discount']);
        $data['discount'] = str_replace(',', '.', $data['discount']);
        try {

            Coupon::create($data);
            return redirect()->route('coupons.index')->with('success', 'Cupom criado com sucesso');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        $this->validate($request);
        $data['discount'] = str_replace('.', '', $data['discount']);
        $data['discount'] = str_replace(',', '.', $data['discount']);
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->update($data);
            return redirect()->route('coupons.index')->with('success', 'Cupom atualizado com sucesso');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['success' => 'Cupom excluído com sucesso!']);
    }

    private function validate(Request $request)
    {
        return $request->validate([
            'code' => 'required',
            'deadline_at' => 'required',
            'type' => 'required|in:percent,amount',
            'discount' => 'required|regex:/^\d+(,\d{1,2})?$/'
        ]);
    }

    public function validateCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)
            ->where('deadline_at', '>=', now())
            ->where('active', true)
            ->first();

        if ($coupon) {
            return response()->json([
                'valid' => true,
                'coupon' => [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'discount' => $coupon->discount
                ]
            ]);
        }

        return response()->json(['valid' => false]);
    }
}
