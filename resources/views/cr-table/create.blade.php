@extends('backend.layouts.app')

@section('title', __('Generate New Form'))

@section('content')
    <x-forms.post :action="route('menu.cr-table.store')" id="form_submit">
        <x-backend.card>
            <x-slot name="header">
                @lang('Generate New Form')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('menu.cr-table.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                @include('cr-table.includes.form')
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Buat')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
