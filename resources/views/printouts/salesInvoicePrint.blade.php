<!DOCTYPE html>
<html>
  <head>
    <title>Sales Invoice</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">

      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
      <!-- Google Font -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

  </head>
  <body onload="window.print();">
    <div class='text-center' style='width:375px; border: 1px solid #000; padding: 4px;'>
      <div class="row text-right" style='width:375px;'> print time: {{date("Y-m-d H:i:sa")}}</div> 
      <div class="row"><h3>Manuri Pharmacy</h3></div>
      <div class="row"><h4>Sales Invoice</h4></div>
      <div class="row" style="font-size: 12px;">Tel : 000 0 000 000 / 000 0 000 000</div>
    </div>

    <div class='text-center' style='width:375px; border: 1px solid #000; padding: 4px;'>
      <div class="row">
        <div class='col-xs-5 text-left'>Date</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->invoice_date}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>ID</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->invoice_id}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Sub Amount</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->invoice_amount}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Discount</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->discount}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Total Amount</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->total_invoice_amount}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Payment Amount</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->payment}}</b></div>
      </div>

      <div class="row">
        <div class='col-xs-5 text-left'>Balance Amount</div>
        <div class='col-xs-6 text-left'>: <b>{{$data['salesInvoice']->balance_payment}}</b></div>
      </div>
    </div>

    <div class='text-center' style='width:375px; border: 1px solid #000; padding: 4px;'>
      <table class="table table-striped table-hover table-center" cellspacing="0">
        <thead>
          <tr>
            <th class="text-center" style="border: 1px solid #dddddd;">#</th>
            <th class="text-center" style="border: 1px solid #dddddd;">Name</th>
            <th class="text-center" style="border: 1px solid #dddddd;">Price</th>
            <th class="text-center" style="border: 1px solid #dddddd;">Invoice QTY</th>
            <th class="text-center" style="border: 1px solid #dddddd;">Amount</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($data['saleInvoiceEntries']))
            @php
              $count = 0;
            @endphp
            @foreach($data['saleInvoiceEntries'] as $saleInvoiceEntry)
              <tr>
                <td class="text-center" style="border: 1px solid #dddddd;">{{++$count}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->item_name}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->price}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->invoice_qty}}</td>
                <td class="text-center" style="border: 1px solid #dddddd;">{{($saleInvoiceEntry->invoice_qty) * $saleInvoiceEntry->price}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
        <tfoot>
          <tr>
            <th class="text-right" style="border: 1px solid #dddddd;" colspan="4">Total</th>
            <th class="text-right" style="border: 1px solid #dddddd;">{{$data['salesInvoice']->total_invoice_amount}}</th>
          </tr>
        </tfoot>
      </table>

      <div class='text-center' style='width:375px;' > Thank You Come Again..... ! </div>
      <br>
      <div class='text-center' style='width:375px; font-size: 12px;'>- System By Hertz Ceylon (PVT) Ltd , Tel : 011 4 346 774 -</div>
    </div>

  </body>
</html>