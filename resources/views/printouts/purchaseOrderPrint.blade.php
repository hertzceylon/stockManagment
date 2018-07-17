<!DOCTYPE html>
<html>
<head>
  <title>Purchase Order</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

</head>
<body onload="window.print();">
    <div class='text-center' style='width:500px; border: 1px solid #000; padding: 4px;'>
      <div class="row text-right" style='width:500px;'> print time: {{date("Y-m-d H:i:sa")}}</div> 
      <div class="row"><h3>Manuri Pharmacy</h3></div>
      <div class="row"><h4>Purchase Order</h4></div>
    </div>

    <div class='text-center' style='width:500px; border: 1px solid #000; padding: 4px;'>
      <div class="row">
        <div class='col-xs-5 text-left'>ID</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['purchaseOrder']->order_id}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Date</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['purchaseOrder']->order_date}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Supplier</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['purchaseOrder']->supplier_name}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Remark</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['purchaseOrder']->remarks}}</b></div>
      </div>
    </div>

    <div class='text-center' style='width:500px; height: 400px; border: 1px solid #000; padding: 4px;'>
      <table class="table table-striped table-hover table-center" cellspacing="0">
        <thead>
          <tr>
            <th class="text-center" style="border: 1px solid #dddddd;">#</th>
            <th class="text-center" style="border: 1px solid #dddddd;">Name</th>
            <th class="text-center" style="border: 1px solid #dddddd;">Order Type</th>
            <th class="text-center" style="border: 1px solid #dddddd;">QTY</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($data['prchase_order_entries']))
            @php
              $count = 0;
            @endphp
            @foreach($data['prchase_order_entries'] as $purchaseOrderEntry)
              <tr>
                <td class="text-center" style="border: 1px solid #dddddd;">{{++$count}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrderEntry->item_name}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrderEntry->order_type_name}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrderEntry->order_qty}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
</body>
</html>