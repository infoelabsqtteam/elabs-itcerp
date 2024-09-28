@if(!empty($viewData))	
    @if(array_key_exists('creditNoteHeader', $viewData))  
        @if(!empty($viewData['creditNoteHeader']['credit_note_type_id']))
            @if($viewData['creditNoteHeader']['credit_note_type_id'] == '1')
                @include('payments.credit_notes.generateAutoCreditNotePdf')
            @elseif($viewData['creditNoteHeader']['credit_note_type_id'] == '2')
                @include('payments.credit_notes.generateManualCreditNotePdf')
            @endif
        @endif
    @endif
@endif