$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("body").on("click", ".show-panel", function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $("#" + id).slideToggle();
    });

    $("#myModal").on("show.bs.modal", function (e) {
        var link = $(e.relatedTarget);
        if (link.data('layout')) {
            $(this).find(".modal-dialog").removeClass('modal-md');
            $(this).find(".modal-dialog").addClass('modal-lg');
        } else {
            $(this).find(".modal-dialog").removeClass('modal-lg');
            $(this).find(".modal-dialog").addClass('modal-md');
        }
        $(this).find(".modal-content").html('Please wait...');
        $(this).find(".modal-content").load(link.attr("href"));
    });

    $('body').on('click', '[data-confirm]', function() {
        var me = $(this),
            me_data = me.data('confirm');

        me_data = me_data.split("|");
        Swal.fire({
            title: me_data[0],
            text: me_data[1],
            // icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0AB1AD',
            cancelButtonColor: '#f46a6a',
            padding:'1rem',
            width: '24rem',
            confirmButtonText: 'Yes, Sure!',
            customClass:{
                title: 'text-warning'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
          }).then((result) => {
            if (result.isConfirmed) {
                eval(me.data('confirm-yes'));
            }
          })
    });

    $('body').on('click', '[data-confirm-reject]', function() {
        var me = $(this),
            me_data = me.data('confirm-reject');

        me_data = me_data.split("|");
        Swal.fire({
            title: me_data[0],
            text: me_data[1],
            icon: 'warning',
            input: 'textarea',
            inputLabel: 'Please specify your reason for rejection.',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Sure!',
            inputValidator: (value) => {
              if (!value) {
                return 'Please Enter Reject Reason'
              }
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
          }).then((result) => {
            if (result.isConfirmed) {
                console.log(result);
                $('#reject_reason').val(result.value);
                eval(me.data('confirm-yes'));
            }
          })
    });
});

function showNotification(type, text, placementFrom, placementAlign, animateEnter, animateExit) {
    if (type === null || type === '') { type = 'success'; }
    if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
    if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
    if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
    var allowDismiss = true;

    toastr[type](text)

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}