<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF OCR Extractor (Gemini API)</title>
    <!-- 1. Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- 2. PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <!-- Set PDF.js worker source -->
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    </script>
    <!-- 3. Tesseract.js (for OCR) - REMOVED -->
    <!-- 4. SheetJS (js-xlsx) (for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <style>
        /* Custom styles for a cleaner look */
        body {
            font-family: 'Inter', sans-serif;
        }
        .card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease-in-out;
        }
        .btn {
            @apply inline-block px-5 py-3 rounded-lg font-semibold text-white shadow-md transition-all duration-200;
        }
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400;
        }
        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400;
        }
        .btn-warning {
            @apply bg-yellow-500 hover:bg-yellow-600 text-yellow-900 disabled:bg-gray-400;
        }
        #page-nav {
            @apply flex items-center justify-center space-x-2 mt-4;
        }
        #page-nav button {
            @apply px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 disabled:opacity-50;
        }
        #page_num {
            @apply w-16 text-center p-2 border rounded-md;
        }
        #status-message {
            @apply text-center my-4 p-3 rounded-md text-sm;
        }
        .status-info {
            @apply bg-blue-100 text-blue-800;
        }
        .status-success {
            @apply bg-green-100 text-green-800;
        }
        .status-error {
            @apply bg-red-100 text-red-800;
        }
        #pdf-preview-container {
            @apply w-full max-w-4xl mx-auto border-4 border-dashed border-gray-300 rounded-lg p-4 mt-6;
        }
        #pdf-canvas {
            @apply w-full h-auto;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4 md:p-8">

    <div class="max-w-5xl mx-auto">
        
        <!-- Header -->
        <header class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">PDF to Text & Excel Extractor</h1>
            <p class="text-gray-600 mt-2">Upload a PDF, get the text, and export to Excel (Experimental)</p>
        </header>

        <!-- Main Card -->
        <main class="card p-6 md:p-8">

            <!-- File Upload -->
            <div>
                <label for="file-upload" class="block text-sm font-medium text-gray-700 mb-2">1. Upload your PDF file:</label>
                <input id="file-upload" name="file-upload" type="file" accept=".pdf"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100
                              cursor-pointer"/>
            </div>

            <!-- Status Message -->
            <div id="status-message" class="hidden"></div>
            
            <!-- Page Navigation -->
            <div id="page-nav" class="hidden">
                <button id="prev-page" class="btn-secondary" title="Previous Page">&larr;</button>
                <span>Page: 
                    <input type="number" id="page_num" value="1" min="1" class="border rounded px-2 py-1 w-16 text-center"> 
                    / 
                    <span id="page_count">0</span>
                </span>
                <button id="next-page" class="btn-secondary" title="Next Page">&rarr;</button>
            </div>
            
            <!-- PDF Preview -->
            <div id="pdf-preview-container" class="hidden">
                <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">PDF Preview</h2>
                <canvas id="pdf-canvas" class="border border-gray-400 rounded-md shadow-inner"></canvas>
            </div>

            <!-- Output Section -->
            <div id="output-section" class="mt-6 hidden">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">2. Extracted Text:</h2>
                <textarea id="output-text" rows="15" 
                          class="w-full p-4 border border-gray-300 rounded-lg bg-gray-50 font-mono text-sm" 
                          placeholder="Extracted text will appear here..." readonly></textarea>

                          <h2 class="text-xl font-semibold text-gray-700 mb-2 mt-6">3. Cleaned & Structured Summary:</h2>
