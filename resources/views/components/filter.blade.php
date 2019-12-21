<div style="margin: 10px 0;">
    <div class="float-left form-group row">
        {{--<label for="filter" class="col-sm-4 col-form-label">Filter: </label>--}}
        <div class="col-sm-12">
            <select name="filter" id="filter" class="form-control" style="margin-left: 30px;" onchange="refreshTable(this.value);">
                <option value="all">{{__("All $type")}}</option>
                <option value="unapproved">{{__('Unapproved')}}</option>
                <option value="approved">{{__('Approved')}}</option>
                <option value="inactive">{{__('Inactive')}}</option>
            </select>
        </div>
    </div>
    <div class="float-right" style="margin-right: 30px;">
        <a class="btn btn-primary" href="{{$routeAdd}}"><i class="fa fa-fw fa-plus"></i> {{__("Add $type")}}</a>
    </div>
</div>
