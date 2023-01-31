@extends('backend.layouts.app')

@section('title', __('Create Field'))

@section('content')
    <div class="alert alert-danger header-message" role="alert" id="alert-validation">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div id="alert-message">
        </div>
    </div>
    <x-forms.post :action="route('menu.cr-field.preview-field', ['table_id' => $detail_form->id])" id="form_submit">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Field '.$detail_form->name)
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('menu.cr-table.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <button class="btn btn-sm btn-success" style="margin-bottom: 10px;" id="view-final-preview">@lang('Submit Seluruh Field-Field')</button>
                <div>
                    @foreach ($form as $key => $item)
                    <div class="form-group row" id="field-{{ $item['field'] }}">
                        <label for="{{ $item['field'] }}" class="col-md-2 col-form-label">{{ $item['label'] }}</label>
                        <div class="col-md-10">
                            @if ($item['type'] == 'dropdown')
                            <select id="inp_{{ $item['field'] }}" name="{{ $item['field'] }}" class="form-control">
                                @foreach ($item['source'] as $k => $v)
                                <option value="{{ $k }}" {{ old($item['field']) == $k ? 'selected' : '' }}>
                                    {{ $v }}
                                </option>
                                @endforeach
                            </select>
                            @elseif ($item['type'] == 'textarea')
                            <textarea name="{{ $item['field'] }}" id="{{ $item['field'] }}" cols="30" rows="10" class="form-control"></textarea>
                            @else
                            <input type="{{ $item['type'] }}" name="{{ $item['field'] }}" class="form-control" value="{{ old($item['field']) }}" />
                            @endif
                            @if (isset($item['info']))
                            <i>{{ $item['info'] }}</i>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" id="savepreview">
                    @lang('Simpan & Preview')
                </button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
    <!-- Modal -->
    <div class="modal fade" id="fieldModal" tabindex="-1" aria-labelledby="fieldModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fieldModalLabel">Field Preview</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" id="warning-message"></div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Type
                                </th>
                            </tr>
                        </thead>
                        <tbody id="preview-field">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit-preview-field">Submit</button>
                <button type="button" class="btn btn-primary" id="submit-final-preview">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('additional-scripts')
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('fieldModal'), {});

        $('#submit-final-preview').hide();
        $('#alert-validation').hide();
        $('#field-option_id').hide();
        $('#field-disabled_source').hide();

        $('.close-modal').on('click', function(){
            myModal.hide();
        });

        $('#inp_type').on('change', function(){
            val = $(this).val();
            
            if (val == 2) {
                $('#field-option_id').show();
                $('#field-data_type').hide();
            } else if (val == 3) {
                $('#field-option_id').hide();
                $('#field-data_type').hide();
            } else {
                $('#field-option_id').hide();
                $('#field-data_type').show();
            }
        });

        $('#inp_disabled').on('change', function(){
            val = $(this).val();
            
            if (val == 1) {
                $('#field-disabled_source').show();
            } else {
                $('#field-disabled_source').hide();
            }
        });

        $('#savepreview').on('click', function(e){
            e.preventDefault();
            
            url = $('#form_submit').attr('action');

            fieldName = $('[name="name"]').val();
            fieldSlug = $('[name="slug_name"]').val();
            fieldType = $('[name="type"]').val();
            fieldOrder = $('[name="order"]').val();

            postData = {
                'name'      : fieldName,
                'slug_name' : fieldSlug,
                'type'      : fieldType,
                'order'     : fieldOrder
            };

            $.ajax({
                url: url,
                method: 'POST',
                data: postData,
                dataType: 'JSON',
                success:function(response)
                {
                    $('#fieldModalLabel').text('Field Preview');
                    $('#submit-preview-field').show();
                    $('#submit-final-preview').hide();
                    warning_message = `<i class="fa fa-warning"></i> Perhatian! <br> Harap pastikan field sudah benar sebelum menekan tombol <span class="label">Submit</span>`;
                    $('#warning-message').html(warning_message);
                    $('#alert-validation').hide();
                    myModal.show();
                    $('#preview-field').html(response.fields);
                },
                error: function(err){
                    status = err.status;
                    message = "";
                    if (status == 422) {
                        if (typeof err.responseJSON.errors != 'undefined') {
                            error = err.responseJSON.errors;

                            $.each(error, function(key, val) {
                                message += val[0] + "<br>"; 
                            });
                        } else {
                            message = err.responseJSON.message;
                        }
                        
                        $('#alert-message').html(message);
                        $('#alert-validation').show();
                    } else if (status == 404) {
                        window.location.href = '{{ route("admin.dashboard") }}';
                    } else {
                        $('#alert-message').html('something wrong when save data');
                        $('#alert-validation').show();
                    }
                }
            });
        });

        $('#submit-preview-field').on('click', function(e) {
            e.preventDefault();

            fieldName = $('[name="name"]').val();
            fieldSlug = $('[name="slug_name"]').val();
            fieldRequired = $('[name="is_mandatory"]').val();
            fieldType = $('[name="type"]').val();
            fieldOption = $('[name="option_id"]').val();
            fieldDataType = $('[name="data_type"]').val();
            fieldDisabled = $('[name="disabled"]').val();
            fieldSource = $('[name="disabled_source"]').val();
            fieldOrder = $('[name="order"]').val();

            postData = {
                'name'      : fieldName,
                'slug_name' : fieldSlug,
                'is_mandatory' : fieldRequired,
                'type'      : fieldType,
                'option_id' : fieldOption,
                'data_type' : fieldDataType,
                'disabled'  : fieldDisabled,
                'disabled_source' : fieldSource,
                'order'     : fieldOrder,
                'table_id'  : '{{ $detail_form->id }}'
            };

            $.ajax({
                url: "{{ route('menu.cr-field.save-preview-field') }}",
                method: 'POST',
                data: postData,
                dataType: 'JSON',
                success:function(response)
                {
                    alert('Sukses Menambah Field');
                    $('#alert-validation').hide();
                    myModal.hide();
                    $('#form_submit').trigger("reset");
                },
                error: function(err){
                    $('#alert-message').html('something wrong when save data');
                    $('#alert-validation').show();
                }
            });
        });

        $('#view-final-preview').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('menu.cr-field.final-preview', ['table_id' => $detail_form->id]) }}",
                method: 'GET',
                dataType: 'JSON',
                success: function(response){
                    $('#fieldModalLabel').text('Konfirmasi');
                    $('#submit-preview-field').hide();
                    $('#submit-final-preview').show();
                    $('#warning-message').html("Apakah sudah selesai menambah field-field-nya?");
                    myModal.show();
                    $('#preview-field').html(response.fields);
                }
            });
        });

        $('#submit-final-preview').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('menu.cr-field.save-final-preview') }}",
                method: 'GET',
                data: {
                    table_id : "{{ $detail_form->id }}"
                },
                dataType: 'JSON',
                success: function(response){
                    window.location.href = "{{ route('menu.cr-table.index') }}";
                }
            })
        });
    </script>
@endpush