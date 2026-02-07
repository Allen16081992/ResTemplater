<?php
    function SetPhrase(string $type = 'tagline'): string {
        // Array of taglines
        $data = [
            'tagline' => [
                "Build an event-highlighting resume in minutes. Modern layouts, bold headers, and instant PDF export.",
                "When corporates decline your application we shall say: Up Yours!",
                "A Scoped Resume is more effective than a general one",
                "Write. Enhance. Impress."
            ],
            'slogan' => [
                "Most Vacancies are written for the Gutter.",
                "Job Rejections Offend us. Papers Will Get Louder!",
                "When Explicit Moments Meet Credibility.",
                "The Dark Arts of Standing Out.",
                "Your Job Hunting Familiar.",
                "Resumes with Attitude."
            ],
            'cta' => [
                "Begin Your Grimoire of Experience Here",
                "Try our Resume Scribing Tool",
                "Write Your Job Scroll Now",
                "Cast a New Resume Today" 
            ],
            'leave' => [
                "Fly Back to the Spellbound Nest",
                "Return to the Enchanted Realm",
                "Head Back to the Wizard's Den",
                "Journey to the Magical Home",
                "Back to the Magical World"
            ]
        ];
        // safety check: fallback to call-to-action
        if (!isset($data[$type])) { $type = 'cta'; }  
        return $data[$type][array_rand($data[$type])];
    }