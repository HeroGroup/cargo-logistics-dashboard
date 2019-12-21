<form method="post" action="{{route('areas.country.store')}}">
    @csrf
    <div class="form-group row">
        <div class="col-sm-3">
            <input type="text" class="form-control" name="country" placeholder="Type Country Name..." required>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-secondary"><i class="fa fa-fw fa-plus"></i> {{__('Add Country')}}</button>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('List of countries')}}</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="countries-list">
                    @foreach($countries as $country)
                        <tr>
                            <td id="{{$country->id}}-name">{{$country->name}}</td>
                            <td>
                                <i class="fa fa-fw fa-pencil text-success" data-toggle="modal" data-target="#editModal-{{$country->id}}" title="Edit" style="cursor: pointer;"></i>
                                {{--<button class="btn btn-xs btn-outline-success-2x" type="button" data-toggle="modal" data-target="#editModal-{{$country->id}}"><i class="fa fa-fw fa-pencil"></i> Edit</button>--}}
                                &nbsp;
                                <i class="fa fa-fw fa-remove text-danger" title="Delete" onclick="deleteCountry('{{$country->id}}')" style="cursor: pointer;"></i>
                                {{--<button class="btn btn-xs btn-outline-danger-2x" type="button" onclick="deleteCountry('{{$country->id}}')"><i class="fa fa-fw fa-remove"></i> Delete</button>--}}
                                <form id="destroy-form-{{$country->id}}" method="post" action="{{route('areas.country.destroy', $country->id)}}" style="display:none">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                            </td>
                            <td>
                                <!-- Edit Modal -->
                                <div class="modal" id="editModal-{{$country->id}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">{{__('Edit Country')}}</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="country" id="{{$country->id}}-new-name" value="{{$country->name}}" placeholder="Country new name...">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <button type="button" class="btn btn-secondary" onclick="editCountry('{{$country->id}}')">{{__('Confirm')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function editCountry(id) {
        let newName = $("#"+id+"-new-name").val();
        if (!newName) {
            swal({"title": "Enter New Name", "icon": "warning"});
        } else {
            $.ajax({
                url: "{{route('areas.country.update')}}",
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    country: id,
                    name: newName
                }
            })
                .done(function (response) {
                    if (response.success) {
                        $("#"+id+"-name").html(newName);
                        $("#editModal-"+id).modal("hide");
                    } else {
                        swal({"title": "Unable to update!", "icon": "error"});
                    }
                });
        }
    }

    function deleteCountry(id) {
        swal({
            title: "{{__('Are you sure you want to delete this item?')}}",
            text: "{{__('Be aware that deletion process is non-returnable')}}",
            icon: "warning",
            buttons: ["{{__('Cancel')}}", "{{__('Delete')}}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                document.getElementById('destroy-form-'+id).submit();
            }
        });
    }
</script>
