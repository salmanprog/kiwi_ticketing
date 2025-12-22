<?php

if (!function_exists('mapTicketName')) {
    function mapTicketName(array $ticket)
    {
        $slugMap = [
        ];

        if (isset($ticket['ticketSlug']) && isset($slugMap[$ticket['ticketSlug']])) {
            $ticket['ticketType'] = $slugMap[$ticket['ticketSlug']];
        }

        return $ticket;
    }
}
