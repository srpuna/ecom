@extends('admin.layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Product: {{ $product->name }}
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="col-span-2">
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Basic Information</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" value="{{ $product->name }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                        required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ $product->price }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Discount Price ($) (Optional)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ $product->discount_price }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2">
                </div>

                <!-- Shipping -->
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Shipping & Dimensions</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                    <input type="number" step="0.001" name="weight" value="{{ $product->weight }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                        required>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Length (cm)</label>
                        <input type="number" step="0.01" name="length" value="{{ $product->length }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Width (cm)</label>
                        <input type="number" step="0.01" name="width" value="{{ $product->width }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Height (cm)</label>
                        <input type="number" step="0.01" name="height" value="{{ $product->height }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2"
                            required>
                    </div>
                </div>

                <!-- Media -->
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Media & Details</h3>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Main Image</label>
                    <input type="file" name="main_image"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2">
                    @if($product->main_image)
                        <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ $product->main_image }}" target="_blank"
                                class="text-blue-500 hover:underline">View Image</a></p>
                    @endif
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border p-2">{{ $product->description }}</textarea>
                </div>

                <!-- Settings -->
                <div class="col-span-2 mt-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_order_now_enabled" id="is_order_now_enabled" {{ $product->is_order_now_enabled ? 'checked' : '' }}
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="is_order_now_enabled" class="ml-2 block text-sm text-gray-900">
                            Enable "Order Now" Button
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-6">If disabled, only "Inquire" button will be shown.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.products.index') }}"
                    class="bg-gray-200 text-gray-800 px-4 py-2 rounded mr-4">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">Update
                    Product</button>
            </div>
        </form>
    </div>
@endsection