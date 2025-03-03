<?php

namespace App\Scrapers\Interfaces;

interface MangaScraperInterface
{
    /**
     * Extract basic manga information
     * 
     * @return array Should contain at minimum: ['title', 'slug']
     */
    public function extractBasicInfo(): array;
    
    /**
     * Extract detailed manga information
     * 
     * @return array Containing detailed manga information
     */
    public function extractDetails(): array;
    
    /**
     * Extract manga genres
     * 
     * @return array List of genre names
     */
    public function extractGenres(): array;
}