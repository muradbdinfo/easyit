<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #1a56db;">New Service Request</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr><td style="padding: 8px; font-weight: bold; width: 120px;">Name:</td><td style="padding: 8px;">{{ $serviceRequest->name }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Email:</td><td style="padding: 8px;">{{ $serviceRequest->email }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Phone:</td><td style="padding: 8px;">{{ $serviceRequest->phone }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Company:</td><td style="padding: 8px;">{{ $serviceRequest->company ?? 'N/A' }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Service:</td><td style="padding: 8px;">{{ $serviceRequest->service?->title ?? 'General' }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Budget:</td><td style="padding: 8px;">{{ $serviceRequest->budget_range ?? 'N/A' }}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Urgency:</td><td style="padding: 8px; text-transform: capitalize;">{{ $serviceRequest->urgency }}</td></tr>
    </table>
    <h3 style="margin-top: 20px;">Requirements:</h3>
    <div style="background: #f5f5f5; padding: 15px; border-radius: 5px;">{{ $serviceRequest->requirements }}</div>
    <p style="margin-top: 20px; color: #666; font-size: 12px;">Sent from Easy IT Website Service Request Form</p>
</body>
</html>
