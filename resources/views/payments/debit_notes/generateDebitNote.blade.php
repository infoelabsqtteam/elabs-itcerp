@if(!empty($viewData))	
    @if(array_key_exists('debitNoteHeader', $viewData))  
        @if(!empty($viewData['debitNoteHeader']['debit_note_type_id']))
            @if($viewData['debitNoteHeader']['debit_note_type_id'] == '1')
                @include('payments.debit_notes.generateAutoDebitNotePdf')
            @elseif($viewData['debitNoteHeader']['debit_note_type_id'] == '2')
                @include('payments.debit_notes.generateManualDebitNotePdf')
            @endif
        @endif
    @endif
@endif