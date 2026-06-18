"use strict";

(() => {
    // 2. Safely wraps your variables inside a private sandbox vault
    const customEnginePrivateToken = "XYZ123";

    document.addEventListener('DOMContentLoaded', () => {
        // ========================================================
        // 1. TEXT CONTROLLER (LIVE WORKBENCH MIRROR MODULE)
        // ========================================================
        const ghostName = document.getElementById('ghost-name');
        const ghostHeadline = document.getElementById('ghost-headline');
        const ghostExp = document.getElementById('ghost-exp');

        const paperName = document.getElementById('paper-name');
        const paperHeadline = document.getElementById('paper-headline');
        const paperExp = document.getElementById('paper-exp');

        /**
         * REFACTORED: Safe Single-Line Text Synchronizer
         * Prevents element tracking crashes during character cap thresholds.
         */
        const syncText = (source, target, isUpperCase = false, maxLen = 25) => {
            // Intercept and destroy the Enter key to protect layout continuity
            source.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                    
                    // Shift focus cleanly to the next workbench element down
                    const nextInput = source.closest('.glass-input').nextElementSibling?.querySelector('[contenteditable]');
                    if (nextInput) nextInput.focus();
                }
            });

            source.addEventListener('input', () => {
                let text = source.textContent; // Pull clean string metadata
                
                // Enforce structural boundaries safely
                if (text.length > maxLen) {
                    text = text.substring(0, maxLen);
                    source.textContent = text; 
                    
                    // Caret Restoration Gate (Protects your cursor from jumping backwards)
                    const range = document.createRange();
                    const sel = window.getSelection();
                    if (source.childNodes.length > 0) {
                        const textNode = source.childNodes[0];
                        range.setStart(textNode, Math.min(maxLen, textNode.length));
                        range.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                }

                if (text.trim() === '') {
                    target.textContent = ' ';
                    return;
                }
                
                target.textContent = isUpperCase ? text.toUpperCase() : text;
            });
        };

        /**
         * NEW MODULE: Multi-Line Rich String Synchronizer
         * Keeps structural spacing characters (\n) alive for FPDF mapping pipelines.
         */
        const syncMultiLineText = (source, target, maxLen = 250) => {
            source.addEventListener('input', () => {
                let text = source.textContent;

                if (text.length > maxLen) {
                    text = text.substring(0, maxLen);
                    source.textContent = text;
                    
                    const range = document.createRange();
                    const sel = window.getSelection();
                    if (source.childNodes.length > 0) {
                        const textNode = source.childNodes[0];
                        range.setStart(textNode, Math.min(maxLen, textNode.length));
                        range.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                }

                if (text.trim() === '') {
                    target.innerHTML = '&nbsp;';
                    return;
                }

                // Map clean newlines into web layout view breaks securely
                const sanitizedText = text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/\n/g, "<br>");

                target.innerHTML = sanitizedText;
            });
        };

        // Initialize updated interactive canvas hooks
        if (ghostName && paperName) syncText(ghostName, paperName, true, 25);
        if (ghostHeadline && paperHeadline) syncText(ghostHeadline, paperHeadline, false, 35); // Bumped headline cap slightly
        if (ghostExp && paperExp) syncMultiLineText(ghostExp, paperExp, 250); // Assigned to multi-line loop


        // ========================================================
        // 2. HERO DISPLAY LAYER FOUNDRY ENGINE
        // ========================================================
        const heroMainName = document.getElementById('hero-main-name');
        const heroMainHeadline = document.getElementById('hero-main-headline');
        const heroMainExp = document.getElementById('hero-main-exp');
        const heroMainSkills = document.getElementById('hero-main-skills');
        const heroMainEdu = document.getElementById('hero-main-edu');
        const heroSheet1 = document.getElementById('hero-sheet-1');
        const heroSheet2 = document.getElementById('hero-sheet-2');

        // Data Generation Repositories (Global Names)
        const firstNames = ["Alexander", "Morgan", "Elena", "Marcus", "Valerie", "Viktor", "Sylvia"];
        const lastNames = ["Vane", "Blackwood", "Thorne", "Kross", "Sterling", "Vance", "Arcane"];

        // 1. Group repositories into specialized sector pools
        const industrySectors = [
            {
                id: "tech",
                titles: ["SYSTEM ENGINEER", "FULL-STACK ARCHITECT", "INFRASTRUCTURE ENGINEER", "ROUTING ENGINEER"],
                experience: [
                    "Engineered high-throughput custom data pipelines. Rewrote legacy framework logic into native routines. Refitted automated architectures under zero-downtime.",
                    "Dismantled monolithic application spaghetti. Deployed ultra-clean vanilla routing structures that cut server response latencies in half.",
                    "Reverse-engineered proprietary black-box APIs to recover legacy system control. Optimized automated memory allocation and fortified backends."
                ],
                skills: [
                    ["Vanilla JS", "Native PHP", "Custom Routing", "SQL Optimization"],
                    ["Low-Level Engine", "DOM Manipulation", "Data Architecture", "Bare-Metal Dev"],
                    ["Memory Management", "API Remodeling", "Clean Logic", "Zero-Dependency"]
                ],
                education: [
                    "Self-Taught / Raw Production Forge (2018 - Present)",
                    "B.S. Computer Engineering & Systems Architecture",
                    "Advanced Academy of Low-Level Core Paradigms"
                ]
            },
            {
                id: "hospitality",
                titles: ["HEAD BARISTA", "CAFE OPERATIONS LEAD", "SENSORY QUALITY SPECIALIST"],
                experience: [
                    "Managed front-of-house operations in high-volume environments. Calibrated espresso extraction variables and maintained rapid service speed under pressure.",
                    "Optimized café inventory tracking and supply chains. Re-engineered store workflow layouts to clear ordering bottlenecks during morning peak hours."
                ],
                skills: [
                    ["Espresso Calibration", "Latte Art", "Inventory Tracking", "POS Systems"],
                    ["Customer Retention", "Sensory Analysis", "Workflow Optimization", "Team Leadership"]
                ],
                education: [
                    "Specialty Coffee Association (SCA) Professional Certification",
                    "Autonomous Hospitality Operations Training Lab",
                    "B.A. Food Sciences & Beverage Management"
                ]
            },
            {
                id: "hospitality_events",
                titles: ["EVENT EXPERIENCES CREW", "TERRACE HOSTESS & SERVER", "VIP GUEST RELATIONS"],
                experience: [
                    "Coordinated front-of-house logistics and guest management for high-capacity festivals and seasonal promotional events. Maintained rapid pace and fluid communication under strict operational timelines.",
                    "Managed high-volume table service and floor coordination at a premium city-center terrace. Streamlined order routing and point-of-sale entries to elevate customer satisfaction during peak holiday rushes.",
                    "Facilitated VIP ticketing, entry protocols, and backstage hospitality operations. Resolved immediate scheduling changes and attendee requests proactively to protect brand presentation."
                ],
                skills: [
                    ["Event Coordination", "Crowd Management", "Guest Check-In", "Logistics Support"],
                    ["High-Volume Service", "Order Routing Systems", "Cash & POS Handling", "Team Speed"],
                    ["VIP Hospitality", "Conflict Resolution", "Interpersonal Polish", "Problem Solving"]
                ],
                education: [
                    "B.A. International Hospitality & Event Management — In Progress",
                    "European Hospitality Academy (EHA) Service Foundations Certificate",
                    "Social Responsibility & Crowd Safety Management Workshop"
                ]
            },
            {
                id: "boutique_retail",
                titles: ["FASHION SALES ASSISTANT", "BRAND REPRESENTATIVE", "VISUAL MERCHANDISER"],
                experience: [
                    "Provided tailored style consultations and exceptional customer service within a premium boutique environment. Consistently met daily sales targets and built strong client retention through proactive engagement.",
                    "Represented high-street lifestyle brands during high-volume holiday campaigns. Orchestrated floor promotions, managed inventory replenishment cycles, and maintained brand presentation standards.",
                    "Executed floor-wide visual merchandising guidelines and window display transitions. Analyzed hot-spot tracking zones to strategically arrange product layouts and maximize foot-traffic conversion rates."
                ],
                skills: [
                    ["Styling & Clienteling", "Sales Conversion", "Inventory Management", "Point of Sale (POS)"],
                    ["Brand Ambassadorship", "Trend Analysis", "Loss Prevention", "Team Collaboration"],
                    ["Visual Merchandising", "Display Design", "Spatial Layouts", "Stockroom Optimization"]
                ],
                education: [
                    "B.A. Fashion Management & Retail Marketing — In Progress",
                    "Visual Display & Trend Forecasting Academy Workshop",
                    "Certified Retail Service & Customer Experience Specialist"
                ]
            }
            {
                id: "industrial",
                titles: ["MIG/TIG WELDER", "FABRICATION TECHNICIAN", "STRUCTURAL FITTER"],
                experience: [
                    "Interpreted blueprint schematics to execute complex industrial fabrications. Inspected welds for structural integrity under strict safety compliance regulations.",
                    "Operated heavy machinery and calibrated precise thermal tools. Maintained zero-defect metrics across high-volume production cycles."
                ],
                skills: [
                    ["MIG / TIG Welding", "Blueprint Interpretation", "Metal Fabrication", "Plasma Cutting"],
                    ["Metallurgy Basics", "Structural Fitting", "Precision Measurement", "Safety Compliance"]
                ],
                education: [
                    "Certified Welding Inspector (CWI) / AWS Track",
                    "Advanced Industrial Fabrication Academy",
                    "Vocational Technical Core Metallurgy Matrix"
                ]
            }
        ];

        // Engine Trackers
        let currentPhase = 'name'; // 'name' -> 'title' -> 'exp' -> 'skills' -> 'edu'
        let textIndex = 0;
        let skillIndex = 0;
        let loopTimeout = null;

        let activeIdentity = { name: "", title: "", exp: "", skills: [], edu: "" };
        let historicalResumes = [];

        /**
         * Pulls cohesive structures from a single selected industry sector
         * @returns {Object} Structured data card
         */
        function generateRandomIdentity() {
            const first = firstNames[Math.floor(Math.random() * firstNames.length)];
            const last = lastNames[Math.floor(Math.random() * lastNames.length)];
            
            // Step 1: Pick the sector first to lock in the industry context
            const selectedSector = industrySectors[Math.floor(Math.random() * industrySectors.length)];
            
            // Step 2: Extract random elements strictly from within that selected sector
            const randomTitle = selectedSector.titles[Math.floor(Math.random() * selectedSector.titles.length)];
            const randomExp = selectedSector.experience[Math.floor(Math.random() * selectedSector.experience.length)];
            const randomSkills = selectedSector.skills[Math.floor(Math.random() * selectedSector.skills.length)];
            const randomEdu = selectedSector.education[Math.floor(Math.random() * selectedSector.education.length)];
            
            return {
                name: `${first} ${last}`.toUpperCase(),
                title: randomTitle,
                exp: randomExp,
                skills: randomSkills,
                edu: randomEdu
            };
        }

        function updateBackgroundSheets() {
            const renderSheetData = (sheetElement, identityData) => {
                if (!sheetElement || !identityData) return;

                // 1. Name Allocation
                sheetElement.querySelector('.sheet-glass-header').innerHTML = 
                    `<h3 style="font-family: 'Inter', sans-serif; font-weight: 900; font-size: 0.82rem; color: rgba(255,255,255,0.75); margin:0; letter-spacing: -0.5px;">${identityData.name}</h3>`;
                
                // 2. Headline Allocation
                sheetElement.querySelector('.sheet-glass-headline').innerHTML = 
                    `<span style="display:inline-block; vertical-align:top;">${identityData.title}</span>`;
                
                // 3. Experience Allocation (Capped to clip dynamic container bounds)
                const cappedExp = identityData.exp.length > 75 ? identityData.exp.substring(0, 75) + "..." : identityData.exp;
                sheetElement.querySelector('.sheet-glass-exp').innerHTML = 
                    `<p style="font-size: 0.44rem; color: rgba(255,255,255,0.35); margin:0; font-family: sans-serif;">${cappedExp}</p>`;
                
                // 4. Dual-Column Skill Token Matrix Injection
                let skillsHTML = "";
                identityData.skills.forEach(skill => {
                    skillsHTML += `<div style="font-size: 0.44rem; font-weight: 600; color: rgba(255,255,255,0.5); width: calc(50% - 4px); display:flex; align-items:center;">• ${skill}</div>`;
                });
                sheetElement.querySelector('.sheet-glass-skills').innerHTML = skillsHTML;
                
                // 5. Education Allocation
                sheetElement.querySelector('.sheet-glass-edu').innerHTML = 
                    `<div style="font-size: 0.44rem; color: rgba(255,255,255,0.4); font-weight: 500; font-family: sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${identityData.edu}</div>`;
            };

            // Render previous generational passes down the visual hierarchy stack
            if (historicalResumes.length > 0 && heroSheet1) {
                renderSheetData(heroSheet1, historicalResumes[historicalResumes.length - 1]);
            }
            if (historicalResumes.length > 1 && heroSheet2) {
                renderSheetData(heroSheet2, historicalResumes[historicalResumes.length - 2]);
            }
        }

        function runFoundryEngine() {
            // Phase 1: Name Line String Tokening
            if (currentPhase === 'name') {
                if (textIndex < activeIdentity.name.length) {
                    heroMainName.innerHTML = `<h3 style="font-family: 'Inter', sans-serif; font-weight: 900; margin:0; font-size: 1.15rem; letter-spacing: -0.5px; line-height: 1.1; color: #1a1a1a;">${activeIdentity.name.substring(0, textIndex + 1)}</h3>`;
                    textIndex++;
                    loopTimeout = setTimeout(runFoundryEngine, 45);
                } else {
                    currentPhase = 'title';
                    textIndex = 0;
                    loopTimeout = setTimeout(runFoundryEngine, 200);
                }
            }
            // Phase 2: Professional Sub-Headings
            else if (currentPhase === 'title') {
                if (textIndex < activeIdentity.title.length) {
                    heroMainHeadline.innerHTML = `<span style="display:inline-block; vertical-align:top;">${activeIdentity.title.substring(0, textIndex + 1)}</span>`;
                    textIndex++;
                    loopTimeout = setTimeout(runFoundryEngine, 30);
                } else {
                    currentPhase = 'exp';
                    textIndex = 0;
                    heroMainName.classList.remove('cursor-blink');
                    heroMainExp.classList.add('cursor-blink');
                    loopTimeout = setTimeout(runFoundryEngine, 350);
                }
            }
            // Phase 3: Experience Description Block
            else if (currentPhase === 'exp') {
                const maxVisibleChars = 90; 
                const cappedText = activeIdentity.exp.length > maxVisibleChars 
                    ? activeIdentity.exp.substring(0, maxVisibleChars) + "..." 
                    : activeIdentity.exp;

                if (textIndex < cappedText.length) {
                    heroMainExp.innerHTML = `<p class="paper-exp-text" style="margin:0; font-size: 0.55rem; line-height: 1.35; color: #333; font-weight: 500;">${cappedText.substring(0, textIndex + 1)}</p>`;
                    textIndex++;
                    loopTimeout = setTimeout(runFoundryEngine, 10);
                } else {
                    currentPhase = 'skills';
                    textIndex = 0;
                    skillIndex = 0;
                    heroMainExp.classList.remove('cursor-blink');
                    loopTimeout = setTimeout(runFoundryEngine, 300);
                }
            }
            // Phase 4: Dynamic Matrix Array Elements
            else if (currentPhase === 'skills') {
                if (skillIndex < activeIdentity.skills.length) {
                    const currentSkillText = activeIdentity.skills[skillIndex];
                    
                    if (textIndex < currentSkillText.length) {
                        let renderingHTML = "";
                        for(let i=0; i<skillIndex; i++) {
                            renderingHTML += `<div style="font-size: 0.52rem; font-weight: 600; color: #444; width: calc(50% - 6px); display:flex; align-items:center;">• ${activeIdentity.skills[i]}</div>`;
                        }
                        renderingHTML += `<div class="cursor-blink" style="font-size: 0.52rem; font-weight: 600; color: #444; width: calc(50% - 6px); display:flex; align-items:center;">• ${currentSkillText.substring(0, textIndex + 1)}</div>`;
                        heroMainSkills.innerHTML = renderingHTML;
                        
                        textIndex++;
                        loopTimeout = setTimeout(runFoundryEngine, 20);
                    } else {
                        skillIndex++;
                        textIndex = 0;
                        loopTimeout = setTimeout(runFoundryEngine, 150);
                    }
                } else {
                    const children = heroMainSkills.children;
                    if(children.length > 0) children[children.length-1].classList.remove('cursor-blink');
                    
                    currentPhase = 'edu';
                    textIndex = 0;
                    heroMainEdu.classList.add('cursor-blink');
                    loopTimeout = setTimeout(runFoundryEngine, 300);
                }
            }
            // Phase 5: Educational Terminal Node
            else if (currentPhase === 'edu') {
                if (textIndex < activeIdentity.edu.length) {
                    heroMainEdu.innerHTML = `<div style="font-size: 0.52rem; color: #555; font-weight: 500; font-family: sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${activeIdentity.edu.substring(0, textIndex + 1)}</div>`;
                    textIndex++;
                    loopTimeout = setTimeout(runFoundryEngine, 15);
                } else {
                    heroMainEdu.classList.remove('cursor-blink');
                    historicalResumes.push({...activeIdentity});
                    if (historicalResumes.length > 5) historicalResumes.shift();

                    // Master display hold window (6 seconds) before layout breakdown transition
                    loopTimeout = setTimeout(() => {
                        updateBackgroundSheets();

                        const targets = [heroMainName, heroMainHeadline, heroMainExp, heroMainSkills, heroMainEdu];
                        targets.forEach(t => {
                            t.style.opacity = 0;
                            t.style.transition = "opacity 0.4s ease";
                        });

                        setTimeout(() => {
                            targets.forEach(t => { t.innerHTML = ""; t.style.opacity = 1; });

                            currentPhase = 'name';
                            textIndex = 0;
                            heroMainName.classList.add('cursor-blink');
                            
                            activeIdentity = generateRandomIdentity();
                            runFoundryEngine();
                        }, 500);
                    }, 6000);
                }
            }
        }

        // Initialize Identity Pipeline History Seeds
        historicalResumes.push(generateRandomIdentity());
        historicalResumes.push(generateRandomIdentity());
        updateBackgroundSheets();

        // Fire Generator Engine Context
        activeIdentity = generateRandomIdentity();
        if (heroMainName) heroMainName.classList.add('cursor-blink');
        setTimeout(runFoundryEngine, 600);
    });
})();