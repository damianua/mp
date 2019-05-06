<?php
/**
 * @var \Illuminate\Support\ViewErrorBag $errors
 * @var array $categories
 * @var array $produtProperties
 */
?>
@extends('layouts.app', [
    'pageTitle' => __('Associate catalog categories with product properties')
])

@section('content')
    <ul style="font-size: 16px" class="nav nav-pills nav-fill">
        @foreach($categories as $category)
            <li class="nav-item">
                <a class="nav-link {{$category['is_current'] ? 'active' : ''}}" href="{{ route('settings.category_properties', [$category['id']]) }}">
                    {{ $category['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <form method="post" action="{{ route('settings.category_properties.associate', [$currentCategory['id']]) }}">
        @csrf
        <div class="mt-1" style="max-height: 675px; overflow-y: auto">
            <table class="table">
                <thead>
                <tr>
                    <th class="col-md-3">Название</th>
                    <th class="col-md-3 text-center">Сортировка</th>
                    <th class="col-md-1 text-center">Скрыть</th>
                    <th class="col-md-5"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($productProperties as $property)
                        <tr>
                            <td>{{ $property['name'] }}</td>
                            <td>
                                <input
                                    name="property[{{ $property['id'] }}][sort]"
                                    @if($property['is_hidden'])
                                        readonly = "readonly"
                                    @endif
                                    class="form-control offset-3 col-6 text-right"
                                    type="text"
                                    value="{{ $property['sort_value'] }}"
                                />
                            </td>
                            <td>
                                <input name="property[{{ $property['id'] }}][hide]" @if($property['hide_value']) checked="checked" @endif class="form-control" type="checkbox" />
                            </td>
                            <td class="align-middle" style="color: red">
                                @error('property.'.$property['id'].'.sort')
                                    {{ $message }}
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-1">
            <input class="btn btn-primary" type="submit" value="Сохранить">
            <input class="btn btn-outline-primary" type="reset" value="Отмена">
        </div>
    </form>
@endsection