<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Waiver Preview</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #000;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #000;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature img {
            max-width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Waiver Form Details</h1>

    <table>
        <tr>
            <th>Order ID</th>
            <td>{{ $waiver->order_id }}</td>
        </tr>
        <tr>
            <th>QR Code</th>
            <td>{{ $waiver->qr_code }}</td>
        </tr>
        <tr>
            <th>Waiver Type</th>
            <td>{{ $waiver->waiver_type }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $waiver->email }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $waiver->name }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $waiver->dob }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $waiver->phone }}</td>
        </tr>
        <tr>
            <th>Street Address</th>
            <td>{{ $waiver->street_address }}</td>
        </tr>
        <tr>
            <th>City</th>
            <td>{{ $waiver->city }}</td>
        </tr>
        <tr>
            <th>State</th>
            <td>{{ $waiver->state }}</td>
        </tr>
        <tr>
            <th>Zip Code</th>
            <td>{{ $waiver->zip_code }}</td>
        </tr>
        <tr>
            <th>Parent Name</th>
            <td>{{ $waiver->parent_name }}</td>
        </tr>
        <tr>
            <th>Parent Address</th>
            <td>{{ $waiver->parent_address }}</td>
        </tr>
        <tr>
            <th>Parent Phone</th>
            <td>{{ $waiver->parent_phone }}</td>
        </tr>
    </table>

    @if($waiver->photo)
    <div class="signature">
        <p><strong>Signature: </strong></p>
        <img src="{{ url('uploads/waivers/' . $waiver->photo) }}" alt="Signature">
    </div>
@endif

</body>
</html>