<textarea id="cleaned-text" rows="12"
          class="w-full p-4 border border-gray-300 rounded-lg bg-gray-50 font-mono text-sm"
          placeholder="Gemini cleaned summary will appear here..."
          readonly></textarea>

                
                <div class="mt-4 flex flex-wrap gap-4 justify-center">
                    <button id="copy-text" class="btn btn-secondary">Copy to Clipboard</button>
                    <button id="download-txt" class="btn btn-secondary">Download as .txt</button>
                    <button id="download-excel" class="btn btn-warning" title="This is experimental and may not format complex tables correctly.">
                        Download as .xlsx (Beta)
                    </button>

                    <button id="extract-tables" class="btn btn-primary">Extract Tables (Camelot)</button>

                </div>
                <div id="copy-success" class="text-center text-green-600 font-medium mt-2 hidden">Copied to clipboard!</div>
            </div>

        </main>
    </div>

    <script>
        // --- DOM Elements ---
        const fileUpload = document.getElementById('file-upload');
        const statusMessage = document.getElementById('status-message');
        
        const pageNav = document.getElementById('page-nav');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const pageNumInput = document.getElementById('page_num');
        const pageCountSpan = document.getElementById('page_count');

        const previewContainer = document.getElementById('pdf-preview-container');
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');

        const outputSection = document.getElementById('output-section');
        const outputText = document.getElementById('output-text');
        const copyBtn = document.getElementById('copy-text');
        const copySuccessMsg = document.getElementById('copy-success');
        const downloadTxtBtn = document.getElementById('download-txt');
        const downloadExcelBtn = document.getElementById('download-excel');

        // --- PDF.js State ---
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let currentOcrController = null; // To cancel fetch requests

        // --- Helper: Update Status Message ---
        function updateStatus(message, type = 'info') {
            statusMessage.textContent = message;
            statusMessage.className = `text-center my-4 p-3 rounded-md text-sm status-${type}`;
            statusMessage.classList.remove('hidden');
        }

        // --- Helper: Render a PDF Page ---
        function renderPage(num) {
            pageRendering = true;
            pageNum = num;
            pageNumInput.value = num;
            updateStatus('Rendering PDF page...', 'info');
            
            // Disable output while rendering
            outputSection.classList.add('hidden');
            outputText.value = '';

            // Cancel any ongoing OCR fetch request
            if (currentOcrController) {
                currentOcrController.abort();
            }
            currentOcrController = new AbortController(); // Create a new controller for the new request


            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale: 1.5 });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                const renderTask = page.render(renderContext);
                
                renderTask.promise.then(() => {
                    pageRendering = false;
                    previewContainer.classList.remove('hidden');
                    updateStatus('Page rendered. Starting Gemini OCR...', 'info');
                    
                    // Start OCR after rendering
                    runOCR(); // This is now the Gemini API call

                    if (pageNumPending !== null) {
                        // New page was requested while rendering
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                }).catch(err => {
                    console.error('Page render error:', err);
                    updateStatus('Error rendering page.', 'error');
                    pageRendering = false;
                });
            }).catch(err => {
                console.error('pdfDoc.getPage error:', err);
                updateStatus('Error getting page from PDF.', 'error');
                pageRendering = false;
            });

            // Update page nav UI
            prevPageBtn.disabled = num <= 1;
            nextPageBtn.disabled = num >= pdfDoc.numPages;
        }

        // --- Helper: Queue Page Render ---
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // --- Helper: Run Gemini OCR ---
        async function runOCR() {
            outputSection.classList.add('hidden');
            updateStatus('Processing with Gemini OCR (this may take a moment)...', 'info');

            // 1. Get image data from canvas
            const imageDataUrl = canvas.toDataURL('image/png'); // Get as PNG
            const base64ImageData = imageDataUrl.split(',')[1]; // Strip the data URL prefix

            // 2. Set up API call
            const apiKey = "AIzaSyCwCYYWZqtip3KvsW6FV37lwbiYU64FS28"; // Per instructions, leave empty. Canvas will handle it.
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`;
            const prompt = `
            Extract all text from this image.

            THEN produce a second output called "CLEAN SUMMARY" in a human-readable format.

            Follow EXACTLY this structure:

            PARTS:
            PHASE:
            FLUID:
            TYPE:
            SPECIFICATION:
            GRADE:
            OPERATING TEMPERATURE:
            OPERATING PRESSURE:
            DESIGN TEMPERATURE:
            DESIGN PRESSURE:

            If a value cannot be identified, leave it blank.
            `;

            const payload = {
                contents: [{
                    parts: [
                        { text: prompt },
                        {
                            inlineData: {
                                mimeType: "image/png",
                                data: base64ImageData
                            }
                        }
                    ]
                }],
                // Optional: Add generationConfig or safetySettings if needed
            };

            // 3. Implement fetch with exponential backoff
            let response;
            let retries = 0;
            const maxRetries = 5;
            let delay = 1000;

            while (retries < maxRetries) {
                try {
                    response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                        signal: currentOcrController.signal // Pass the abort signal
                    });

                    if (response.ok) {
                        break; // Success
                    } else if (response.status === 429 || response.status >= 500) {
                        // Throttling or server error, wait and retry
                        console.warn(`Retryable error: ${response.status}. Retrying in ${delay}ms...`);
                        await new Promise(resolve => setTimeout(resolve, delay));
                        delay *= 2; // Exponential backoff
                        retries++;
                    } else {
                        // Client-side error (e.g., 400 Bad Request)
                        const errorData = await response.json();
                        console.error('API Error:', errorData);
                        updateStatus(`Error from Gemini API: ${errorData.error?.message || 'Bad Request'}`, 'error');
                        return; // Don't retry on client errors
                    }
                } catch (err) {
                    if (err.name === 'AbortError') {
                        console.log('OCR fetch aborted by user');
                        updateStatus('OCR Canceled.', 'info');
                        return; // This is an expected cancellation
                    }
                    console.error('Fetch network error:', err);
                    if (retries >= maxRetries - 1) {
                        updateStatus('Network error. Please check your connection and try again.', 'error');
                        return;
                    }
                    // Wait before retrying on network error
                    await new Promise(resolve => setTimeout(resolve, delay));
                    delay *= 2;
                    retries++;
                }
            }

            if (!response || !response.ok) {
                if (response) {
                    updateStatus(`Failed to get response after ${maxRetries} retries. Status: ${response.status}`, 'error');
                } else if (!currentOcrController.signal.aborted) {
                    // Only show this error if it wasn't an intentional abort
                    updateStatus(`Failed to get response after ${maxRetries} retries.`, 'error');
                }
                return;
            }

            // 4. Process the successful response
            try {
                const result = await response.json();
                const candidate = result.candidates?.[0];
                const text = candidate?.content?.parts?.[0]?.text;
                if (text) {
                    updateStatus('OCR complete!', 'success');

                    // 1. Put RAW OCR text into first textbox
                    outputText.value = text;

                    // 2. Extract the 'CLEAN SUMMARY' section if it exists
                    let cleaned = "";
                    const cleanIndex = text.indexOf("CLEAN SUMMARY");
                    if (cleanIndex !== -1) {
                        cleaned = text.substring(cleanIndex + "CLEAN SUMMARY".length).trim();
                    } else {
                        cleaned = "No clean summary returned by Gemini.";
                    }

                    document.getElementById("cleaned-text").value = cleaned;

                    outputSection.classList.remove('hidden');
                }

                else {
                    console.error('Unexpected API response structure:', result);
                    // Check for safety ratings blocking
                    if (candidate && candidate.finishReason !== 'STOP') {
                         updateStatus(`Error: No text returned. Reason: ${candidate.finishReason}`, 'error');
                    } else {
                         updateStatus('Error: Could not parse text from API response.', 'error');
                    }
                }
            } catch (err) {
                console.error('Response parsing error:', err);
                updateStatus('Error parsing API response.', 'error');
            }
        }

        // --- Event Listener: File Upload ---
        fileUpload.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') {
                updateStatus('Please select a valid PDF file.', 'error');
                return;
            }

            const fileReader = new FileReader();
            fileReader.onload = function() {
                const typedarray = new Uint8Array(this.result);
                updateStatus('Loading PDF...', 'info');

                pdfjsLib.getDocument(typedarray).promise.then(pdfDoc_ => {
                    pdfDoc = pdfDoc_;
                    pageCountSpan.textContent = pdfDoc.numPages;
                    pageNumInput.max = pdfDoc.numPages;
                    
                    // Reset to page 1 and render
                    pageNum = 1;
                    renderPage(pageNum);
                    
                    pageNav.classList.remove('hidden');
                }).catch(err => {
                    console.error('PDF load error:', err);
                    updateStatus('Error loading PDF file. It may be corrupt or protected.', 'error');
                });
            };
            fileReader.readAsArrayBuffer(file);
        });

        // --- Event Listeners: Page Navigation ---
        prevPageBtn.addEventListener('click', () => {
            if (pageNum <= 1) return;
            queueRenderPage(pageNum - 1);
        });

        nextPageBtn.addEventListener('click', () => {
            if (pageNum >= pdfDoc.numPages) return;
            queueRenderPage(pageNum + 1);
        });
        
        pageNumInput.addEventListener('change', () => {
             let num = parseInt(pageNumInput.value, 10);
             if (num < 1) num = 1;
             if (num > pdfDoc.numPages) num = pdfDoc.numPages;
             queueRenderPage(num);
        });


        // --- Event Listeners: Output Buttons ---
        
        // Copy to Clipboard
        copyBtn.addEventListener('click', () => {
            if (navigator.clipboard && window.isSecureContext) {
                // Modern async clipboard API
                navigator.clipboard.writeText(outputText.value).then(() => {
                    showCopySuccess();
                }).catch(err => {
                    console.warn('Async clipboard copy failed, falling back.', err);
                    fallbackCopyText();
                });
            } else {
                // Fallback for insecure contexts (like file://) or old browsers
                fallbackCopyText();
            }
        });

        function fallbackCopyText() {
            try {
                outputText.select();
                document.execCommand('copy');
                outputText.setSelectionRange(0, 0); // Deselect
                showCopySuccess();
            } catch (err) {
                console.error('Fallback copy failed:', err);
                alert('Failed to copy text. Please copy manually.');
            }
        }


        // --- Helper: Translate / Format OCR Text ---
async function translateTextWithGemini(rawText) {
    updateStatus('Formatting text for readability via Gemini...', 'info');

    const apiKey = ""; // replace with your API key
    const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`;

const prompt = `
Extract all text from this image. Then analyze the extracted data and produce a single JSON object strictly following this schema:

{
  "PARTS": "",
  "PHASE": "",
  "FLUID": "",
  "TYPE": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "SPECIFICATION": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "GRADE": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "INSULATION": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "DESIGN_TEMPERATURE_C": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "DESIGN_PRESSURE_MPa": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "OPERATING_TEMPERATURE_C": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  },
  "OPERATING_PRESSURE_MPa": {
    "category1": { "Top Head": "", "Shell": "", "Bottom Head": "" },
    "category2": { "Head": "", "Shell": "" },
    "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" },
    "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" }
  }
}

=========================
CATEGORY DETECTION RULES
=========================
- Category 1 → pressure vessel with Top Head, Shell, Bottom Head
- Category 2 → vessel with Head + Shell
- Category 3 → heat exchanger with Top Channel, Shell, Bottom Channel
- Category 4 → heat exchanger with Channel, Shell, Tube Bundle
Fill ONLY the matching category; leave all others as empty strings.

=========================
MATERIAL NORMALIZATION RULES
=========================
- Map extracted materials to TYPE, SPEC, and GRADE as follows:

| Extracted Material       | TYPE            | SPEC   | GRADE |
|--------------------------|----------------|--------|-------|
| ASTM A516 Gr70           | Carbon Steel   | SA-516 | 70    |
| ASTM A36                 | Carbon Steel   | A36    | ""    |
| ASTM A240 304L           | Stainless Steel| A240   | 304L  |
| ASTM A312 TP304L         | Stainless Steel| A312   | 304L  |
| ASTM A182 F304L          | Stainless Steel| A182   | 304L  |

- If multiple materials appear, assign each correctly to the corresponding part (Top Head, Shell, Bottom Head, etc.).

=========================
INSULATION RULE
=========================
- If insulation text appears → "Yes"
- If text is "bare", "none", or not found → "No"

=========================
TEMPERATURE & PRESSURE RULES
=========================
- Convert all temperatures to numeric °C
- Convert all pressures to MPa:
  • bar(g) → MPa = bar(g) / 10  
  • psi → MPa = psi × 0.00689476
- Working temperature/pressure = Operating temperature/pressure

=========================
OUTPUT RULES
=========================
- Output ONLY the JSON object
- No explanations, comments, or extra text
- Leave empty strings if a value is missing
- Ensure the JSON strictly matches the schema above

=========================
EXAMPLE OUTPUT
=========================
{
  "PARTS": "Hot Water Vessel",
  "PHASE": "Liquid",
  "FLUID": "Hot Water",
  "TYPE": { "category1": { "Top Head": "Carbon Steel", "Shell": "Carbon Steel", "Bottom Head": "Carbon Steel" }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "SPECIFICATION": { "category1": { "Top Head": "SA-516", "Shell": "SA-516", "Bottom Head": "SA-516" }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "GRADE": { "category1": { "Top Head": "70", "Shell": "70", "Bottom Head": "70" }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "INSULATION": { "category1": { "Top Head": "Yes", "Shell": "Yes", "Bottom Head": "Yes" }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "DESIGN_TEMPERATURE_C": { "category1": { "Top Head": 100, "Shell": 100, "Bottom Head": 100 }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "DESIGN_PRESSURE_MPa": { "category1": { "Top Head": 4.0, "Shell": 4.0, "Bottom Head": 4.0 }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "OPERATING_TEMPERATURE_C": { "category1": { "Top Head": 50, "Shell": 50, "Bottom Head": 50 }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } },
  "OPERATING_PRESSURE_MPa": { "category1": { "Top Head": 3.6, "Shell": 3.6, "Bottom Head": 3.6 }, "category2": { "Head": "", "Shell": "" }, "category3": { "Top Channel": "", "Shell": "", "Bottom Channel": "" }, "category4": { "Channel": "", "Shell": "", "Tube Bundle": "" } }
}
`;




    const payload = {
        contents: [{ text: prompt }]
    };

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const errData = await response.json();
            throw new Error(errData.error?.message || 'Gemini API error');
        }

        const result = await response.json();
        const candidate = result.candidates?.[0];
        const formattedText = candidate?.content?.[0]?.text || rawText;

        return formattedText;

    } catch (err) {
        console.error('Gemini formatting error:', err);
        updateStatus('Failed to format text. Showing raw OCR output.', 'error');
        return rawText;
    }
}


        function showCopySuccess() {
            copySuccessMsg.classList.remove('hidden');
            setTimeout(() => {
                copySuccessMsg.classList.add('hidden');
            }, 2000);
        }

        // Download as .txt
        downloadTxtBtn.addEventListener('click', () => {
            const text = outputText.value;
            const blob = new Blob([text], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `extracted_text_page_${pageNum}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

        // Download as .xlsx
        downloadExcelBtn.addEventListener('click', () => {
            try {
                const text = outputText.value;
                
                // --- Experimental Table Parsing Logic ---
                // This is a very basic heuristic. It assumes:
                // 1. Rows are separated by newlines.
                // 2. Columns are separated by two or more spaces (or a tab).
                // This will fail on many complex layouts.
                const lines = text.split('\n');
                const data = lines.map(line => line.split(/\s{2,}|\t/));
                // ------------------------------------------

                const ws = XLSX.utils.aoa_to_sheet(data);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Page ' + pageNum);
                
                // Write and download the file
                XLSX.writeFile(wb, `extracted_data_page_${pageNum}.xlsx`);

            } catch (err) {
                console.error('Excel export error:', err);
                updateStatus('Failed to generate Excel file.', 'error');
            }
        });

        document.getElementById('extract-tables').addEventListener('click', async () => {
    const file = fileUpload.files[0];
    if (!file) {
        updateStatus("Please upload a PDF first.", "error");
        return;
    }

    const formData = new FormData();
    formData.append('pdf', file);

    updateStatus("Extracting tables (Camelot)...", "info");

    try {
        const response = await fetch("http://localhost/yourproject/PdfController/extractTables", {
            method: "POST",
            body: formData
        });

        const result = await response.json();
        console.log("Tables:", result);

        updateStatus("Tables extracted! Downloading Excel...", "success");

        // Trigger browser download of returned Excel
        const a = document.createElement("a");
        a.href = "http://localhost/yourproject/uploads/last_camelot.xlsx";
        a.download = "camelot_tables.xlsx";
        a.click();

    } catch (e) {
        console.error(e);
        updateStatus("Failed to extract tables.", "error");
    }
});


    </script>
</body>
</html>