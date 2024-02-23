<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300&family=Roboto&display=swap");
@import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet');
body {
  font-family: 'Roboto';
  font-size: 12px;
  display: table-cell;
}

p {
  margin: 0;
}

.receipt-form {
  margin: auto;
  background: white;
  width: 48mm;
  max-width: 58mm;
  display: inline-block;
  position: relative;
}

.receipt-form .border-line {
  margin: 0;
}

.receipt-form .container .receipt-title {
  margin-bottom: 0.5em;
}

.receipt-form .container .receipt-title p {
  margin: 0;
  text-align: center;
}

.receipt-form .container .receipt-title .store-name {
  font-weight: bold;
}

.receipt-form .container ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.receipt-form .container .tin-number {
  text-decoration: underline;
}

.receipt-form .receipt-no .receipt-number {
  font-weight: bold;
}

.receipt-form table {
  width: 100%;
  margin-left: auto;
  border-spacing: 0;
}

.receipt-form table td:last-child {
  text-align: right;
}

.receipt-form table td {
  padding: 0px;
}

.receipt-form .receipt-summary table {
  margin-bottom: 1em;
}

.cashier-container {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
      -ms-flex-direction: row;
          flex-direction: row;
}

div.cashier-container {
  margin: 4px, 4px;
  padding: 4px;
  width: 1500px;
  height: 600px;
  overflow-x: auto;
  overflow-y: hidden;
  white-space: nowrap;
}

.cashier-container .order {
  margin: 0.5em;
  position: relative;
  width: 376px;
  min-height: 300px;
  max-height: 920px;
  padding: 1em;
  background: white;
  border-radius: 20px;
  -webkit-box-shadow: 10px 10px 5px blur rgba(0, 0, 0, 0.3);
          box-shadow: 10px 10px 5px blur rgba(0, 0, 0, 0.3);
}

.general-information {
  -ms-grid-columns: auto auto;
      grid-template-columns: auto auto;
  display: -ms-grid;
  display: grid;
  margin-bottom: 1em;
}

.cashier-receipt-no {
  background-color: rgba(255, 255, 255, 0.8);
  font-size: 30px;
  margin-top: auto;
  text-align: left;
  font-size: 15px;
}

.timer {
  text-align: right;
  padding-right: 1px;
}

.status {
  -ms-grid-rows: auto;
      grid-template-rows: auto;
  display: -ms-grid;
  display: grid;
}

.progress-container {
  text-align: center;
  position: relative;
  width: 108px;
  height: 60px;
  margin: auto;
  background-color: #F4C63D;
  padding-top: 1em;
  border-radius: 20px;
}

.progress-container .progress-number {
  font-size: 25px;
  font-weight: bold;
  color: White;
}

.progress-container .progress-information {
  color: white;
}

.materials {
  margin-bottom: 1em;
}

.buttons button {
  border-style: none;
  width: 97px;
  height: 49px;
  font-size: 20px;
  font-weight: bold;
  color: white;
}

.buttons .done {
  background-color: #EFEFEF;
}

.buttons .release {
  background-color: #00860A;
}

.buttons .print {
  background-color: #FFDC23;
}

.buttons .container {
  margin-left: 40px;
  margin-right: auto;
}

@media print {
  .hidden-print,
  .hidden-print * {
    display: none !important;
  }
}
/*# sourceMappingURL=main.css.map */
    </style>
</head>
<body>
    <?php
      $total = 0;
      $quantity = 0;
      $lineItems = 0;
    ?>
    <div id="receipt" class="receipt-form">
        <div id="container" class="container">
            <div class="receipt-title">
                <p class="store-name" id="storeName">{{strtoupper($orders[0]->store_name)}}</p>
                <p id="storeAddress">{{strtoupper($orders[0]->store_address)}}</p>
                @if($orders[0]->store_tin)
                <p>VAT REG TIN: <span class="tin-number" id="tinNumber">{{$orders[0]->store_tin}}</span></p>              
                @endif
            </div>
            <p class="border-line">------------------------------------------------------</p>
            <div class="receipt-no">
                <ul>
                    <li>RECEIPT NO#: <span class="receipt-number" id="receiptNumber">{{$orders[0]->order_ref}}</span></li>
                    <li>ENCODER: <span id="currentUser">ADMIN</span></li>
                </ul>
            </div>
            <p class="border-line">------------------------------------------------------</p>
            <div class="receipt-items">
                <table>

                    @foreach($orders as $order)
                    <?php
                    $rowTotal = $order->final_price * $order->quantity;
                    $total += $rowTotal;
                    $quantity = $quantity + $order->quantity;
                    $lineItems++;
                    ?>

                    <tr id="item-details">
                        <td id="itemPcs">{{$order->quantity}} {{ $order->quantity > 1 ? $inflector->pluralize($order->uom) : $order->uom}}</td>
                        <td>
                            <ul class="item-description" id="itemDescription">
                                <li>{{$order->code}}</li>
                                <li>{{$order->product_name}}</li>
                                <li class="sub-price">PHP{{number_format($order->final_price, 2)}}</li>
                            </ul>
                        </td>
                        <td class="itemPrice" id="totalPrice"> PHP{{number_format($rowTotal, 2)}}</td>
                    </tr>

                    @endforeach
                </table>
                <p class="border-line">------------------------------------------------------</p>
            <div class="total-due">
                <p>
                 </p>
                <table>
                    <tr>
                        <td>Line Items: <span id="lineItem">{{$lineItems}}</span> | 
                            Quantity: <span id="itemQty">{{$quantity}}</span> </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>TOTAL DUE</td>
                        <td id="totalDue"> PHP{{number_format($total, 2)}}</td>
                    </tr>
                </table>
            </div>
            <p class="border-line">------------------------------------------------------</p>
            <div class="receipt-summary">
                <table class="receipt-sum" >
                    <tr>
                        <td>RECEIPT NO. </td>
                        <td id="receiptNumber">{{$orders[0]->order_ref}}</td>
                    </tr>
                    
                    <tr>
                        <td>DAY/TIME</td>
                        <td id="currentDate">{{$orders[0]->created_at}}</td>
                    </tr>
                </table>
                <p class="border-line">------------------------------------------------------</p>
            </div>
            <div class="receipt-creator">
                <ul>
                    <li>Accredited POS Supplier (Department of Science and Technology)</li>
                    <li>Liwayway, Odiongan, Romblon</li>
                    @if($orders[0]->store_tin)
                    <li>TIN: {{$orders[0]->store_tin}}</li>  
                    @endif
                    
                </ul> 
            </div>
        </div>
    </div>
    <button onclick="generatePDF()" class="hidden-print" id="btnPr">Print</button>
    
    <script>
      function generatePDF(){
          window.print();
          
      }
    </script>
</body>
</html>