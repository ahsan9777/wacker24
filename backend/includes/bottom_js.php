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
        //App.setPage("index");  //Set current page
        //App.init(); //Initialise plugins and elements
        $(".multiple_select").select2();
        //FormWizard.init();
    });
    $('.close').on('click', function(){
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