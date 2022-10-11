<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <script>
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args);
                }, timeout);
            };
        }

        window.initMarkdownEditor = function () {
            let editor = new EasyMDE({
                autoDownloadFontAwesome: false,
                element: this.$refs.editor,
                uploadImage: true,
                placeholder: '{{ __('Start writing…') }}',
                initialValue: this.markdown,
                spellChecker: false,
                autoSave: false,
                status: [{
                    className: "upload-image",
                    defaultValue: ''
                }],
                toolbar: [
                    "heading", "bold", "italic", "link",
                    "|",
                    "quote", "unordered-list", "ordered-list", "table",
                    "|",
                    {
                        name: "upload-image",
                        action: EasyMDE.drawUploadedImage,
                        className: "fa fa-image",
                    },
                    "undo",
                    { // When FontAwesome is not auto downloaded, this loads the correct icon
                        name: "redo",
                        action: EasyMDE.redo,
                        className: "fa fa-redo",
                        title: "Redo",
                    },
                ],
                {{--
                    imageAccept: 'image/png, image/jpeg, image/gif, image/avif',
                    imageUploadFunction: function(file, onSuccess, onError) {
                        if (file.size > 1024 * 1024 * 2) {
                            return onError('File cannot be larger than 2MB.');
                        }

                        if (file.type.split('/')[0] !== 'image') {
                            return onError('File must be an image.');
                        }

                        const data = new FormData();
                        data.append('file', file);

                        fetch('{{ action(\Spatie\Mailcoach\Http\Api\Controllers\UploadsController::class) }}', {
                            method: 'POST',
                            body: data,
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                        })
                            .then(response => response.json())
                            .then(({ success, file }) => {
                                if (! success) {
                                    return onError();
                                }

                                onSuccess(file.url);
                            });
                    },

                    --}}
            });

            editor.codemirror.on("change", debounce(() => {
                this.markdown = editor.value();
                this.$refs.editor.dirty = true;
            }));
        }
    </script>

    <div wire:ignore
         x-data="{
            markdown: @entangle($getStatePath()),
            init: initMarkdownEditor,
        }">
        <textarea x-ref="editor"></textarea>
    </div>
</x-forms::field-wrapper>
