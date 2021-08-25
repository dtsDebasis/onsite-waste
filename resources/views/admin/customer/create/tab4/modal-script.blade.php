
<script>
    function getBranchPackageList(branch_id=0,purpose = 'list',modal_close = false){
        if(modal_close){
            $("#all_package_details .close").click();
        }
        var page_type = $('#page_type').val();
        $.ajax({
            url: '{{url("admin/customers/ajax-get-branch-package-list")}}',
            data: {
                company_id: '{{$id}}',branch_id:branch_id,purpose:purpose,page_type:page_type
            },
            type: 'GET',
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                if (data.success) {
                    $('#package_modal_head').html(data.mod_head_content);
                    $('#package_modal_body').html(data.html);
                    $('#all_package_details').modal('toggle');

                }
                else {
                    bootbox.alert({
                        title:"Package List",
                        message: data.msg ,
                        type:"error"
                    });
                }
            },
            error: function(data) {
                $('.loader').hide();
                    bootbox.alert({
                    title:"Package List",
                    message: data.msg ,
                    type:"error"
                });
            }
        });
    }
    $(document).ready(function () {

        $('body').on('click','#add_package',function(){
            getBranchPackageList(0,'form');
        });
        $('body').on('click','#assigned_contact_list', function(){
            var id = $(this).attr('data-id');
            $.ajax({
                url: '{{url("admin/customers/ajax-get-branch-assigned-contact-details")}}',
                data: {
                    company_id: '{{$id}}',branch_id:id
                },
                type: 'GET',
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    if (data.success) {
                        $('#package_modal_head').html('Contact List');
                        $('#package_modal_body').html(data.html);
                        $('#all_package_details').modal('show');
                    }
                    else {
                        bootbox.alert({
                            title:"Contact List",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                },
                error: function(data) {
                    $('.loader').hide();
                        bootbox.alert({
                        title:"Contact List",
                        message: data.msg ,
                        type:"error"
                    });
                }
            });
        });
        $( "#all_package_details" ).on('shown.bs.modal', function(){
            let value = $('#service_type').val();
            if(value == 'TE-5000'){
                $('.service-based-view').addClass('d-none');
            }
            else{
                $('.service-based-view').removeClass('d-none');
            }
        });
        $('body').on('click','#transaction_details', function(){
            var id = $(this).attr('data-id');
            let page_type = $('#page_type').val();
            $.ajax({
                url: '{{url("admin/customers/ajax-get-branch-transaction-details")}}',
                data: {
                    company_id: '{{$id}}',branch_id:id,page_type:page_type
                },
                type: 'GET',
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    if (data.success) {
                        $('#package_modal_head').html('Pricing Details');
                        $('#package_modal_body').html(data.html);
                        $('#all_package_details').modal('show');
                    }
                    else {
                        bootbox.alert({
                            title:"Package List",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                },
                error: function(data) {
                    $('.loader').hide();
                        bootbox.alert({
                        title:"Package List",
                        message: data.msg ,
                        type:"error"
                    });
                }
            });
        });
        $('body').on('click','#transactional_update', function(){
            let form_data = $("#transactional-form").serialize();
            $.ajax({
                url: '{{url("admin/customers/ajax-update-branch-transaction-details")}}',
                data: form_data,
                type: 'POST',
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    $('#all_package_details').modal('hide');
                    if (data.success) {
                        bootbox.alert({
                            title:"Price Details",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                    else {
                        bootbox.alert({
                            title:"Price Details",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                },
                error: function(data) {
                    $('.loader').hide();
                        bootbox.alert({
                        title:"Contact Assign",
                        message: data.msg ,
                        type:"error"
                    });
                }
            });
        });
        $('body').on('click','#package_listing',function(){
            var id = $(this).attr('data-id');
            //getBranchPackageList(id);
            getBranchPackageList(id,'list');
        });
        $('body').on('click','#contact_details',function(){
            $('.existing-assign-contact').html('Assign');
            $('.existing-assign-contact').removeClass('new_assigned');
            var branch_id = $(this).attr('data-id');
            var existing_assigned = $(this).attr('data-add_contacts');

            $('#branch-assign-to-contact-form').find('#selected_branch_id').val(branch_id);
            $('#branch-assign-to-contact-form').find('#assign_user_ids').val(existing_assigned);
            var existing_arr = existing_assigned.split(',');
            $.each(existing_arr,function(index,value){
                $('#existing_contact_list_td_'+value).html('<i class="fa fa-check"></i> Assigned');
                $('#existing_contact_list_td_'+value).addClass('new_assigned');
            });

            $('#all_assingn_unassign_contact_list').modal('show');
        });
        $('body').on('click','.existing-assign-contact',function(){
            $(this).toggleClass('new_assigned');
            if(($(this).text() == "Assign")){
                $(this).html('<i class="fa fa-check"></i> Assigned');
            }
            else{
                $(this).text("Assign");
            }
            let ids = [];
            $(".new_assigned").each(function(index,value) {
                ids.push($(this).attr('user_id'));
            });
            $('#branch-assign-to-contact-form').find('#assign_user_ids').val(ids.toString());
        });

        $('body').on('click','#contact_reassign_submit',function(){
            var packages = $('#branch-assign-to-contact-form').find('#assign_user_ids').val();
            if(packages == null || packages == undefined || packages == ''){
                bootbox.alert({
                    title:"Contact Assign",
                    message: 'Please select contact person.' ,
                    type:"error"
                });
            }
            else{
                bootbox.confirm({
                    message: "Are You Sure? Do you want to continue this action?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                    if(result){
                            $('#branch-assign-to-contact-form').submit();
                        }
                    }
                });
            }
        });

        $("body").on('click', '.add_contact_delete_item', function() {
            let user_id = $(this).attr('data-user_id');
            $('#contact_list_td_'+user_id).text('Assign');
            $('#contact_list_td_'+user_id).removeClass('btn-outline-primary pointer-none');
            $('#contact_list_td_'+user_id).addClass('btn-outline-secondary  waves-effect waves-light');
            $('#appended_contact_'+user_id).remove();
        });
        $(".assign-contact").on("click", function () {
            $(this).text("Assigned");
            $(this).removeClass("btn-outline-secondary  waves-effect waves-light");
            $(this).addClass("btn-outline-primary pointer-none");
            let user_id = $(this).attr('user_id');
            let company_id = $(this).attr('company_id');
            let user_name = $(this).attr('user_name');
            let user_email = $(this).attr('user_email');
            let user_designation = $(this).attr('user_designation');
            let user_phone = $(this).attr('user_phone');
            $('#contact_list_append').append(`
                <div class="col-md-4 bg-gradient appended_contact appendafter-${user_id}" id="appended_contact_${user_id}">
                    <input type="hidden" name="branch_users[]"  value="${user_id}" >
                    <div class="card contact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center ">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18   ">
                                        <i class="bx bx-user-circle"></i>
                                    </span>
                                </div>
                                <h6 class="font-size-14 mb-0">${user_name}
                                    <span class=" text-danger font-size-12 add_contact_delete_item del-item" data-user_id="${user_id}">
                                        <i class="bx bx-trash-alt"></i>
                                    </span>
                                </h6>
                            </div>
                            <div class="text-muted">
                                <div class="d-flex">
                                    <span class="ml-2 text-truncate">Designation: ${user_designation}</span>
                                </div>
                                <div class="d-flex">
                                    <span class="ml-2 text-truncate">Email: ${user_email}</span>
                                </div>
                                <div class="d-flex">
                                    <span class="ml-2 text-truncate">Phone: ${user_phone}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });
        $('body').on('click','.delete-branch-package',function(e){
            e.preventDefault();
            var id= $(this).attr('data-id');
            var company_id= $(this).attr('data-company_id');
            var branch_id= $(this).attr('data-branch_id');
            if(id == null || id == undefined || id == ''){
                bootbox.alert({
                    title:"Package Remove",
                    message: 'Details Not Found' ,
                    type:"error"
                });
            }
            else{
                bootbox.confirm({
                    message: "Are You Sure? Do you want to continue this action?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            $.ajax({
                                url: '{{url("admin/customers/ajax-post-branch-package-removed")}}',
                                data: {
                                    company_id: company_id,branch_id:branch_id,package_id:id
                                },
                                type: 'DELETE',
                                beforeSend: function() {
                                    //$('#all_package_details').modal('hide');
                                    $('.loader').show();
                                },
                                success: function(data) {
                                    $('.loader').hide();
                                    if (data.success) {
                                        $("#all_package_details .close").click();
                                        bootbox.alert({
                                            title:"Package Removed",
                                            message: data.msg ,
                                            type:"error"
                                        });
                                    }
                                    else {
                                        bootbox.alert({
                                            title:"Package Removed",
                                            message: data.msg ,
                                            type:"error"
                                        });
                                    }
                                },
                                error: function(data) {
                                    $('.loader').hide();
                                    bootbox.alert({
                                        title:"Package Removed",
                                        message: data.msg ,
                                        type:"error"
                                    });
                                }
                            });
                        }
                    }
                });
            }

        });
        $('body').on('click','#brnch_pck_update', function(e){
            e.preventDefault();
            let form_data = $("#branch-package-form").serialize()
            $.ajax({
                url: '{{url("admin/customers/ajax-update-branch-package-details")}}',
                data: form_data,
                type: 'PATCH',
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    if (data.success) {
                        $('#all_package_details').modal('hide');
                        bootbox.alert({
                            title:"Package Details",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                    else {
                        bootbox.alert({
                            title:"Package Details",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                },
                error: function(data) {
                    $('.loader').hide();
                        bootbox.alert({
                        title:"Package Details",
                        message: data.msg ,
                        type:"error"
                    });
                }
            });
        });
        $('body').on('click','.add_package_delete_item',function(e){
            e.preventDefault();
            let package_id = $(this).attr('data-package_id');
            $('#appended_package_'+package_id).remove();
            $('.clone-package').removeClass('branch-package-assigned');
            $('.clone-package').text('Assign');
        });
        $('body').on("click",".clone-package",function (e) {
            e.preventDefault();
            let packageid = $(this).attr('packageid');
            $('.clone-package').removeClass('branch-package-assigned');
            $('.clone-package').text('Assign');

            $(this).addClass('branch-package-assigned');
            $(this).html('<i class="fa fa-check"></i> Assigned');
            let purpose = $(this).attr('data-purpose');
            if(purpose == 'form'){
                let price = $('#package_price_'+packageid).val();
                $('#form_package_price').val(price);
                let name = $(this).attr('data-name');
                let monthly_rate = $(this).attr('data-monthly_rate');
                let frquency_number = $(this).attr('data-frquency_number');
                let box_included = $(this).attr('data-box_included');
                $('#package_list_append').html(`
                    <div class="col-md-4 bg-gradient appended_package appendafter-${packageid}" id="appended_package_${packageid}">
                        <input type="hidden" name="package_id"  value="${packageid}" >
                        <div class="card contact-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center ">
                                    <div class="avatar-xs mr-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18   ">
                                            <i class="bx bx-user-circle"></i>
                                        </span>
                                    </div>
                                    <h6 class="font-size-14 mb-0">${name}
                                        <span class=" text-danger font-size-12 add_package_delete_item del-item-package" data-package_id="${packageid}">
                                            <i class="bx bx-trash-alt"></i>
                                        </span>
                                    </h6>
                                </div>
                                <div class="text-muted">
                                    <div class="d-flex">
                                        <span class="ml-2 text-truncate">Frquency Number: ${frquency_number}</span>
                                    </div>
                                    <div class="d-flex">
                                        <span class="ml-2 text-truncate">Box Included: ${box_included}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
        $('body').on("click","#cloned_submit",function (e) {
            e.preventDefault();
            let ids = [];
            let package_price = [];
            let company_id = $(this).attr('data-company_id');
            let branch_id = $(this).attr('data-branch_id');
            $(".branch-package-assigned").each(function(index,value) {
                let package_id = $(this).attr('packageid');
                ids.push($(this).attr('packageid'));
                package_price.push($('#package_price_'+package_id).val());
            });
            if(ids == null || ids == undefined || ids == ''){
                bootbox.alert({
                    title:"Add Package",
                    message: 'Please select packages.' ,
                    type:"error"
                });
            }
            else{
                bootbox.confirm({
                    message: "Are You Sure? Do you want to continue this action?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            $.ajax({
                                url: '{{url("admin/customers/ajax-post-branch-package-clone")}}',
                                data: {
                                    company_id: company_id,branch_id:branch_id,package_ids:ids.toString(),package_price:package_price.toString()
                                },
                                type: 'POST',
                                beforeSend: function() {
                                    //$('#all_package_details').modal('hide');
                                    $('.loader').show();
                                },
                                success: function(data) {
                                    $('.loader').hide();
                                    if (data.success) {
                                        $("#all_package_details .close").click();
                                        bootbox.alert({
                                            title:"Package Assign",
                                            message: data.msg ,
                                            type:"error"
                                        });
                                    }
                                    else {
                                        bootbox.alert({
                                            title:"Package Assign",
                                            message: data.msg ,
                                            type:"error"
                                        });
                                    }
                                },
                                error: function(data) {
                                    $('.loader').hide();
                                    bootbox.alert({
                                        title:"Package Assign",
                                        message: data.msg ,
                                        type:"error"
                                    });
                                }
                            });
                        }
                    }
                });
            }
        });

        $('body').on('click','.edit-branch-package',function(e){
            e.preventDefault();
            var id= $(this).attr('data-id');
            let company_id = $(this).attr('data-company_id');
            let branch_id = $(this).attr('data-branch_id');
            if(id == null || id == undefined || id == ''){
                bootbox.alert({
                    title:"Package Remove",
                    message: 'Details Not Found' ,
                    type:"error"
                });
            }
            else{
                $.ajax({
                    url: '{{url("admin/customers/ajax-get-branch-package-details")}}',
                    data: {
                        company_id: company_id,branch_id:branch_id,package_id:id
                    },
                    type: 'GET',
                    beforeSend: function() {
                        //$('#all_package_details').modal('hide');
                        $('.loader').show();
                    },
                    success: function(data) {
                        $('.loader').hide();
                        if (data.success) {
                            $("#all_package_details .close").click();
                            //getBranchPackageList(branch_id);
                            $('#edit_package_modal_body').html(data.html);
                            $('#edit_package_details').modal('show');
                        }
                        else {
                            bootbox.alert({
                                title:"Package Removed",
                                message: data.msg ,
                                type:"error"
                            });
                        }
                    },
                    error: function(data) {
                        $('.loader').hide();
                        bootbox.alert({
                            title:"Package Removed",
                            message: data.msg ,
                            type:"error"
                        });
                    }
                });
            }

        });

    });
</script>
