<div class="row company_contact_sections" id="company_contact_section_{{$key}}">
    @php($enable_cross = isset($enable_cross)?$enable_cross:true)
    
    @php($pointer_event = isset($pointer_event)?$pointer_event:'')
    <div class="col-md-3 {{$pointer_event}}" id="contact_person_div_{{$key}}">
        <div class="form-group">
            @php($not_id = isset($not_id)?$not_id:null)
            @php($CompanyContactUsers = \App\Helpers\Helper::getCompanyContactPersonIDName($id,$not_id))
            @php($selectedUserId = isset($selectedUserId)?$selectedUserId:null)
            @if(isset($selectedCompanyContactUsers) && count($selectedCompanyContactUsers))
                @php($CompanyContactUsers = $selectedCompanyContactUsers)
            @endif
            {!! Form::label('contact_person_ids['.$key.']', 'Name *:',array('class'=>'','for'=>'contact_person_id_'.$key),false) !!}
            {!! Form::select('contact_person_ids['.$key.']',$CompanyContactUsers,$selectedUserId,['class'=>'form-control select2 select_contact_person','placeholder'=>'Choose ...','id'=>'contact_person_id_'.$key,'data-key'=>$key,'required'=>'required']) !!}
            @if ($errors->has('contact_person_ids['.$key.']'))
                <span class="help-block">{{ $errors->first('contact_person_ids['.$key.']') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group contact_person_toggled_class_{{$key}}">        
            @php($selectedUserEmail = isset($selectedUserEmail)?$selectedUserEmail:null)
            {!! Form::label('contact_person_emails['.$key.']', 'contact person Email:',array('class'=>'','for'=>'contact_person_email_'.$key),false) !!}
            {!! Form::email('contact_person_emails['.$key.']',$selectedUserEmail,['class'=>'form-control','id'=>'contact_person_email_'.$key,'readonly'=>'readonly']) !!}
            @if ($errors->has('contact_person_emails['.$key.']'))
                <span class="help-block">{{ $errors->first('contact_person_emails['.$key.']') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group contact_person_toggled_class_{{$key}}">        
            @php($selectedUserPhone = isset($selectedUserPhone)?$selectedUserPhone:null)
            {!! Form::label('contact_person_phones['.$key.']', 'contact person Number:',array('class'=>'','for'=>'contact_person_phone_'.$key),false) !!}
            {!! Form::email('contact_person_phones['.$key.']',$selectedUserPhone,['class'=>'form-control','id'=>'contact_person_phone_'.$key,'readonly'=>'readonly']) !!}
            @if ($errors->has('contact_person_phones['.$key.']'))
                <span class="help-block">{{ $errors->first('contact_person_phones['.$key.']') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group contact_person_toggled_class_{{$key}}">        
            @php($selectedIsOwner = isset($selectedIsOwner)?$selectedIsOwner:0)
            {!! Form::label('is_owners['.$key.']', 'Is Owner *:',array('class'=>'','for'=>'is_owner_'.$key),false) !!}
            {!! Form::select('is_owners['.$key.']',['1'=>'Yes','0'=>'No'],$selectedIsOwner,['class'=>'form-control','id'=>'is_owner_'.$key,'required'=>'required']) !!}
            @if ($errors->has('is_owners['.$key.']'))
                <span class="help-block">{{ $errors->first('is_owners['.$key.']') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-1">
    @if($enable_cross)
        <div class="col-lg-12">
            <div class="form-group ">
            {!! Form::label('contact_person_action['.$key.']', 'remove',array('class'=>'','for'=>'contact_person_action_'.$key),false) !!}
                <a href="javascript:;" data-no="{{$key}}" class="contact_remove_section btn btn-danger"> <i class="fa fa-trash"></i></a>
            </div>
        </div>
    @endif
    </div>
</div>
