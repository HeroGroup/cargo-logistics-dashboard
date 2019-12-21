@extends('layouts.admin', ['crumbs' => [
    'Settings' => route('settings.index')]
, 'title' => __('List of Setting Key Values')])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" onclick="addNewKey()">
                    <i class="fa fa-fw fa-plus"></i> Add Key
                </button>
            </div>

            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('#')}}</th>
                                <th scope="col">{{__('Key')}}</th>
                                <th scope="col">{{__('Value')}}</th>
                                <th scope="col">{{__('Placeholder')}}</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach($settings as $setting)
                                <tr id="{{$setting->id}}">
                                    <td>{{$setting->id}}</td>
                                    <td>{{$setting->setting_key}}</td>
                                    <td>
                                        <input type="text" class="form-control" value="{{$setting->setting_value}}" name="{{$setting->id}}_value">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{$setting->setting_placeholder}}" name="{{$setting->id}}_placeholder">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-secondary" name="{{$setting->id}}_submit" onclick="updateSetting('{{$setting->id}}')">Submit</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" name="{{$setting->id}}remove" onclick="removeSetting('{{$setting->id}}')">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    function removeSetting(key) {
        swal({
            title: "{{__('Are you sure you want to delete this item?')}}",
            text: "{{__('Be aware that deletion process is non-returnable')}}",
            icon: "warning",
            buttons: ["{{__('Cancel')}}", "{{__('Delete')}}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "settings/removeAjax",
                    method: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        key: key
                    }
                })
                    .done(function (response) {
                        if (response.success) {
                            $("#"+key).remove();
                            swal(JSON.parse(response).message, "", "success");
                        } else {
                            swal(JSON.parse(response).message, "", "warning");
                        }
                    });
            }
        });
    }

    function updateSetting(key) {
        let form = new FormData();
        form.append("_token", "{{csrf_token()}}");
        form.append("key", key);
        form.append("value", $("input[name="+key+"_value]").val());
        form.append("placeholder", $("input[name="+key+"_placeholder]").val());

        let settings = {
            "url": "settings/updateAjax",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };

        $.ajax(settings).done(function (response) {
            swal(JSON.parse(response).message, "", "success");
        });
    }

    let tempKey = "", tempValue = "", tempPlaceholder = "", newKey = false;
    function createSetting() {
        let form = new FormData();
        form.append("_token", "{{csrf_token()}}");
        form.append("key", tempKey);
        form.append("value", tempValue);
        form.append("placeholder", tempPlaceholder);

        let settings = {
            "url": "settings/createAjax",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };

        $.ajax(settings).done(function (response) {
            swal(JSON.parse(response).message, "", "success");
            newKey = false;
        });
    }

    function setNewKey(input) {
        tempKey = input.value;
    }

    function setNewValue(input) {
        tempValue = input.value;
    }

    function setNewPlaceholder(input) {
        tempPlaceholder = input.value;
    }

    function addNewKey() {
        if (!newKey) {
            newKey = true;
            let newChild = "<tr><td></td>" +
                "<td><input type='text' name='new_setting_key' class='form-control' onkeyup='setNewKey(this)'></td>" +
                "<td><input type='text' name='new_setting_value' class='form-control' onkeyup='setNewValue(this)'></td>" +
                "<td><input type='text' name='new_setting_placeholder' class='form-control' onkeyup='setNewPlaceholder(this)'></td>" +
                "<td><button type='button' class='btn btn-secondary' onclick='createSetting()'>Submit</button></td>" +
                "</tr>";
            $("#table-body").append(newChild);
        }
    }
</script>
@endsection
