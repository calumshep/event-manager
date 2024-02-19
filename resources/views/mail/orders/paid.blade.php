<x-mail::message>
# Your Tickets

Thank you for your recent order for {{ $event->name }}!

Your tickets are attached to this email. Please keep them somewhere safe.

We look forward to seeing you in November!

<x-mail::button :url="$url">
View Order
</x-mail::button>

Thanks,<br>
Scottish Ski Club
</x-mail::message>
