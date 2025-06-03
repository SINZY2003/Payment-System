<!DOCTYPE html>
<html>
<head>
    <title>Payment Invoice</title>
</head>
<body>
    <h1>Payment Confirmation</h1>
    <p>Dear Customer,</p>
    <p>Thank you for your payment. Here are the details of your transaction:</p>
    <ul>
        <li><strong>Amount:</strong> ${{ number_format($charge->amount / 100, 2) }}</li>
        <li><strong>Description:</strong> {{ $charge->description }}</li>
        <li><strong>Transaction ID:</strong> {{ $charge->id }}</li>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::createFromTimestamp($charge->created)->toDateTimeString() }}</li>
    </ul>
    <p>If you have any questions, please do not hesitate to contact us.</p>
    <p>Sincerely,</p>
    <p>SinzoleDesigns</p>
</body>
</html>
