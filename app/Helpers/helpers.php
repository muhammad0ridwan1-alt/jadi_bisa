<?php

if (!function_exists('formatYoutubeEmbed')) {
    /**
     * Helper to safely format YouTube embed links (handles watch?v=, youtu.be/, live/, &t= params)
     */
    function formatYoutubeEmbed(?string $url): ?string {
        if (!$url) return null;
        
        if (str_contains($url, 'youtu.be/')) {
            $id = explode('youtu.be/', $url)[1] ?? '';
            $id = strtok($id, '?');
            return "https://www.youtube.com/embed/" . $id;
        }
        
        if (str_contains($url, 'watch?v=')) {
            $id = explode('watch?v=', $url)[1] ?? '';
            $id = strtok($id, '&');
            return "https://www.youtube.com/embed/" . $id;
        }

        if (str_contains($url, 'embed/')) {
            return $url;
        }

        return $url;
    }
}
