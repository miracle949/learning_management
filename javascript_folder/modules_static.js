

const STATIC_MODULES = {
    philosophy: [
        {
            id: 'static_phil_1',
            title: 'Introduction to Critical Thinking',
            description: 'A foundational guide to logical reasoning and argumentation.',
            lessons: 4,
            is_static: true
        },
        {
            id: 'static_phil_2',
            title: 'The Socratic Method',
            description: 'Learn how to question assumptions and seek deeper truths.',
            lessons: 3,
            is_static: true
        },
    ],
    ucsp: [
        {
            id: 'static_ucsp_1',
            title: 'What is Culture?',
            description: 'An overview of cultural identity, norms, and values.',
            lessons: 3,
            is_static: true
        },
        {
            id: 'static_ucsp_2',
            title: 'Society and Social Institutions',
            description: 'Understanding how societies are structured and maintained.',
            lessons: 4,
            is_static: true
        },
    ],
    math: [
        {
            id: 'static_math_1',
            title: 'Number Sense Fundamentals',
            description: 'Core concepts in arithmetic and number theory.',
            lessons: 5,
            is_static: true
        },
    ],
    // add the rest of your 8 subjects here...
};

// console.log('Subject slug:', subject);
// console.log('STATIC_MODULES keys:', typeof STATIC_MODULES !== 'undefined' ? Object.keys(STATIC_MODULES) : 'NOT LOADED');