<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Item Borrowing Request Notification</h2>
    </div>

    <div class="content">
        <p>Hello {{ $data['giver']->name }},</p>

        <p>You have received a new borrow request for your item:</p>

        <h3>Item Details</h3>
        <p><strong>Item Name:</strong> {{ $data['item']->name }}</p>
        <p><strong>Category:</strong> {{ $data['item']->category->name ?? 'N/A' }}</p>

        <h3>Borrower Information</h3>
        <p><strong>Name:</strong> {{ $data['receiver']->name ?? $data['transaction']->borrower_name }}</p>
        <p><strong>Contact:</strong> {{ $data['transaction']->contact_info }}</p>

        <h3>Borrowing Details</h3>
        <p><strong>From:</strong> {{ \Carbon\Carbon::parse($data['transaction']->start_date)->format('M d, Y') }}</p>
        <p><strong>To:</strong> {{ \Carbon\Carbon::parse($data['transaction']->end_date)->format('M d, Y') }}</p>
        <p><strong>Purpose:</strong> {{ $data['transaction']->purpose }}</p>
        @if($data['transaction']->message)
            <p><strong>Message:</strong> {{ $data['transaction']->message }}</p>
        @endif

        <p>Please log in to your account to review and respond to this request.</p>

        <a href="{{ route('transactions.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            Go to Dashboard
        </a>
    </div>

    <div class="footer">
        <p>Thank you for using our service!</p>
        <p>This is an automated message, please do not reply directly to this email.</p>
    </div>
</div>
</body>
</html>
