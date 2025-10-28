"use strict";

const paragraphsData = [
    // Paragraph 1
    [
        { top: 60, left: 100, width: 80 },
        { top: 62, left: 104, width: 20 },
        { top: 67, left: 127, width: 5 },
        { top: 70, left: 135, width: 10 },
        { top: 74, left: 150, width: 5 },
        { top: 78, left: 160, width: 15 }
    ],
    // Paragraph 2
    [
        { top: 82, left: 102, width: 30 },
        { top: 88, left: 136, width: 4 },
        { top: 91, left: 145, width: 5 },
        { top: 93, left: 155, width: 2 },
        { top: 96, left: 160, width: 15 },
        { top: 90, left: 104, width: 5 },
        { top: 92, left: 113, width: 2 },
        { top: 95, left: 119, width: 8 },
        { top: 100, left: 133, width: 20 },
        { top: 105, left: 159, width: 2 },
        { top: 108, left: 166, width: 10 }
    ],
    // Paragraph 3
    [
        { top: 115, left: 100, width: 50 },
        { top: 123, left: 152, width: 3 },
        { top: 127, left: 160, width: 15 },
        { top: 121, left: 103, width: 5 },
        { top: 126, left: 110, width: 30 },
        { top: 132, left: 143, width: 2 },
        { top: 136, left: 150, width: 15 },
        { top: 140, left: 170, width: 5 }
    ],
    // Paragraph 4
    [
        { top: 140, left: 101, width: 10 },
        { top: 142, left: 115, width: 2 },
        { top: 146, left: 120, width: 15 },
        { top: 150, left: 140, width: 5 },
        { top: 156, left: 150, width: 26 },
        { top: 153, left: 99, width: 29 },
        { top: 158, left: 130, width: 5 },
        { top: 161, left: 140, width: 2 },
        { top: 166, left: 146, width: 30 },
    ]
];

function generateLines(paragraph, linesData) {
    linesData.forEach(data => {
        const line = document.createElement('div');
        line.className = 'shape line';
        line.style.top = data.top + 'px';
        line.style.left = data.left + 'px';
        line.style.width = data.width + 'px';
        paragraph.appendChild(line);
    });
}

function generateParagraphs(container, paragraphsData) {
    paragraphsData.forEach((linesData, index) => {
        const paragraph = document.createElement('div');
        paragraph.className = `paragraph paragraph-${index + 1}`;
        generateLines(paragraph, linesData);
        container.appendChild(paragraph);
    });
}

const resumeContainer = document.querySelector('.resume-container');
generateParagraphs(resumeContainer, paragraphsData);