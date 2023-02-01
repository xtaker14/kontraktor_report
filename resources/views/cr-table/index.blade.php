@extends('backend.layouts.app')

@section('title', __('Manajemen Form'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Manajemen Form')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('menu.cr-table.create')"
                :text="__('Buat Form Baru')"
            />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.forms-table />
        </x-slot>
    </x-backend.card>
    <!-- Modal -->
    <div class="modal fade" id="indexCrTableModal" tabindex="-1" aria-labelledby="indexCrTableModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indexCrTableModalLabel"></h5>
                </div>
                <div class="modal-body" id="modal-content">
                    
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('additional-scripts')
<script>
    var myModal = new bootstrap.Modal(document.getElementById('indexCrTableModal'), {});

    $('.close-modal').on('click', function(){
        myModal.hide();
    });

    $('.view-data').on('click', function(e){
        e.preventDefault();
        
        id = $(this).attr('id');

        $.ajax({
            url: "{{ route('menu.cr-table.view-data') }}",
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function(response) {
                $('.modal-title').text('Form');
                table = response.table;
                fields = response.field;

                view = '<h3>Form</h3>';
                view += `<table class="table table-striped">
                            <tr>
                                <td>Kode Form</td>
                                <td>${table.slug}</td>
                            </tr>
                            <tr>
                                <td>Nama Form</td>
                                <td>${table.name}</td>
                            </tr>
                        </table>`;
                
                view += '<h3>Form</h3>';
                view += `<table class="table table-striped">
                            <tr>
                                <th>#</th>
                                <th>Nama Field</th>
                                <th>Kode Field</th>
                                <th>Type</th>
                            </tr>`;
                $.each(fields, function(k, v) {
                    view += `<tr>
                            <td>${(k + 1)}</td>
                            <td>${v.name}</td>
                            <td>${v.slug_name}</td>
                            <td>${v.type}</td>
                        </tr>`;
                });

                view += '</table>';
                $('#modal-content').html(view);
                myModal.show();
            }
        })
    });
</script>
@endpush