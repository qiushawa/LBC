<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
$search = $request->input('search');
        $discounts = Discount::with('products')
            ->when($search, function ($query, $search) {
                return $query->where('discount_name', 'like', "%{$search}%")
                             ->orWhere('discount_description', 'like', "%{$search}%");
            })
            ->paginate(25);

        $totalDiscounts = Discount::count();
        $products = Product::where('launch_status', 'active')
                           ->select('product_id', 'product_name')
                           ->get();

        $discounts->appends(['search' => $search]);

        return view('admin.dashboard', compact('discounts', 'totalDiscounts', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'discount_name' => 'required|string|max:50',
                'discount_type' => 'required|in:percentage,fixed_amount',
                'discount_value' => 'required|integer|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'discount_description' => 'nullable|string|max:4096',
                'products' => 'nullable|array',
                'products.*' => 'exists:products,product_id',
            ]);

            $discount = Discount::create($validated);
            if (!empty($validated['products'])) {
                $discount->products()->sync($validated['products']);
            }

            return response()->json(['success' => true, 'message' => '折價新增成功！']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '伺服器錯誤：' . $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $discount = Discount::with('products')->findOrFail($id);
        if ($request->wantsJson()) {
            return response()->json([
                'discount_name' => $discount->discount_name,
                'discount_type' => $discount->discount_type,
                'discount_value' => $discount->discount_value,
                'start_date' => $discount->start_date,
                'end_date' => $discount->end_date,
                'discount_description' => $discount->discount_description ?? '',
                'products' => $discount->products->pluck('product_id')->toArray(),
            ]);
        }

        $products = Product::where('launch_status', 'active')->pluck('product_name', 'product_id');
        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    public function update(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);

            $validated = $request->validate([
                'discount_name' => 'required|string|max:50',
                'discount_type' => 'required|in:percentage,fixed_amount',
                'discount_value' => 'required|integer|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'discount_description' => 'nullable|string|max:4096',
                'products' => 'nullable|array',
                'products.*' => 'exists:products,product_id',
            ]);

            $discount->update($validated);
            $discount->products()->sync($validated['products'] ?? []);

            return response()->json(['success' => true, 'message' => '折價更新成功！']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '伺服器錯誤：' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $discount->delete();

            return redirect()->route('admin.discounts')->with('message', '折價刪除成功！');
        } catch (\Exception $e) {
            return redirect()->route('admin.discounts')->with('error', '折價刪除失敗，請稍後再試');
        }
    }
}
