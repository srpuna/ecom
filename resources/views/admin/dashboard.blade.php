@extends('admin.layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
    </h2>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium uppercase">Total Orders</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">124</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-medium uppercase">Pending Inquiries</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">12</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium uppercase">Revenue</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">$4,500</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Recent Activity</h3>
        <p class="text-gray-500">No recent activity.</p>
    </div>
@endsection