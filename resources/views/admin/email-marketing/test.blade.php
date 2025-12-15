<!DOCTYPE html>
<html>
<head>
    <title>Email Marketing Test</title>
</head>
<body>
    <h1>Email Marketing Test Page</h1>
    <p>This is a test page to verify that Email Marketing routes are working.</p>
    
    <h2>Campaigns:</h2>
    @if(isset($campaigns) && $campaigns->count() > 0)
        <ul>
            @foreach($campaigns as $campaign)
                <li>{{ $campaign->name }} - {{ $campaign->status }}</li>
            @endforeach
        </ul>
    @else
        <p>No campaigns found.</p>
    @endif
    
    <h2>Stats:</h2>
    @if(isset($stats))
        <ul>
            <li>Total Campaigns: {{ $stats['total_campaigns'] ?? 0 }}</li>
            <li>Active Subscribers: {{ $stats['active_subscribers'] ?? 0 }}</li>
            <li>Total Emails Sent: {{ $stats['total_emails_sent'] ?? 0 }}</li>
            <li>Open Rate: {{ $stats['open_rate'] ?? 0 }}%</li>
        </ul>
    @else
        <p>No stats available.</p>
    @endif
</body>
</html>


















