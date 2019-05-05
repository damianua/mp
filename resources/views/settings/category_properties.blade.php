<?php
/**
 * @var array $categories
 */
?>
@extends('layouts.app', [
    'pageTitle' => __('Associate catalog categories with product properties')
])

@section('content')
    <ul class="nav nav-pills nav-fill">
        @foreach($categories as $category)
            <li class="nav-item">
                <a class="nav-link {{$category['is_current'] ? 'active' : ''}}" href="{{ route('settings.category_properties', [$category['id']]) }}">
                    {{ $category['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection