@extends('layouts.app', ['pageTitle' => __('Dashboard')])

@section('content')
    @if(Auth::user()->isAdmin())
        <div>
            <a href="{{ route('register') }}">{{ __('Add user') }}</a>
        </div>
    @endif
    @can('associateWithProperties', \App\Models\Category::class)
        <div>
            <a href="{{ route('settings.category_properties') }}">{{ __('Associate catalog categories with product properties') }}</a>
        </div>
    @endcan
@endsection
