<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] ?? 'New User Report Notification' }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.9em; color: #666; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>New User Report Notification</h2>
    </div>

    <div class="content">
        <p>A new user report has been submitted with the following details:</p>

        <h3>Report Information</h3>
        <p><strong>Report ID:</strong> {{ $data['report']->id }}</p>
        <p><strong>Submitted at:</strong> {{ $data['report']->created_at->format('M d, Y H:i') }}</p>
        <p><strong>Status:</strong> <span style="color: {{ $data['report']->status === 'pending' ? '#ffc107' : '#28a745' }};">
            {{ ucfirst($data['report']->status) }}
        </span></p>

        <h3>Reporter Details</h3>
        <p><strong>Name:</strong> {{ $data['reporter']->name }}</p>
        <p><strong>Email:</strong> {{ $data['reporter']->email }}</p>

        <h3>Reported User Details</h3>
        <p><strong>Name:</strong> {{ $data['reported']->name }}</p>
        <p><strong>Email:</strong> {{ $data['reported']->email }}</p>

        <h3>Report Reason</h3>
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ $data['report']->reason }}
        </div>

        <a href="{{ route('admin.reports.show', $data['report']->id) }}" class="button">
            View Full Report Details
        </a>

        <p>Please review this report and take appropriate action.</p>
    </div>

    <div class="footer">
        <p>Thank you for maintaining our community standards!</p>
        <p>This is an automated message. Please do not reply directly to this email.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</div>
</body>
</html>
