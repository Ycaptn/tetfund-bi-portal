{{-- new beneficiary member modal --}}
<div class="modal fade" id="mdl-beneficiary-member-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-beneficiary-modal-title" class="modal-title"><span class="opposite_create">Create</span> Beneficiary User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-sm-12 alert alert-info">
                    <i class=""><strong>NOTE:</strong> <span id='opposite_creating'>You will be creating a new</span> beneficiary user for <strong>{{ ucwords(strtolower($beneficiary->full_name)); }}</strong></i>
                </div>
                <div id="div-beneficiary-member-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="form-beneficiary-member-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-sm-12 m-3" id="div_beneficiary_member_fields">
                            @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_fields')
                        </div>

                        <div class="col-sm-12 m-3" id="div_beneficiary_member_show_fields">
                            @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_show_fields')
                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-beneficiary-member-modal">
                <button type="button" class="btn btn-primary" id="btn-save-beneficiary-member-modal" value="add">
                    <div id="spinner-beneficiary-member" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Process & <span class="opposite_create">Create</span>
                </button>
                <button type="button" class="btn btn-primary" id="btn-dismiss-beneficiary-member-preview-modal" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>

        </div>
    </div>
</div>