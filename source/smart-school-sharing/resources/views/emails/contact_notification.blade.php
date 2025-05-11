<!DOCTYPE html>
<html>
<head>
    <title>New Contact Received</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h2>New Contact Form Submission</h2>

<p><strong>Name:</strong> {{ $data['contact']->name }}</p>
<p><strong>Email:</strong> {{ $data['contact']->email }}</p>
<p><strong>Message:</strong></p>
<p>{{ $data['contact']->message }}</p>

<p><strong>Submitted at:</strong> {{ $data['contact']->created_at->format('Y-m-d H:i:s') }}</p>

<p>You can view and manage this contact in the admin panel.</p>

<a href="{{ route('admin.contacts.index') }}" class="button" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View All Contacts</a>

<p>Or view this specific contact directly:</p>
<a href="{{ route('admin.contacts.show', $data['contact']->id) }}" class="button" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View This Contact</a>
</body>
</html>
