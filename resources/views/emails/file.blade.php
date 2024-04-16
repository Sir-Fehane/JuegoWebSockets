<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Compra</title>
    <style>
        .receipt .container {
    display: block;
    width: 100%;
    background: #fff;
    max-width: 350px;
    padding: 25px;
    margin: 50px auto 0;
    box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }

    .receipt .receipt_header {
        padding-bottom: 40px;
        border-bottom: 1px dashed #000;
        text-align: center;
    }

    .receipt .receipt_header h1 {
        font-size: 20px;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .receipt .receipt_header h1 span {
        display: block;
        font-size: 25px;
    }

    .receipt .receipt_header h2 {
        font-size: 14px;
        color: #727070;
        font-weight: 300;
    }

    .receipt .receipt_header h2 span {
        display: block;
    }

    .receipt .receipt_body {
        margin-top: 25px;
    }

    .receipt table {
        width: 100%;
    }

    .receipt thead, tfoot {
        position: relative;
    }

    .receipt thead th:not(:last-child) {
        text-align: left;
    }

    .receipt thead th:last-child {
        text-align: right;
    }

    .receipt thead::after {
        content: '';
        width: 100%;
        border-bottom: 1px dashed #000;
        display: block;
        position: absolute;
    }

    .receipt tbody td:not(:last-child), tfoot td:not(:last-child) {
        text-align: left;
    }

    .receipt tbody td:last-child, tfoot td:last-child{
        text-align: right;
    }

    .receipt tbody tr:first-child td {
        padding-top: 15px;
    }

    .receipt tbody tr:last-child td {
        padding-bottom: 15px;
    }

    .receipt tfoot tr:first-child td {
        padding-top: 15px;
    }

    .receipt tfoot::before {
        content: '';
        width: 100%;
        border-top: 1px dashed #000;
        display: block;
        position: absolute;
    }

    .receipt tfoot tr:first-child td:first-child, tfoot tr:first-child td:last-child {
        font-weight: bold;
        font-size: 20px;
    }

    .receipt .date_time_con {
        display: flex;
        justify-content: center;
        column-gap: 25px;
    }

    .receipt .items {
        margin-top: 25px;
    }

    .receipt h3 {
        border-top: 1px dashed #000;
        padding-top: 10px;
        margin-top: 25px;
        text-align: center;
        text-transform: uppercase;
    }
    </style>
</head>
<body>
    <h1>Gracias por su compra!</h1>
    <div class="receipt">
    <div class="container">
    
    <div class="receipt_header">
    <h1>Ticket de Compra <span><br />{{}}</span></h1>
    <h2>{{}}</h2>
    </div>
    
    <div class="receipt_body">

        <div class="date_time_con" >
            <div class="date">{{$date}}</div>
        </div>

        <div class="items">
            <table>
        
                <thead>
                    <th>QTY</th>
                    <th>ITEM</th>
                    <th>AMT</th>
                </thead>
        
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Lorem ipsum</td>
                        <td>2.3</td>
                    </tr>

                    <tr>
                        <td>1</td>
                        <td>Lorem ipsum</td>
                        <td>2.3</td>
                    </tr>

                    <tr>
                        <td>1</td>
                        <td>Lorem ipsum</td>
                        <td>2.3</td>
                    </tr>

                    <tr>
                        <td>1</td>
                        <td>Lorem ipsum</td>
                        <td>2.3</td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td>32.1</td>
                    </tr>

                    <tr>
                        <td>Cash</td>
                        <td></td>
                        <td>32.1</td>
                    </tr>

                    <tr>
                        <td>Change</td>
                        <td></td>
                        <td>32.1</td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>


    <h3>Thank You!</h3>

</div>
</div>
</body>
</html>