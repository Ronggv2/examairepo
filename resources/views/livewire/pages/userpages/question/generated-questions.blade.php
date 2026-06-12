<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm min-h-[720px]">
    <!-- Question Set Control Panel -->
    <div class="mb-6 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Question Set Management</h3>
                <p class="text-xs text-slate-500 mt-1">{{ count($questions) }} questions • Subjects: {{ implode(', ', $subjects ?? ['General']) }}</p>
            </div>
            <!-- <button wire:click="regenerate" :disabled="$wire.isLoading" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 disabled:bg-slate-200 disabled:text-slate-500">
                @if($isLoading)
                    <svg class="h-3 w-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                @endif
                {{ $isLoading ? 'Generating...' : 'Regenerate' }}
            </button> -->
        </div>

        <!-- Subject Management Row -->
        <div class="space-y-2 border-t border-slate-200 pt-3">
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-2">Current Subjects</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(($subjects ?? ['General']) as $index => $subj)
                        <div class="inline-flex items-center gap-2 rounded-lg bg-white border border-slate-300 px-2.5 py-1.5 text-xs font-medium">
                            <span class="text-slate-700">{{ $subj }}</span>
                            @if(count($subjects ?? []) > 1)
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        @if($error)
            <div class="rounded-lg border border-red-200 bg-red-50 p-2">
                <p class="text-xs font-medium text-red-700">{{ $error }}</p>
            </div>
        @endif
    </div>

    <!-- Questions List -->
    <div class="mt-6 space-y-4">

    <div class="mt-6 space-y-4">
        @if($isLoading)
            <div class="rounded-3xl border border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-8">
                <div class="flex flex-col items-center justify-center gap-4">
                    <svg class="h-12 w-12 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div class="text-center">
                        <p class="text-sm font-semibold text-slate-900">Generating AI Questions...</p>
                        <p class="text-xs text-slate-500 mt-1">This may take a moment</p>
                    </div>
                </div>
            </div>
        @elseif(count($questions))
            @foreach($questions as $index => $question)
                @php
                    $questionText = null;
                    $answers = [];
                    $correctAnswer = null;

                    if (is_object($question)) {
                        $question = json_decode(json_encode($question), true);
                    }

                    if (is_array($question)) {
                        $questionText = $question['question'] ?? $question['questionText'] ?? $question['question_text'] ?? $question['text'] ?? $question['Question'] ?? $question['prompt'] ?? null;
                        $answers = $question['answers'] ?? $question['options'] ?? $question['choices'] ?? $question['answer'] ?? [];
                        $correctAnswer = $question['correctAnswer'] ?? $question['correct_answer'] ?? $question['correct'] ?? $question['answer'] ?? null;
                    }

                    if (is_object($answers)) {
                        $answers = (array) $answers;
                    }

                    if (is_string($answers)) {
                        $answers = preg_split('/\r?\n/', trim($answers));
                    }

                    $answers = is_array($answers) ? array_values($answers) : [];

                    if (is_array($questionText)) {
                        $questionText = implode(' ', array_map('strval', $questionText));
                    }

                    if (is_array($correctAnswer)) {
                        $correctAnswer = implode(', ', array_map('strval', $correctAnswer));
                    }
                @endphp
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                        <div class="space-y-3">
                            @if($editingIndex === $index)
                                <label class="text-xs font-medium text-slate-700">Question</label>
                                <textarea wire:model.defer="editingQuestion.question" rows="3" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"></textarea>

                                <label class="text-xs font-medium text-slate-700">Answers</label>
                                <div class="grid gap-2">
                                    @for($i = 0; $i < 4; $i++)
                                        <input wire:model.defer="editingQuestion.answers.{{ $i }}" type="text" placeholder="Answer {{ $i + 1 }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                                    @endfor
                                </div>

                                <label class="text-xs font-medium text-slate-700">Correct Answer</label>
                                <input wire:model.defer="editingQuestion.correctAnswer" type="text" placeholder="Correct answer text" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                            @else
                                <h3 class="text-base font-semibold text-slate-900">{{ $loop->iteration }}. {{ $questionText ?? 'No question provided' }}</h3>
                                <div class="grid gap-2 text-sm text-slate-600">
                                    @foreach($answers as $answer)
                                        @php
                                            $answerText = is_array($answer) ? implode(', ', array_map('strval', $answer)) : $answer;
                                        @endphp
                                        <div>{{ $answerText }}</div>
                                    @endforeach
                                </div>
                                @if(!empty($correctAnswer))
                                    <div class="mt-3 text-sm font-medium text-slate-700">Correct answer: {{ $correctAnswer }}</div>
                                @endif
                            @endif
                        </div>

                            <div class="flex gap-2">
                                @if($editingIndex === $index)
                                    <button wire:click="saveEdit" class="inline-flex h-10 items-center justify-center rounded-2xl bg-green-50 border border-green-200 text-green-700 px-3">Save</button>
                                    <button wire:click="cancelEdit" class="inline-flex h-10 items-center justify-center rounded-2xl bg-white border border-slate-200 text-slate-700 px-3">Cancel</button>
                                @else
                                    <button wire:click="editQuestion({{ $index }})" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:border-slate-300">
                                        <img src="{{ asset('assets/svg/edit.svg') }}" alt="Edit" class="h-4 w-4">
                                    </button>
                                    <button wire:click="delete({{ $index }})" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:border-slate-300">
                                        <img src="{{ asset('assets/svg/delete.svg') }}" alt="Delete" class="h-4 w-4">
                                    </button>
                                @endif
                            </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="rounded-3xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                No questions generated yet. Click Regenerate to create AI questions and answers.
            </div>
        @endif
    </div>
