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
        <h2>Item Rejection Notification</h2>
    </div>

    <div class="content">
        <p>Hello {{ $data['user']->name }},</p>

        <p>We regret to inform you that your item <strong>{{ $data['item']->name }}</strong> has been <strong>rejected</strong>.</p>

        <h3>Reason for Rejection</h3>
        <p>{{ $data['reason'] }}</p>

        <p>Please click the link below to review and update your item:</p>
        <p>
            <a href="{{ route('items.edit', $data['item']->id) }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Edit Item
            </a>
        </p>

        <p>If you believe this was a mistake, please contact the admin team for clarification.</p>

        <p>Thanks,<br>{{ config('app.name') }}</p>
    </div>

    <div class="footer">
        <p>Thank you for using our service!</p>
        <p>This is an automated message, please do not reply directly to this email.</p>
    </div>
</div>
</body>
</html>
