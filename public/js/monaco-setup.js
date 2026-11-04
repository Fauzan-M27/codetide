/**
 * Monaco Editor Setup & Configuration
 * Untuk CodeTide Question Editor
 */

let monacoEditor = null;
let editorLanguage = 'html'; // Default language

/**
 * Initialize Monaco Editor
 * @param {string} containerId - ID of container element
 * @param {string} language - Language mode (html, css, javascript)
 * @param {string} initialValue - Initial code value
 * @param {Function} onChange - Callback when content changes
 */
function initMonacoEditor(containerId, language = 'html', initialValue = '', onChange = null) {
    const container = document.getElementById(containerId);
    
    if (!container) {
        console.error(`Container #${containerId} not found`);
        return null;
    }

    // Monaco Editor Configuration
    monacoEditor = monaco.editor.create(container, {
        value: initialValue,
        language: language,
        theme: 'codetide-dark', // Custom theme (will be defined below)
        automaticLayout: true, // Auto resize
        fontSize: 14,
        lineNumbers: 'on',
        minimap: {
            enabled: false // Disable minimap untuk space lebih besar
        },
        scrollBeyondLastLine: false,
        wordWrap: 'on',
        tabSize: 2,
        insertSpaces: true,
        renderWhitespace: 'selection',
        quickSuggestions: true,
        suggestOnTriggerCharacters: true,
        acceptSuggestionOnEnter: 'on',
        bracketPairColorization: {
            enabled: true
        },
        guides: {
            bracketPairs: true,
            indentation: true
        },
        padding: {
            top: 16,
            bottom: 16
        },
        scrollbar: {
            vertical: 'auto',
            horizontal: 'auto',
            useShadows: false,
            verticalScrollbarSize: 12,
            horizontalScrollbarSize: 12
        }
    });

    editorLanguage = language;

    // Setup onChange callback with debounce
    if (onChange && typeof onChange === 'function') {
        let debounceTimer;
        monacoEditor.onDidChangeModelContent(() => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const code = monacoEditor.getValue();
                onChange(code);
            }, 500); // 500ms debounce
        });
    }

    return monacoEditor;
}

/**
 * Define Custom CodeTide Dark Theme
 */
function defineCodeTideTheme() {
    monaco.editor.defineTheme('codetide-dark', {
        base: 'vs-dark',
        inherit: true,
        rules: [
            { token: 'comment', foreground: '6A9955', fontStyle: 'italic' },
            { token: 'keyword', foreground: '569CD6', fontStyle: 'bold' },
            { token: 'string', foreground: 'CE9178' },
            { token: 'number', foreground: 'B5CEA8' },
            { token: 'regexp', foreground: 'D16969' },
            { token: 'type', foreground: '4EC9B0' },
            { token: 'class', foreground: '4EC9B0' },
            { token: 'function', foreground: 'DCDCAA' },
            { token: 'variable', foreground: '9CDCFE' },
            { token: 'constant', foreground: '4FC1FF' },
            { token: 'tag', foreground: '569CD6' },
            { token: 'attribute.name', foreground: '9CDCFE' },
            { token: 'attribute.value', foreground: 'CE9178' },
        ],
        colors: {
            'editor.background': '#0B1622', // Dark blue background (sesuai theme kita)
            'editor.foreground': '#E8F4FF',
            'editor.lineHighlightBackground': '#0F1F2E',
            'editor.selectionBackground': '#264F78',
            'editor.inactiveSelectionBackground': '#1E3A50',
            'editorCursor.foreground': '#53EAFD', // Cyan cursor
            'editorLineNumber.foreground': '#4B5C6B',
            'editorLineNumber.activeForeground': '#53EAFD',
            'editor.selectionHighlightBackground': '#264F7840',
            'editor.wordHighlightBackground': '#575757B8',
            'editorIndentGuide.background': '#1E3A50',
            'editorIndentGuide.activeBackground': '#53EAFD',
            'editorBracketMatch.background': '#0064001A',
            'editorBracketMatch.border': '#53EAFD',
        }
    });
}

