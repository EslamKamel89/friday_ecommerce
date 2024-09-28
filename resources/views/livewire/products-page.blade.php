<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
        <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
            <div class="flex flex-wrap mb-24 -mx-3">
                <!-- Filters ------------------------------------------>
                <div class="w-full pr-2 lg:w-1/4 lg:block">
                    <!-- Category ------------------------------------------>
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                        <!-- Category title ------------------------------------------>
                        <h2 class="text-2xl font-bold dark:text-gray-400"> Categories</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <!-- Category title -->
                        <!-- Category item builder ------------------------------------------>
                        <ul>
                            <!-- Category foreach ------------------------------------------>
                            @foreach ( $categories as $category )
								<li class="mb-4" wire:key="{{'categoryFilter ' . $category->id}}">
									<label for="{{$category->slug}}" class="flex items-center dark:text-gray-400 ">
										<input type="checkbox" wire:model.live="selectedCategories" id="{{$category->slug}}"
											value="{{$category->id}}" class="w-4 h-4 mr-2">
										<span class="text-lg">{{$category->name}}</span>
									</label>
								</li>
							@endforeach
                            <!-- Category foreach -->
                        </ul>
                        <!-- Category item builder -->
                    </div>
                    <!-- Category -->
                    <!-- Brand ------------------------------------------>
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <!-- Brand title ------------------------------------------>
                        <h2 class="text-2xl font-bold dark:text-gray-400">Brand</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <!-- Brand title -->
                        <!-- Brand item builder ------------------------------------------>
                        <ul>
                            <!-- Brand item foreach ------------------------------------------>
                            @foreach ( $brands as $brand )
								<li class="mb-4" wire:key="{{'brandFilter ' . $brand->id}}">
									<label for="{{$brand->slug}}" class="flex items-center dark:text-gray-300">
										<input type="checkbox" wire:model.live="selectedBrands" id="{{$brand->slug}}"
											value="{{$brand->id}}" class="w-4 h-4 mr-2">
										<span class="text-lg dark:text-gray-400">{{$brand->name}}</span>
									</label>
								</li>
							@endforeach
                            <!-- Brand item foreach -->
                        </ul>
                        <!-- Brand item builder -->
                    </div>
                    <!-- Brand -->
                    <!-- Product status ------------------------------------------>
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Product Status</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            <li class="mb-4">
                                <label for="featured" class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" id="featured" wire:model.live="featured" value="1"
                                        class="w-4 h-4 mr-2">
                                    <span class="text-lg dark:text-gray-400">Featured</span>
                                </label>
                            </li>
                            <li class="mb-4">
                                <label for="sale" class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" id="sale" value="1" wire:model.live="onSale"
                                        class="w-4 h-4 mr-2">
                                    <span class="text-lg dark:text-gray-400">On Sale</span>
                                </label>
                            </li>

                        </ul>
                    </div>
                    <!-- Product status -->
                    <!-- Product price ------------------------------------------>
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Price</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <p class=" text-lg font-bold text-blue-400 ">
                            {{$price == null ? null : Number::currency( $price )}}
                        </p>
                        <div>
                            <input x-ref="slider" x-init="
                                setTimeout(function(){
                                $refs.slider.value = {{ $this->minPrice + 5 }} ;
                                } , 250) ;

                                " wire:model.live="price" type="range"
                                class="w-full h-1 mb-4 bg-blue-100 rounded appearance-none cursor-pointer"
                                max="{{$this->maxPrice}}" min="{{$this->minPrice}}" step="100">
                            <div class="flex justify-between ">
                                <span
                                    class="inline-block text-lg font-bold text-blue-400 ">{{Number::currency( $this->minPrice )}}</span>
                                <span
                                    class="inline-block text-lg font-bold text-blue-400 ">{{Number::currency( $this->maxPrice )}}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Product price -->
                </div>
                <!-- Filters -->
                <!-- Main Product Contaienr ------------------------------------------>
                <div class="w-full px-3 lg:w-3/4">
                    <!-- Sort filter ------------------------------------------>
                    <div class="px-3 mb-4">
                        <div
                            class="items-center justify-between hidden px-3 py-2 bg-gray-100 md:flex dark:bg-gray-900 ">
                            <div class="flex items-center justify-between">
                                <select wire:model.live="sortType" name="" id=""
                                    class="block w-40 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900">
                                    <option value="latest">Sort by latest</option>
                                    <option value="price">Sort by Price</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Sort filter -->
                    <!-- Product item builder------------------------------------------>
                    <div class="flex flex-wrap items-center ">

                        <!-- Product foreach------------------------------------------>
                        @foreach ( $products as $product )
							<div wire:key="{{'product ' . $product->id}}" class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3">
								<div class="border border-gray-300 dark:border-gray-700">
									<div class="relative bg-gray-200">
										<a href="/products/{{$product->slug}}" class="">
											<img src="{{asset( 'storage/' . collect( $product->images )->last() )}}" alt=""
												class="object-cover w-full h-56 mx-auto ">
										</a>
									</div>
									<div class="p-3 ">
										<div class="flex items-center justify-between gap-2 mb-2">
											<h3 class="text-xl font-medium dark:text-gray-400 h-14">
												{{ $product->name }}
											</h3>
										</div>
										<p class="text-lg ">
											<span
												class="text-green-600 dark:text-green-600">{{Number::currency( $product->price, 'USD' )}}</span>
										</p>
									</div>
									<div class="flex justify-center p-4 border-t border-gray-300 dark:border-gray-700">

										<a wire:click.prevent="addToCart({{$product->id}}); " href="#"
											class="text-gray-500 flex items-center space-x-2 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-300">
											<svg wire:loading.remove wire:target="addToCart({{$product->id}})"
												xmlns="http://www.w3.org/2000/svg" width="16" height="16"
												fill="currentColor" class="w-4 h-4 bi bi-cart3 " viewBox="0 0 16 16">
												<path
													d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
												</path>
											</svg>
											<span wire:loading.remove wire:target="addToCart({{$product->id}})">Add to
												Cart</span>
											<span wire:loading wire:target="addToCart({{$product->id}})">
												<x-common.spinner />
											</span>
										</a>

									</div>
								</div>
							</div>
						@endforeach
                        <!-- Product foreach-->
                    </div>
                    <!-- Product item builder-->
                    <!-- pagination ------------------------------------------>
                    <div class="flex justify-end mt-6">
                        {{ $products->onEachSide( 3 )->links() }}
                    </div>
                    <!-- pagi nation -->
                </div>
                <!-- Main Product Contaienr -->
            </div>
        </div>
    </section>

</div>