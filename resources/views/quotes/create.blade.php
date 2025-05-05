<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Quote</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Form styles */
        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .form-textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            min-height: 100px;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* Quotes preview styles */
        .quote-preview {
            background-color: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
        }

        .quote-preview-text {
            font-style: italic;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .quote-preview-author {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>

<body class="bg-blue-100">
    <div class="container mx-auto p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('quotes.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ← Back
            </a>
            <h1 class="text-2xl font-bold text-gray-800 ml-4">Add New Quote</h1>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('quotes.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="quote" class="block text-sm font-medium text-gray-700">Quote Text</label>
                    <textarea id="quote" name="quote" class="form-textarea" placeholder="Enter the quote text">{{ old('quote') }}</textarea>
                    @error('quote')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                    <input type="text" id="author" name="author" class="form-input" placeholder="Enter the author's name" value="{{ old('author') }}">
                    @error('author')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Preview</label>
                    <div class="quote-preview mt-2">
                        <p class="quote-preview-text" id="previewText">"The quote will appear here as you type."</p>
                        <p class="quote-preview-author" id="previewAuthor">— Author Name</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('quotes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2 hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save Quote</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quoteInput = document.getElementById('quote');
            const authorInput = document.getElementById('author');
            const previewText = document.getElementById('previewText');
            const previewAuthor = document.getElementById('previewAuthor');

            // Update preview as user types
            quoteInput.addEventListener('input', updatePreview);
            authorInput.addEventListener('input', updatePreview);

            function updatePreview() {
                const text = quoteInput.value.trim();
                const author = authorInput.value.trim();
                
                previewText.textContent = text ? `"${text}"` : "\"The quote will appear here as you type.\"";
                previewAuthor.textContent = author ? `— ${author}` : "— Author Name";
            }

            // Initialize preview with any existing values
            updatePreview();
        });
    </script>
</body>
</html>
