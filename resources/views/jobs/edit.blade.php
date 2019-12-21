@extends('layouts.admin', ['crumbs' => [
    __('Jobs') => route('jobs.index'),
    __('Edit Job') => route('jobs.edit', $job)]
, 'title' => __('Edit Job') . ' (' . __($job->status) . ')'
, 'subtitle' => 'From: ' . $job->pickup_address . ' To: ' . $job->dropoff_address])
@section('content')
    <div class=" card col-md-12 mx-auto">
        {!! Form::model($job, array('route' => array('jobs.update', $job), 'method' => 'PUT', 'class' =>'form theme-form')) !!}
        <div class="card-body">
            @if (isset($items) && $items->count() > 0)
            <div class="table-responsive">
                <h5>Items</h5>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->item_description}}</td>
                            <td>{{$item->item_price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            @endif

            @if($job->status != 'completed')
            <div class="form-group row">
                <label for="instructions" class="col-sm-3 col-form-label">{{__('Instructions')}}</label>
                <div class="col-sm-9">
                    <textarea name="instructions" class="form-control" cols="4"></textarea>
                </div>
            </div>
            @else
                <div class="form-group row">
                    <label for="proof" class="col-sm-3 col-form-label">{{__('Proof')}}</label>
                    <div class="col-sm-9">
                        <a href="{{$job->proof}}" target="_blank">
                            <img src="{{$job->proof}}" alt="" height="200" style="border: 1px dotted black; border-radius: 5px;" />
                        </a>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="additional_note" class="col-sm-3 col-form-label">{{__('Additional Note')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="additional_note" class="form-control" value="{{$job->additional_note}}" @if($rating) disabled @endif >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="rate" class="col-sm-3 col-form-label">{{__('Rate Driver')}}</label>
                    <div class="col-sm-9">
                        @if($rating)
                            @for($i = 0; $i < $rating->rate; $i++)
                                <i class="fa fa-star text-success" style="font-size: 20px;"></i>
                            @endfor
                        @else
                            @component('components.rating-stars')@endcomponent
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="comment" class="col-sm-3 col-form-label">{{__('Comment on driver')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="comment" class="form-control" @if($rating) value="{{$rating->comment}}" disabled @endif >
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            <a href="{{route('jobs.index')}}" class="btn btn-light">{{__('Cancel')}}</a>
        </div>
        {!! Form::close() !!}
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("textarea[name=instructions]").val('{{$job->instructions}}');
        });
    </script>
@endsection
