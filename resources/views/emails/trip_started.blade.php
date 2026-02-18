<h2>Trip Started</h2>

<p>Hello,</p>

<p>The bus trip for {{ $child->name }} has started.</p>

<p>Trip: {{ $trip->name }}</p>

<p>You can track the bus using the link below:</p>

<a href="{{ url('/trips/'.$trip->id.'/map') }}">
    View Live Map
</a>

<p>Thank you.</p>