</div>

<!-- Puter.js for Free OpenAI API -->
<script>
// Initialize Puter token before loading the script so the SDK can use it silently.
window.PUTER_TOKEN = '{{ env("PUTER_TOKEN") }}';
try {
    if (window.PUTER_TOKEN) {
        localStorage.setItem('puter.auth.token.v2', window.PUTER_TOKEN);
        localStorage.setItem('puter.auth.token', window.PUTER_TOKEN);
    }
} catch (e) {
    console.warn('Could not persist Puter token to localStorage:', e);
}
</script>
<script src="https://js.puter.com/v2/"></script>

<script>
function initPuterToken() {
    if (!window.PUTER_TOKEN || !window.puter) {
        return;
    }

    if (typeof window.puter.setAuthToken === 'function') {
        window.puter.setAuthToken(window.PUTER_TOKEN);
    }
}

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    initPuterToken();
} else {
    window.addEventListener('DOMContentLoaded', initPuterToken);
}

function handlePuterGenerateEvent(event) {
    const params = event?.detail ?? event;
    const data = Array.isArray(params) ? params[0] : params;
    console.log('Regenerate triggered with params:', data);
    generateQuestionsWithPuter(data);
}

window.addEventListener('puter-generate', handlePuterGenerateEvent);

window.addEventListener('puter-generate', handlePuterGenerateEvent);

async function generateQuestionsWithPuter(params) {
    const count = params.count || 5;
    const subject = params.subject || 'General knowledge';
    const difficulty = params.difficulty || 'Easy';

    const prompt = `Create exactly ${count} multiple-choice questions about the subject "${subject}" at "${difficulty}" difficulty. ` +
        `Use the subject exactly as provided in the prompt and do not generate questions for any other topic. ` +
        `Respond with ONLY valid JSON and nothing else. Return an array of exactly ${count} objects that starts with '[' and ends with ']'. ` +
        `Each object must include exactly: question (string), answers (array of 4 strings), correctAnswer (string). ` +
        `Do not include any extra text, explanation, or markdown formatting. Example output: [{"question":"...","answers":["A...","B...","C...","D..."],"correctAnswer":"B..."}]`;

    try {
        // Show loading state
        document.querySelector('livewire-component')?.dispatchEvent(new CustomEvent('generating', { detail: true }));

        // Call Puter's OpenAI API (free, user-pays model)
        const response = await puter.ai.chat(prompt, {
            model: 'gpt-4o-mini', // Using a faster, more cost-effective model
            temperature: 0.7,
        });

        // Parse the JSON response
        console.log('API Response:', response, typeof response);
        const rawContent = response?.message?.content ?? response?.text ?? response?.content ?? response;
        console.log('Raw AI content:', rawContent);

        let jsonStr = extractJson(rawContent);
        let questions;
        try {
            questions = JSON.parse(jsonStr);
        } catch (parseError) {
            console.warn('Initial JSON.parse failed, attempting to sanitize AI output', parseError, jsonStr);

            // Basic sanitization heuristics: normalize smart quotes, remove trailing commas,
            // and convert simple single-quoted strings to double-quoted strings.
            try {
                let cleaned = String(jsonStr || '')
                    .replace(/[\u2018\u2019\u201C\u201D]/g, '"') // smart quotes -> "
                    .replace(/,\s*([}\]])/g, '$1') // remove trailing commas before } or ]
                    .replace(/\r?\n/g, ' ') // collapse newlines
                    .trim();

                // Convert simple single-quoted values to double quotes when safe
                cleaned = cleaned.replace(/'([^']*)'/g, function(match, p1) {
                    return '"' + p1.replace(/\"/g, '\\"') + '"';
                });

                questions = JSON.parse(cleaned);
                jsonStr = cleaned; // use cleaned for debugging later
            } catch (secondError) {
                console.error('Sanitized JSON.parse also failed', secondError, jsonStr);
                throw parseError; // rethrow original parse error to be handled by outer catch
            }
        }

        // Normalize and validate questions
        const normalizedQuestions = normalizeQuestions(questions);
        console.log('Normalized questions:', normalizedQuestions);
        if (normalizedQuestions.length !== count) {
            console.warn(`Requested ${count} questions but received ${normalizedQuestions.length}.`, normalizedQuestions);
        }

        // Send back to Livewire via DOM event
        console.log('Dispatching questionsGenerated with', normalizedQuestions.length, 'items');
        const payload = { questions: normalizedQuestions };
        window.dispatchEvent(new CustomEvent('questionsGenerated', {
            detail: payload,
            bubbles: true,
            composed: true,
        }));
        document.dispatchEvent(new CustomEvent('questionsGenerated', {
            detail: payload,
            bubbles: true,
            composed: true,
        }));
    } catch (error) {
        console.error('Puter.js Error:', error);
        const errorMessage = error.message || 'Failed to generate questions using Puter.js. Please try again.';
        const errorPayload = { error: errorMessage };
        window.dispatchEvent(new CustomEvent('generationError', {
            detail: errorPayload,
            bubbles: true,
            composed: true,
        }));
        document.dispatchEvent(new CustomEvent('generationError', {
            detail: errorPayload,
            bubbles: true,
            composed: true,
        }));
    }
}

