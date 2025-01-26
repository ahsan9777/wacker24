<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CKEditor 5 - Classic editor</title>
    <script src="assets/js/ckeditor.js"></script>
</head>
<body>
    <h1>Classic editor</h1>
    <textarea name="content" id="editor">
        hello i am heare
    </textarea>
    <script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 
                     'undo', 'redo' // Exclude 'imageUpload' or 'image'
                ]
            },
            removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed']
        })
        .catch(error => {
            console.error(error);
        });
</script>

</body>
</html>