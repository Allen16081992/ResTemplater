<?php
    function SetPhrase(string $type = 'tagline'): string {
        // Array of taglines
        $data = [
            'tagline' => [
                "Cast Your Paper: Professional Resumes Made Easy",
                "Apply for Internship with Witchcrafter Papers",
                "Influence Recruiters with us: Start Witchcrafting!",
                "Empower Your Journey with an Appealing Resume",
                "Unlock Opportunities: Create Your Master Resume",
                "Improve the Chances of your Job Conquest",
                "A Loaded Resume for Hungry Recruiting Agencies",
                "Catch a Demotivated Recruiter's Eye: Strike with Visual",
                "Target 1 Job per Resume to land a Tactical Advantage"
            ],
            'slogan' => [
                "Most Vacancies are written poorly and belong in the Gutter.",
                "Internship rejections offends us. Papers Will Get Louder!",
                "The Dark Arts of Standing Out Begins Here",
                "Your Job Hunting Familiar",
                "Spells for Success, Cast in Paper",
                "Resumes with Attitude", 
                "Give HR Heads... Up!"
            ],
            'cta' => [
                "Craft a Resume for Internship Here",
                "Try our Resume Casting Tool",
                "Write Your Career Grimoire Now",
                "Witchcrafted Papers Appeal",
                "Cast a New Resume with Ease" 
            ]
        ];

        // safety check: fallback to tagline
        if (!isset($data[$type])) { 
            $type = 'cta'; 
        } 
        
        return $data[$type][array_rand($data[$type])];
    }