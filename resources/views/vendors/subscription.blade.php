@extends('layouts.admin', ['crumbs' => [
    'Vendors' => route('vendors.index', 'all'),
    'Vendor Subscriptions' => route('vendors.subscription', $vendor->id)]
, 'title' => 'Vendor Latest Subscription'])
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($subscription, array('route' => array('vendors.updateSubscription', $vendor, $subscription), 'method' => 'PUT',  'class' => 'form theme-form')) !!}
                        <input type="hidden" name="vendor_id" value="{{$vendor->id}}">
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="from_date" class="col-sm-2 col-form-label">Subscribe From</label>
                                    <div class="col-sm-3">
                                        <input type="date" name="from_date" value="{{$subscription ? $subscription->from_date : ''}}" class="form-control btn-pill digits">
                                    </div>
                                    <label for="to_date" class="col-sm-2 col-form-label">Subscribe To</label>
                                    <div class="col-sm-3">
                                        <input type="date" name="to_date" value="{{$subscription ? $subscription->to_date : ''}}" class="form-control btn-pill digits">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="submit" value="Submit" class="btn btn-success btn-pill">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--</form>-->
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Vendor Previous Subscriptions</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">Subscribed From<b>2018-07-11</b> To <b>2019-07-10</b></li>
                        <li class="list-group-item">Subscribed From<b>2017-07-11</b> To <b>2018-07-10</b></li>
                        <li class="list-group-item">Subscribed From<b>2016-07-11</b> To <b>2017-07-10</b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
