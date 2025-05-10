@component('mail::message')
    # New User Report

    A new report has been submitted:

    - **Report ID:** {{ $report->id }}
    - **Reporter:** {{ $reporter->name }} ({{ $reporter->email }})
    - **Reported User:** {{ $reported->name }} ({{ $reported->email }})
    - **Reason:**
    {{ $report->reason }}

    @component('mail::button', ['url' => route('admin.reports.show', $report->id)])
        View in Admin Panel
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
