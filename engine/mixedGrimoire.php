<?php // Code Convention: camelCase
    class mixedGrimoire {
        public static function castMagicalHeader(): string {
            // The asterisk (*) acts as our custom "split marker" where white turns to purple
            $grimoireLines = [
                "Many jobs list Impossible Stacks but * Are Understaffed",
                "Requiring to work under stress means * Poor Management",
                "A 'Competitive Salary' and no numbers is * A Red Flag",           
                "Asking for flexibility means * Overtime Assured",
                "Most Vacancies are written for * The Gutter.",
                "Cast a Resume that * Gets You Hired.",
                "The Dark Arts of * Standing Out.",
            ];

            // 2. Grab a random line from the array
            $randomLine = $grimoireLines[array_rand($grimoireLines)];

            // 3. Split the string at the asterisk
            $parts = explode('*', $randomLine);

            // 4. Return clean, scannable HTML
            if (count($parts) === 2) {
                $firstPart = trim($parts[0]);
                $secondPart = trim($parts[1]);
                return "{$firstPart} <span class=\"text-gradient\">{$secondPart}</span>";
            }

            // Fallback if no asterisk was found
            return $randomLine;
        }

        public static function SetPhrase(string $type = 'tagline'): string {
            // Array of taglines
            $data = [
                'tagline' => [
                    "Stop fighting with Word docs. PaperWitch uses a touch of code and a dash of style to brew professional PDFs.",
                    "When corporates decline your application we say: Up Yours!",
                    "A Scoped Resume is more effective than a general one.",
                    "Your Job Hunting Familiar.",
                    "Write. Enhance. Impress."
                ],
                'slogan' => [
                    "Most Vacancies are written for the Gutter.",
                    "Job Rejections Offends us. Papers Will Get Louder!",
                    "When Explicit Moments Meet Credibility.",
                    "The Dark Arts of Standing Out.",
                    "Resumes with Attitude."
                ],
                'cta' => [
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
    }