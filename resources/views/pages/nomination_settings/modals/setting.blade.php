<!-- Modal -->
<div class="modal fade" id="astd-setting-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="astd-setting-label"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <form>
                <div id="div-astd-setting-modal-error" class="mb-3">

                </div>
                <div class="mb-3">
                    <input type="hidden"  class="form-control" id="setting-status" value="">
                    <input type="hidden"  class="form-control" id="setting-key" value="">
                    <input type="hidden"  class="form-control" id="setting-id" value="">
                    <select class="form-select form-select-lg mb-3" id="astd-setting-role" aria-label=".form-select-lg example">
                        <option value="">Select Role ...</option>
                        @if (isset($beneficiary_roles) && count($beneficiary_roles) > 0)

                            @foreach ($beneficiary_roles as $role)
                                <option value="{{ $role->name }}"> {{ $role->name }} </option>
                            @endforeach
                            
                        @endif

                    </select>
                </div>
            </form>
          
        </div>
        <div class="">
          <div class="row m-2">
              <div class="col-6">  <button type="button" class="btn btn-secondary float-start" data-bs-dismiss="modal">Close</button> </div>
              <div class="col-6"> 
                <button type="button" id="save-astd-setting" class="btn btn-primary float-end">
                  <span id="astd-setting-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Save changes
                </button> 
              </div>
          </div>
         
          
        </div>
      </div>
    </div>
  </div>