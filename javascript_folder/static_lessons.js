const STATIC_LESSONS = {
    philosophy: {
        static_phil_1: { // matches module id from STATIC_MODULES
            title: 'Introduction to Critical Thinking',
            description: 'A foundational guide to logical reasoning.',
            lessons: [
                {
                    id: 'static_phil_1_L1',
                    title: 'Lesson 1: What is Critical Thinking?',
                    content: `Critical thinking is the ability to analyze facts to form a judgment. 
                              It is self-directed, self-disciplined, and self-corrective thinking.`,
                    videos: [
                        {
                            title: 'Introduction to Critical Thinking',
                            url: 'https://www.youtube.com/watch?v=example1'
                        }
                    ],
                    images: [
                        {
                            title: 'Critical Thinking Framework',
                            path: '../assets/static/critical_thinking.jpg'
                        }
                    ],
                    flashcards: [
                        { front: 'What is Critical Thinking?', back: 'The ability to analyze facts to form a judgment.' },
                        { front: 'What is Logic?', back: 'The study of reasoning and argumentation.' },
                    ],
                    activity: {
                        title: 'Reflection Activity',
                        instructions: 'Answer the following questions based on the lesson.',
                        total_points: 20,
                        questions: [
                            {
                                id: 'static_phil_1_L1_A1',
                                type: 'essay',
                                question: 'In your own words, what is critical thinking?'
                            },
                            {
                                id: 'static_phil_1_L1_A2',
                                type: 'multiple_choice',
                                question: 'Which of the following best describes critical thinking?',
                                choices: {
                                    a: 'Accepting all information as true',
                                    b: 'Analyzing facts to form a judgment',
                                    c: 'Memorizing information',
                                    d: 'Following instructions blindly'
                                },
                                correct: 'b'
                            }
                        ]
                    },
                    quiz: {
                        title: 'Critical Thinking Quiz',
                        instructions: 'Choose the best answer for each question.',
                        passing_score: 75,
                        questions: [
                            {
                                id: 'static_phil_1_L1_Q1',
                                question: 'What does critical thinking involve?',
                                choices: {
                                    a: 'Blind acceptance',
                                    b: 'Emotional decisions',
                                    c: 'Analyzing and evaluating information',
                                    d: 'Memorization'
                                },
                                correct: 'c'
                            },
                            {
                                id: 'static_phil_1_L1_Q2',
                                question: 'Critical thinking helps us to:',
                                choices: {
                                    a: 'Make informed decisions',
                                    b: 'Avoid all questions',
                                    c: 'Accept everything we read',
                                    d: 'Skip problem solving'
                                },
                                correct: 'a'
                            }
                        ]
                    }
                },
                {
                    id: 'static_phil_1_L2',
                    title: 'Lesson 2: Logic and Reasoning',
                    content: `Logic is the foundation of all rational thinking. 
                              It helps us construct valid arguments and identify fallacies.`,
                    videos: [],
                    images: [],
                    flashcards: [
                        { front: 'What is Deductive Reasoning?', back: 'Reasoning from general to specific.' },
                        { front: 'What is Inductive Reasoning?', back: 'Reasoning from specific to general.' },
                    ],
                    activity: null,
                    quiz: {
                        title: 'Logic Quiz',
                        instructions: 'Answer all questions.',
                        passing_score: 75,
                        questions: [
                            {
                                id: 'static_phil_1_L2_Q1',
                                question: 'Deductive reasoning goes from:',
                                choices: {
                                    a: 'Specific to general',
                                    b: 'General to specific',
                                    c: 'Question to answer',
                                    d: 'Feeling to fact'
                                },
                                correct: 'b'
                            }
                        ]
                    }
                }
            ]
        },
        static_phil_2: {
            title: 'The Socratic Method',
            description: 'Learn how to question assumptions.',
            lessons: [
                {
                    id: 'static_phil_2_L1',
                    title: 'Lesson 1: Who was Socrates?',
                    content: `Socrates was a classical Greek philosopher credited as the founder 
                              of Western philosophy. He is known for the Socratic method of questioning.`,
                    videos: [
                        { title: 'Who Was Socrates?', url: 'https://www.youtube.com/watch?v=example2' }
                    ],
                    images: [],
                    flashcards: [
                        { front: 'Who is Socrates?', back: 'A classical Greek philosopher and founder of Western philosophy.' }
                    ],
                    activity: null,
                    quiz: null
                }
            ]
        }
    },
    ucsp: {
        static_ucsp_1: {
            title: 'What is Culture?',
            description: 'An overview of cultural identity, norms, and values.',
            lessons: [
                {
                    id: 'static_ucsp_1_L1',
                    title: 'Lesson 1: Defining Culture',
                    content: `Culture refers to the shared beliefs, values, customs, behaviors, 
                              and artifacts that members of a society use to cope with their world.`,
                    videos: [],
                    images: [],
                    flashcards: [
                        { front: 'What is Culture?', back: 'Shared beliefs, values, and customs of a society.' },
                        { front: 'What are Cultural Norms?', back: 'Rules and expectations that guide behavior in a society.' }
                    ],
                    activity: {
                        title: 'Culture Reflection',
                        instructions: 'Answer based on your own cultural experience.',
                        total_points: 15,
                        questions: [
                            {
                                id: 'static_ucsp_1_L1_A1',
                                type: 'essay',
                                question: 'Describe one cultural practice from your community.'
                            }
                        ]
                    },
                    quiz: {
                        title: 'Culture Quiz',
                        instructions: 'Choose the best answer.',
                        passing_score: 75,
                        questions: [
                            {
                                id: 'static_ucsp_1_L1_Q1',
                                question: 'Culture is best described as:',
                                choices: {
                                    a: 'Individual preferences',
                                    b: 'Shared beliefs and values of a society',
                                    c: 'Government policies',
                                    d: 'Economic systems'
                                },
                                correct: 'b'
                            }
                        ]
                    }
                }
            ]
        }
    }
    // ... add more subjects and modules here
};