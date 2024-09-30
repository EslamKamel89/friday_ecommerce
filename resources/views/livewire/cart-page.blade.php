<div class="mx-auto w-full max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8">
    <div class="container mx-auto px-4">
        <h1 class="mb-4 text-2xl font-semibold">Shopping Cart</h1>
        <div class="flex flex-col gap-4 md:flex-row">
            <!-- Orders Section  --------------------------------------------------->
            <div class="md:w-3/4">
                <div
                    class="mb-4 overflow-x-auto rounded-lg bg-white p-6 shadow-md"
                >
                    <table class="w-full">
                        <!-- Orders table head  --------------------------------------------------->
                        <thead>
                            <tr>
                                <th class="text-left font-semibold">Product</th>
                                <th class="text-left font-semibold">Price</th>
                                <th class="text-left font-semibold">
                                    Quantity
                                </th>
                                <th class="text-left font-semibold">Total</th>
                                <th class="text-left font-semibold">Remove</th>
                            </tr>
                        </thead>
                        <!-- Orders table head  -->
                        <!-- Orders table Body  --------------------------------------------------->
                        <tbody>
                            @forelse ($cartItems as $cartItem)
                                <!-- Orders table row  --------------------------------------------------->
                                <tr
                                    wire:key="{{ "cartItem. " . $cartItem["product_id"] }}"
                                >
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img
                                                class="mr-4 h-16 w-16"
                                                src="{{ asset("storage/" . $cartItem["image"]) }}"
                                                alt="Product image"
                                            />
                                            <span class="font-semibold">
                                                {{ $cartItem["name"] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        {{ Number::currency($cartItem["unit_amount"]) }}
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <button
                                            wire:click="decrement({{$cartItem['product_id']}})"
                                                class="mr-2 rounded-md border px-4 py-2"
                                            >
                                                -
                                            </button>
                                            <span class="w-8 text-center">
                                                {{ $cartItem["quantity"] }}
                                            </span>
                                            <button
                                            wire:click="increment({{$cartItem['product_id']}})"
                                                class="ml-2 rounded-md border px-4 py-2"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        {{ Number::currency($cartItem["total_amount"]) }}
                                    </td>
                                    <td>
                                        <button
                                        wire:click="removeItem({{ $cartItem['product_id'] }})"
                                            class="rounded-lg border-2 border-slate-400 bg-slate-300 px-3 py-1 hover:border-red-700 hover:bg-red-500 hover:text-white"
                                        >
                                             <span wire:loading.remove wire:target="removeItem({{ $cartItem['product_id'] }})">Remove</span>
                                             <span  wire:loading wire:target="removeItem({{ $cartItem['product_id'] }})">
                                                Removing..
                                             </span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Orders table row  -->
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
                                        class="py-4 text-center text-2xl"
                                    >
                                        No items available in the cart!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <!-- Orders table Body  -->
                    </table>
                </div>
            </div>
            <!-- Orders Section  -->
            <!-- Summary Section  --------------------------------------------------->
            <div class="md:w-1/4">
                <div class="rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-lg font-semibold">Summary</h2>
                    <!-- Subtotal  --------------------------------------------------->
                    <div class="mb-2 flex justify-between">
                        <span>Subtotal</span>
                        <span>{{Number::currency($grandTotal)}}</span>
                    </div>
                    <!-- Subtotal  -->
                    <!-- Taxes  --------------------------------------------------->
                    <div class="mb-2 flex justify-between">
                        <span>Taxes</span>
                        <span>{{Number::currency(0)}}</span>
                    </div>
                    <!-- Taxes  -->
                    <!-- Shipping  --------------------------------------------------->
                    <div class="mb-2 flex justify-between">
                        <span>Shipping</span>
                        <span>{{Number::currency(0)}}</span>
                    </div>
                    <!-- Shipping  -->
                    <!-- Total  --------------------------------------------------->
                    <hr class="my-2" />
                    <div class="mb-2 flex justify-between">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{Number::currency($grandTotal)}}</span>
                    </div>
                    <!-- Total  -->
                    <!-- Checkout Button  --------------------------------------------------->
                     @if($cartItems)
                     <button
                     class="mt-4 w-full rounded-lg bg-blue-500 px-4 py-2 text-white"
                     >
                     Checkout
                    </button>
                    @endif
                    <!-- Checkout Button  -->
                </div>
            </div>
            <!-- Summary Section  -->
        </div>
    </div>
</div>
