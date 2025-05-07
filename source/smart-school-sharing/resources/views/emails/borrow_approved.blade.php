<h2>Hello {{ $data['receiver']->name }},</h2>

<p>Your request to borrow <strong>{{ $data['item']->name }}</strong> has been <strong>approved</strong> by {{ $data['giver']->name }}.</p>

<p>Start Date: {{ $data['transaction']->start_date }}</p>
<p>End Date: {{ $data['transaction']->end_date }}</p>

<p>Please coordinate with the giver for pickup/delivery.</p>

<p>Thanks,<br>{{ config('app.name') }}</p>
