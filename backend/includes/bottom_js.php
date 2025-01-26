<!--<script src="./assets/js/jquery.js"></script>-->
<!--<script src="./assets/js/jquery-2.2.0.min.js"></script>-->
<script src="./assets/js/jquery/jquery-2.0.3.min.js"></script>
<script src="./assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="./assets/js/main.js"></script>
<!-- <script src="./assets/js/toggle.js"></script> -->
<script src="./assets/js/bootstrap5-toggle.ecmas.min.js"></script>
<script src="./assets/js/jquery.toast.min.js"></script>
<script src="./assets/js/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // required elements
        var imgPopup = $('.img-popup');
        var imgCont = $('.container__img-holder');
        var popupImage = $('.img-popup img');
        var closeBtn = $('.close-btn');

        // handle events
        imgCont.on('click', function() {
            var img_src = $(this).children('img').attr('src');
            imgPopup.children('img').attr('src', img_src);
            imgPopup.addClass('opened');
        });

        $(imgPopup, closeBtn).on('click', function() {
            imgPopup.removeClass('opened');
            imgPopup.children('img').attr('src', '');
        });

        popupImage.on('click', function(e) {
            e.stopPropagation();
        });

    });
    $(document).ready(function() {
        //App.setPage("index");  //Set current page
        //App.init(); //Initialise plugins and elements
        $(".multiple_select").select2();
        //FormWizard.init();
    });
    $('.close').on('click', function() {
        $('.alert').hide();
    });
</script>
<script>
    $(document).ready(function() {
        // Listen for toggle changes
        $('#myToggle').change(function() {
            if ($(this).prop('checked')) {
                // Show success toast
                $.toast({
                    heading: 'Success',
                    text: 'Toggle is ON',
                    icon: 'success',
                    position: 'top-right'
                });
            } else {
                // Show error toast
                $.toast({
                    heading: 'Warning',
                    text: 'Toggle is OFF',
                    icon: 'warning',
                    position: 'top-right'
                });
            }
        });
    });
</script>
<script>
    ClassicEditor
        .create(document.querySelector('.ckeditor_one'), {
            toolbar: {
                items: [
                    'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                ]
            },
            removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
        })
        .then(editor => {
            editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
            editor.ui.view.editable.element.style.color = 'black';
        })
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('.ckeditor_two'), {
            toolbar: {
                items: [
                    'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                ]
            },
            removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
        })
        .then(editor => {
            editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
            editor.ui.view.editable.element.style.color = 'black';
        })
        .catch(error => {
            console.error(error);
        });

        ClassicEditor
            .create(document.querySelector('.ckeditor_one_de'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });
            
        ClassicEditor
            .create(document.querySelector('.ckeditor_one_en'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('.ckeditor_two_de'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('.ckeditor_two_en'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });
</script>