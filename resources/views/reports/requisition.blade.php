<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Requisition Report</title>
    <style>
        table{
            width: 100%;
        }
        tr, th, td {
            border: 1px dotted black;
        }
    </style>
</head>
<body>
    <table class="table table-stripped">
        <thead>
            <tr>
                <th colspan="8" style="color: blue; font-size: 14pt">REQUISITION</th>
            </tr>
            <tr>
                <th colspan="8" style="color: grey; font-weight: 300">{{ config('app.name') }} </th>
            </tr>
            <tr>
                <td colspan="4">Requisition: {{ $requisition->requistion_number }}</td>
                <td colspan="4">Requested By: {{ $requisition->user->name }}</td>
            </tr>
            <tr>
                <td colspan="4">Date: {{ date('d/m/Y', strtotime($requisition->date)) }}</td>
                <td colspan="4">Notes: {{ $requisition->notes }}</td>
            </tr>
            <tr>
                <th>Item Code</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Requested Qty</th>
                <th>Approved Qty</th>
                <th>Issued Qty</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requisition->requisition_details as $line)
                <tr>
                    <td>{{ $line->item_code }}</td>
                    <td>{{ $line->description }}</td>
                    <td>{{ $line->unit }}</td>
                    <td>{{ $line->request_qty }}</td>
                    <td>{{ $line->approved_qty }}</td>
                    <td>{{ $line->issued_qty }}</td>
                    <td>{{ $line->status }}</td>
                    <td>{{ $line->notes }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</body>

</html>
