<?php

if (!function_exists('mapTicketName')) {
    function mapTicketName(array $ticket)
    {
        $slugMap = [
            'non-participant-pass-6591' => 'Non-Participant Pass',
            'green-cabana-2427' => 'Green Adventure Pass',
            'black-cabana-6654' => 'Black Adventure Pass',
            'blue-cabana-5183' => 'Blue Adventure Pass',
            'dartseeinteractivedarts-6234' => 'Dartsee Interactive Darts',
            'archerylanes-4470' => 'Archery Lanes',
            'freefallexperience-1048' => 'Free Fall Experience',
            'ropescourseandziplines-8868' => 'Ropes Course And Zip Lines',
            'mountainexperienceviaferrata-1383' => 'Mountain Experience Via Ferrata'

        ];

        if (isset($ticket['ticketSlug']) && isset($slugMap[$ticket['ticketSlug']])) {
            $ticket['ticketType'] = $slugMap[$ticket['ticketSlug']];
        }

        return $ticket;
    }
}
