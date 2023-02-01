<div>
    @foreach ($form as $key => $item)
    <div class="form-group row">
        @if ($item['type'] == 'alert')
        <div class="alert alert-{{ $item['class'] }} col-md-12">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            
            <h4>
                <i class="{{ $item['fontawesome_header_class'] }}"></i>&nbsp;{{ $item['header'] }}
            </h4>
            <?= $item['description'] ?>
            
        </div>
        @else
        <label for="{{ $item['field'] }}" class="col-md-2 col-form-label">{{ $item['label'] }}</label>
        <div class="col-md-10">
            @if ($item['type'] == 'dropdown')
            <select id="inp_{{ $item['field'] }}" name="{{ $item['field'] }}" class="form-control">
                @foreach ($item['source'] as $k => $v)
                @php
                    $selected = '';
                    if(isset($item['value'])){
                        if($k == $item['value']){
                            $selected = 'selected';
                        }
                    }
                @endphp
                <option value="{{ $k }}" {{ old($item['field']) == $k ? 'selected' : '' }} {{ $selected }}>
                    {{ $v }}
                </option>
                @endforeach
            </select>
            @else
            <input type="{{ $item['type'] }}" name="{{ $item['field'] }}" class="form-control" value="{{ ($action == 'insert') ? old($item['field']) : $item['value']; }}" {{ (isset($item['readonly'])) ? 'readonly' : '' }}/>
            @endif
            @if (isset($item['info']))
            <i>{{ $item['info'] }}</i>
            @endif
            @if ($action == 'edit' && $item['type'] == 'file')
               <br> File Sebelumnya : <a href="{{ url('files/'.$table['id'].".xlsx") }}">Download</a>
            @endif
        </div>
        @endif
    </div>
    @endforeach
</div>