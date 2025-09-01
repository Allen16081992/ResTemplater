<?php
    function Tagline() {
        // Array of taglines
        $taglines = [
            "Craft Your Future: Professional Resumes Made Easy",
            "Elevate Your Career with Tailored Resumes",
            "Job Conquest Begins with Us: Start Drafting!",
            "Empower Your Journey: Build Your Perfect Resume",
            "Unlock Opportunities: Create Your Impressive Resume",
            "Your Path to Success Starts Here: Forge Your Resume",
            "Your Resume, Your Way: Start Building Today",
            "Occupy That Job Offer by Creating A Resume Today!",
            "Aim Your Resume at 1 Job to Land A Tactical Advantage"
        ];

        $cta = [
            "Write Your Grimoire Today",
            "Cast Your Career Spell"
        ];

        // Randomly select a tagline
        return $selectedTagline = $taglines[array_rand($taglines)];
        echo htmlspecialchars($cta, ENT_QUOTES);
    }