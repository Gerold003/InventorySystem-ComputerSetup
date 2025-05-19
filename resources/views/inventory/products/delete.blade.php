<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger py-3">
                <h5 class="modal-title text-white" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Delete Product
                </h5>
                
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5 class="font-weight-bold">Delete Confirmation</h5>
                </div>
                
                <p class="text-center mb-3">Are you sure you want to delete this product?</p>
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Product Details:</strong>
                    </div>
                    <p class="mb-0 pl-4" id="deleteProductName"></p>
                </div>
                
                <div class="alert alert-danger mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <small class="font-weight-medium">
                            This action cannot be undone. All related data will be permanently deleted.
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDelete">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    #deleteModal .modal-content {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    #deleteModal .modal-header {
        border-bottom: none;
    }
    
    #deleteModal .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    
    #deleteModal .alert {
        border-radius: 0.5rem;
    }
    
    #deleteModal .btn {
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    #deleteModal .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    #deleteModal .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    #deleteModal .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    #deleteModal .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    #deleteModal .close {
        opacity: 0.75;
        text-shadow: none;
    }
    
    #deleteModal .close:hover {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteForm = null;
    const deleteModal = document.getElementById('deleteModal');

    if (!deleteModal) {
        console.error('Delete modal element not found');
        return;
    }

    window.confirmDelete = function(formId, productName) {
        console.log('Confirming delete for product:', productName);
        
        deleteForm = document.getElementById(formId);
        if (!deleteForm) {
            console.error('Delete form not found:', formId);
            return;
        }

        document.getElementById('deleteProductName').textContent = productName;
        
        // Try Bootstrap 5 first, fall back to Bootstrap 4
        try {
            const bsModal = new bootstrap.Modal(deleteModal);
            bsModal.show();
        } catch (e) {
            console.log('Bootstrap 5 modal failed, trying Bootstrap 4:', e);
            try {
                $(deleteModal).modal('show');
            } catch (e2) {
                console.error('Both Bootstrap 5 and 4 modal initialization failed:', e2);
                alert('Error showing delete confirmation. Please try again.');
            }
        }
    };

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!deleteForm) {
            console.error('No delete form selected');
            return;
        }

        console.log('Submitting delete form:', deleteForm.action);
        
        try {
            deleteForm.submit();
        } catch (e) {
            console.error('Error submitting delete form:', e);
            alert('Error deleting product. Please try again.');
        }
        
        // Try to hide modal using both Bootstrap 5 and 4 methods
        try {
            const bsModal = bootstrap.Modal.getInstance(deleteModal);
            if (bsModal) {
                bsModal.hide();
            } else {
                $(deleteModal).modal('hide');
            }
        } catch (e) {
            console.error('Error hiding modal:', e);
            try {
                $(deleteModal).modal('hide');
            } catch (e2) {
                console.error('Both Bootstrap 5 and 4 modal hide failed:', e2);
            }
        }
    });
});
</script>
@endpush 