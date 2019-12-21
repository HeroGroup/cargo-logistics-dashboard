<div class="row">
    <div class="col-sm-12">
        <form method="post" action="{{route('areas.store')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-3">
                    {!! Form::select('countries', $countries->pluck('name', 'id')->toArray(), null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="area" placeholder="Type Area Name..." required>
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-fw fa-plus"></i> {{__('Add Area')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('List of areas')}}</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{__('Country')}}</th>
                        <th>{{__('Area')}}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="areas-list">
                    @foreach($areas as $area)
                        <tr>
                            <td>{{$area->country->name}}</td>
                            <td id="area-{{$area->id}}-name">{{$area->name}}</td>
                            <td>
                                <i class="fa fa-fw fa-pencil text-success" data-toggle="modal" data-target="#editModal-area-{{$area->id}}" title="Edit" style="cursor: pointer;"></i>
                                {{--<button class="btn btn-xs btn-outline-success-2x" type="button" data-toggle="modal" data-target="#editModal-area-{{$area->id}}"><i class="fa fa-fw fa-pencil"></i> Edit</button>--}}
                                &nbsp;
                                <i class="fa fa-fw fa-remove text-danger" onclick="deleteArea('{{$area->id}}')" title="Delete" style="cursor: pointer;"></i>
                                {{--<button class="btn btn-xs btn-outline-danger-2x" type="button" onclick="deleteArea('{{$area->id}}')"><i class="fa fa-fw fa-remove"></i> Delete</button>--}}
                                <form id="destroy-form-area-{{$area->id}}" method="post" action="{{route('areas.destroy', $area->id)}}" style="display:none">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                            </td>
                            <td>
                                <!-- Edit Modal -->
                                <div class="modal" id="editModal-area-{{$area->id}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">{{__('Edit Area')}}</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="area" id="area-{{$area->id}}-new-name" value="{{$area->name}}" placeholder="Area new name...">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <button type="button" class="btn btn-secondary" onclick="editArea('{{$area->id}}')">{{__('Confirm')}}</button>
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
    function editArea(id) {
        let newName = $("#area-"+id+"-new-name").val();
        if (!newName) {
            swal({"title": "Enter New Name", "icon": "warning"});
        } else {
            $.ajax({
                url: "{{route('areas.update')}}",
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    area: id,
                    name: newName
                }
            })
                .done(function (response) {
                    if (response.success) {
                        $("#area-"+id+"-name").html(newName);
                        $("#editModal-area-"+id).modal("hide");
                    } else {
                        swal({"title": "Unable to update!", "icon": "error"});
                    }
                });
        }
    }

    function deleteArea(id) {
        swal({
            title: "{{__('Are you sure you want to delete this item?')}}",
            text: "{{__('Be aware that deletion process is non-returnable')}}",
            icon: "warning",
            buttons: ["{{__('Cancel')}}", "{{__('Delete')}}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                document.getElementById('destroy-form-area-'+id).submit();
            }
        });
    }
</script>
