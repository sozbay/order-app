<?php

namespace App\Http\Controllers\Api;

use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        $orders = Order::with('orderItems')->where('user_id', $user)->get();
        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    public function create(Request $request)
    {

        $customerId = Auth::user()->id;
        $items = $request->input('items');
        $validateUser = Validator::make($request->all(),
            [
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1'

            ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                return response()->json([
                    'message' => 'Ürün bulunamadı'
                ], 404);
            }

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => $product->name . ' Ürün stokta yok'
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $order = new Order();
            $order->user_id = $customerId;
            $order->total = 0;
            $order->save();
            foreach ($items as $item) {
                $order_item = new OrderItem();
                $product = Product::find($item['product_id']);
                $order_item->order_id = $order->id;
                $order_item->product_id = $item['product_id'];
                $order_item->quantity = $item['quantity'];
                $order_item->unit_price = $product['price'];
                $order_item->total = $product['price'] * $item['quantity'];
                $order->total += $order_item->total;
                $order_item->save();

                // Product updated stock
                $product = Product::find($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }
            $order->save();
            DB::commit();
            $discount = $this->calculateDiscount($order);

            return response()->json([
                'message' => 'Sipariş oluşturuldu',
                'order' => $order,
                'discounts' => $discount['discounts'],
                'totalDiscount' => $discount['totalDiscount'],
                'discountedTotal' => $order->total - $discount['totalDiscount']
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Sipariş oluşturulamadı'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id != auth()->id()) {
            return response()->json(['error' => 'Bu siparişe erişme yetkiniz yok.'], 403);
        }
        $order->orderItems()->delete();
        $order->delete();
        return response()->json(['message' => 'Sipariş silindi.'], 200);
    }


    private function calculateDiscount(Order $order)
    {
        $totalPrice = $order->total;
        $discountAmount = 0;
        $totalDiscountAmount = 0;

        $discounts = Discount::all();
        $calculateDiscount = [];
        foreach ($discounts as $discount) {
            if ($discount->category_id && $discount->quantity && !$discount->free_item_count) {
                // Kategoriye ve miktar şartına bağlı indirim
                $categoryId = $discount->category_id;
                $quantity = $discount->quantity;
                $productCount = $order->orderItems()
                    ->whereHas('product', function ($query) use ($categoryId) {
                        $query->where('category', $categoryId);
                    })
                    ->sum('quantity');
                if ($productCount >= $quantity) {
                    $discountAmount += $this->calculatePercentageDiscount($order, $discount->discount_percent, $categoryId);

                }
            }

            if ($discount->min_order_amount && !$discount->category_id) {
                // Toplam tutara bağlı indirim
                $minOrderAmount = $discount->min_order_amount;
                if ($totalPrice >= $minOrderAmount) {
                    $discountAmount = $totalPrice * ($discount->discount_percent / 100);
                    $totalDiscountAmount += $discountAmount;
                    $calculateDiscount[] = [
                        "discountsAmount" => $discountAmount,
                        "discountReason" => $discount->reason,
                        "subTotal" => $order->total - $totalDiscountAmount
                    ];
                }


            }
            if ($discount->free_item_count) {
                // Ücretsiz ürün indirimi
                $categoryId = $discount->category_id;
                $quantity = $discount->quantity;
                $freeItemCount = $discount->free_item_count;
                $productCount = $order->orderItems()
                    ->whereHas('product', function ($query) use ($categoryId) {
                        $query->where('category', $categoryId);
                    })
                    ->sum('quantity');

                if ($productCount >= $quantity) {
                    $discountAmount = $this->calculateFreeItemDiscount($order, $freeItemCount, $categoryId);
                    $totalDiscountAmount += $discountAmount;
                    $calculateDiscount[] = [
                        "discountsAmount" => $discountAmount,
                        "discountReason" => $discount->reason,
                        "subTotal" => $order->total - $totalDiscountAmount
                    ];
                }
            }
        }

        return [
            'discounts' => $calculateDiscount,
            'totalDiscount' => $totalDiscountAmount
        ];
    }

    private function calculatePercentageDiscount($order, $discountPercent, $categoryId = null)
    {
        $discountedAmount = 0;

        $items = $order->orderItems;

        if ($categoryId) {
            $items = $order->orderItems()->whereHas('product', function ($query) use ($categoryId) {
                $query->where('category', $categoryId);
            })->get();
        }

        foreach ($items as $item) {
            $discountedAmount += $item->unit_price * ($discountPercent / 100);
        }

        return $discountedAmount;
    }

    private function calculateFreeItemDiscount($order, $freeItemCount, $categoryId = null)
    {
        $discountedAmount = 0;

        $items = $order->orderItems;
        if ($categoryId) {
            $items = $order->orderItems()->whereHas('product', function ($query) use ($categoryId) {
                $query->where('category', $categoryId);
            })->get();
        }

        $items = $items->sortBy('unit_price')->take($freeItemCount);

        foreach ($items as $item) {
            $discountedAmount += $item->unit_price;
        }

        return $discountedAmount;
    }

}