/**
 * Get current editor value
 */
function getEditorValue() {
    return monacoEditor ? monacoEditor.getValue() : '';
}

/**
 * Set editor value
 */
function setEditorValue(value) {
    if (monacoEditor) {
        monacoEditor.setValue(value || '');
    }
}

/**
 * Change editor language
 */
function setEditorLanguage(language) {
    if (monacoEditor) {
        monaco.editor.setModelLanguage(monacoEditor.getModel(), language);
        editorLanguage = language;
    }
}

/**
 * Get current editor language
 */
function getEditorLanguage() {
    return editorLanguage;
}

/**
 * Resize editor (useful when container size changes)
 */
function resizeEditor() {
    if (monacoEditor) {
        monacoEditor.layout();
    }
}

/**
 * Focus editor
 */
function focusEditor() {
    if (monacoEditor) {
        monacoEditor.focus();
    }
}

/**
 * Dispose editor (cleanup)
 */
function disposeEditor() {
    if (monacoEditor) {
        monacoEditor.dispose();
        monacoEditor = null;
    }
}

/**
 * Add custom HTML snippets for auto-completion
 */
function registerHTMLSnippets() {
    monaco.languages.registerCompletionItemProvider('html', {
        provideCompletionItems: (model, position) => {
            const suggestions = [
                {
                    label: 'html5',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: [
                        '<!DOCTYPE html>',
                        '<html lang="id">',
                        '<head>',
                        '  <meta charset="UTF-8">',
                        '  <meta name="viewport" content="width=device-width, initial-scale=1.0">',
                        '  <title>${1:Document}</title>',
                        '</head>',
                        '<body>',
                        '  ${2}',
                        '</body>',
                        '</html>'
                    ].join('\n'),
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'HTML5 boilerplate'
                },
                {
                    label: 'div',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: '<div>${1}</div>',
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'Div element'
                },
                {
                    label: 'p',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: '<p>${1}</p>',
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'Paragraph element'
                },
                {
                    label: 'a',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: '<a href="${1}">${2}</a>',
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'Anchor link'
                },
                {
                    label: 'img',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: '<img src="${1}" alt="${2}">',
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'Image element'
                },
                {
                    label: 'form',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: [
                        '<form action="${1}" method="${2:post}">',
                        '  ${3}',
                        '</form>'
                    ].join('\n'),
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'Form element'
                },
                {
                    label: 'table',
                    kind: monaco.languages.CompletionItemKind.Snippet,
                    insertText: [
                        '<table>',
                        '  <thead>',
                        '    <tr>',
                        '      <th>${1}</th>',
                        '    </tr>',
                        '  </thead>',
                        '  <tbody>',
                        '    <tr>',
                        '      <td>${2}</td>',
                        '    </tr>',
                        '  </tbody>',
                        '</table>'
                    ].join('\n'),
                    insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    documentation: 'Table structure'
                }
            ];

            return { suggestions: suggestions };
        }
    });
}

/**
 * Initialize when DOM is ready
 */
if (typeof monaco !== 'undefined') {
    // Define theme immediately if Monaco is loaded
    defineCodeTideTheme();
    // Register snippets
    registerHTMLSnippets();
}

// Export functions to global scope
window.initMonacoEditor = initMonacoEditor;
window.getEditorValue = getEditorValue;
window.setEditorValue = setEditorValue;
window.setEditorLanguage = setEditorLanguage;
window.getEditorLanguage = getEditorLanguage;
window.resizeEditor = resizeEditor;
window.focusEditor = focusEditor;
window.disposeEditor = disposeEditor;
window.defineCodeTideTheme = defineCodeTideTheme;
window.registerHTMLSnippets = registerHTMLSnippets;
