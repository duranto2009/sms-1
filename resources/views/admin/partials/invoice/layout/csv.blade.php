<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <title>Student Fee Report</title>
    <style>

        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}
        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #1eafd3;
            color: rgb(44, 44, 44);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-4 text-capitalize text-center" style="text-align: center">
                <h3>Student Fee Report</h3>
                <p>From: {{\Carbon\Carbon::parse($date[0])->format('d-M-Y')}} to {{\Carbon\Carbon::parse($date[1])->format('d-M-Y')}} </p>
                <p>Class: {{$class!=null?$class->name:'All Class'}}</p>
                <p>Status: {{$status != null?$status==1?'Paid':'Unpaid':'All'}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-light table-bordered table-hover" id="customers">
                    <thead class="table-primary">
                        <tr>
                            <th>Invoice No</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Invoice Title</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Creation Date</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{str_pad($invoice->id,8,'0',STR_PAD_LEFT)}}</td>
                            <td>{{$invoice->student->name}}</td>
                            <td>{{$invoice->class->name}}</td>
                            <td>{{$invoice->title}}</td>
                            <td>{{number_format($invoice->amount,2)}}</td>
                            <td>{{number_format($invoice->paidAmount,2)}}</td>
                            <td>{{$invoice->created_at->format('d-M-Y')}}</td>
                            <td>{{$invoice->created_at->format('d-M-Y')}}</td>
                            <td>{{$invoice->status==1?'paid':'unpaid'}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
