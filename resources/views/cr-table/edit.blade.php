@extends('backend.layouts.app')

@section('title', __('Update Template'))

@section('content')
    <x-forms.patch :action="route('menu.cr-table.update', ['table_id' => $table['id']])" id="form_submit">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Template')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('menu.cr-table.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                @include('cr-table.includes.form')
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Ubah')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
