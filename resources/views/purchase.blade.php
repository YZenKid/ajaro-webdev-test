@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Dashboard
                        </div>
                        <div class="col-6">
                            <button class="btn btn-primary float-right" type="button" data-toggle="modal" data-target="#exampleModal">New Purchase</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User Buyer</th>
                                <th>Transaction Code</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase as $item)
                                <tr>
                                    <td>{{$item['created_at']}}</td>
                                    <td>{{$item['user']['name']}}</td>
                                    <td>{{$item['trx_code']}}</td>
                                    <td>{{$item['total_price']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{url()->current()}}" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Purchase</h5>
                    <button class="add_field_button">Add More Fields</button>
                </div>
                <div class="modal-body">
                    <div class="input_fields_wrap">
                        <div class="form-group">
                            <label>Product</label>
                            <select name="product[]" class="form-control" id="">
                                @foreach ($product as $item)
                                    <option value="{{$item["id"]}}">{{$item["name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Supplier</label>
                            <select name="supplier[]" class="form-control" id="">
                                @foreach ($supplier as $item)
                                    <option value="{{$item["id"]}}">{{$item["name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity[]" class="form-control" placeholder="Number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var max_fields      = 10; //maximum input boxes allowed
            var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
            var add_button      = $(".add_field_button"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<hr><div class="form-group">'+
                            '<label>Product</label>'+
                            '<select name="product[]" class="form-control" id="">'+
                                '@foreach ($product as $item)'+
                                    '<option value="{{$item["id"]}}">{{$item["name"]}}</option>'+
                                '@endforeach'+
                            '</select>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label>Product</label>'+
                            '<select name="supplier[]" class="form-control" id="">'+
                                '@foreach ($supplier as $item)'+
                                    '<option value="{{$item["id"]}}">{{$item["name"]}}</option>'+
                                '@endforeach'+
                            '</select>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label>Quantity</label>'+
                            '<input type="number" name="quantity[]" class="form-control" placeholder="Number">'+
                        '</div>'); //add input box
                }
            });

            $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('div').remove(); x--;
            })
        });
    </script>
@endsection