function extractJson(content) {
    // Convert to string if it's not already (handle object responses)
    if (typeof content !== 'string') {
        if (content && typeof content === 'object') {
            // Puter.js returns: {message: {content: '...'}, ...}
            content = content.message?.content || content.text || content.message || content.content || JSON.stringify(content);
        } else {
            content = String(content || '');
        }
    }
    
    if (!content || typeof content !== 'string') {
        console.error('extractJson received invalid content:', content);
        return '[]';
    }
    
    content = content.trim().replace(/```(?:json)?/gi, '');
    
    // Try to find balanced JSON
    let start = null;
    let depth = 0;
    let inString = false;
    let escape = false;
    let bracket = null;

    for (let i = 0; i < content.length; i++) {
        const char = content[i];

        if (escape) {
            escape = false;
            continue;
        }

        if (char === '\\') {
            escape = true;
            continue;
        }

        if (char === '"') {
            inString = !inString;
            continue;
        }

        if (inString) continue;

        if (start === null && (char === '[' || char === '{')) {
            start = i;
            bracket = char;
            depth = 1;
            continue;
        }

        if (start !== null) {
            if (char === bracket) {
                depth++;
            } else if ((bracket === '[' && char === ']') || (bracket === '{' && char === '}')) {
                depth--;
                if (depth === 0) {
                    return content.substring(start, i + 1);
                }
            }
        }
    }

    return content;
}

function normalizeAnswers(answers) {
    if (Array.isArray(answers)) {
        return answers.map((answer) => typeof answer === 'string' ? answer.trim() : String(answer || '').trim()).filter(Boolean);
    }

    if (answers && typeof answers === 'object') {
        return Object.values(answers).map((answer) => typeof answer === 'string' ? answer.trim() : String(answer || '').trim()).filter(Boolean);
    }

    if (typeof answers === 'string') {
        return answers
            .trim()
            .split(/\r?\n/)
            .map((line) => line.replace(/^[A-D]\s*[\.\)\-]?\s*/i, '').trim())
            .filter(Boolean);
    }

    return [];
}

function normalizeQuestions(questions) {
    if (!Array.isArray(questions)) {
        return [{
            question: 'Invalid response format received.',
            answers: [],
            correctAnswer: null
        }];
    }

    return questions.map((item) => {
        if (typeof item === 'string') {
            return {
                question: item,
                answers: [],
                correctAnswer: null
            };
        }

        if (!item || typeof item !== 'object') {
            return {
                question: 'No question provided',
                answers: [],
                correctAnswer: null
            };
        }

        const question = (item.question || item.questionText || item.question_text || item.text || item.Question || item.prompt || '').trim() || 'No question provided';
        const answers = normalizeAnswers(item.answers || item.options || item.choices || item.answer || []);
        const correctAnswer = (item.correctAnswer || item.correct_answer || item.answer || item.correct || item.correctChoice || '').trim() || null;

        return {
            question,
            answers,
            correctAnswer
        };
    });
}
    </script>
</div>
