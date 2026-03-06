class FormGenerator{
    constructor(formClassName = null){
        this.fieldType = null;
        this.totalField = 0;
        
        if (this.formClassName) {
            this.formClassName = '.'+formClassName;
        }else{
            this.formClassName = '.generate-form';
        }
    }

    extraFields(fieldType){
        this.fieldType = fieldType;
        if (this.fieldType == 'file') {
            var field = `<select name="extensions" class="select2 form-select" multiple required>
                            <option value="jpg">JPG</option>
                            <option value="jpeg">JPEG</option>
                            <option value="png">PNG</option>
                            <option value="pdf">PDF</option>
                            <option value="doc">DOC</option>
                            <option value="docx">DOCX</option>
                            <option value="txt">TXT</option>
                            <option value="xlx">XLX</option>
                            <option value="xlsx">XLSX</option>
                            <option value="csv">CSV</option>
                        </select>`;
                        
            var title = `File Extensions`;
        }else{
            var field = `<div class="options">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="options[]" required>
                                <span class="input-group-text cursor-pointer btn-label-primary addOption">
                                    <span class="tf-icons las la-plus-circle me-1"></span>
                                </span>
                            </div>
                        </div>`;
            var title = `Add Options`;
        }

        var html = `<div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">${title}</label>
                        <div class="col-sm-9 select2-design">
                            ${field}
                        </div>        
                    </div>`;
        if(this.fieldType == 'text' || this.fieldType == 'textarea' || this.fieldType == ''){
            html = '';
        }

        return html;
    }

    addOptions(){
        return `<div class="input-group mb-2">
                    <input type="text" class="form-control" name="options[]" required>
                    <span class="input-group-text cursor-pointer btn-label-danger removeOption">
                        <span class="tf-icons las la-times-circle me-1"></span>
                    </span>
                </div>`;
    }

    formsToJson(form){
        var extensions = null;
        var options = [];
        this.fieldType = form.find('[name=form_type]').val();
        if(this.fieldType == 'file'){
            extensions = form.find('[name=extensions]').val();
        }

        if(this.fieldType == 'select' || this.fieldType == 'checkbox' || this.fieldType == 'radio'){
            var options = $("[name='options[]']").map(function(){return $(this).val();}).get();
        }
        var formItem = {
            type:this.fieldType,
            is_required:form.find('[name=is_required]').val(),
            label:form.find('[name=form_label]').val(),
            extensions:extensions,
            options:options,
            old_id:form.find('[name=update_id]').val()
        };
        return formItem;
    }

    makeFormHtml(formItem,updateId){
        if (formItem.old_id) {
            updateId = formItem.old_id;
        }
        var hiddenFields = `
            <input type="hidden" name="form_generator[is_required][]" value="${formItem.is_required}">
            <input type="hidden" name="form_generator[extensions][]" value="${formItem.extensions}">
            <input type="hidden" name="form_generator[options][]" value="${formItem.options}">
        `;
        var formsHtml = `
            ${hiddenFields}
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">Label</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="form_generator[form_label][]" value="${formItem.label}" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">Type</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="form_generator[form_type][]" value="${formItem.type}" readonly>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-label-primary editFormData" data-form_item='${JSON.stringify(formItem)}' data-update_id="${updateId}">
                    <span class="tf-icons las la-pen me-1"></span></i> Edit
                </button>
                <button type="button" class="btn btn-label-warning removeFormData">
                    <i class="las la-trash me-1"></i> Delete
                </button>
            </div>
        `;
        var html = `<div class="col-xxl-4 col-md-6">
                        <div class="card border p-3" id="${updateId}">
                            ${formsHtml}
                        </div>
                    </div>`;
    
        if(formItem.old_id){
            html = formsHtml;
            $(`#${formItem.old_id}`).html(html);
        }else{
            $('.addedField').append(html);
        }
    }

    formEdit(element){
        this.showModal()
        var formItem = element.data('form_item');
        var form = $(this.formClassName);
        form.find('[name=form_type]').val(formItem.type);
        form.find('[name=form_label]').val(formItem.label);
        form.find('[name=is_required]').val(formItem.is_required);
        form.find('[name=update_id]').val(element.data('update_id'))
        var html = '';
        if (formItem.type == 'file') {
            html += `<div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">File Extensions</label>
                        <div class="col-sm-9 select2-design">
                            <select name="extensions" class="select2 form-select" multiple required>
                                <option value="jpg">JPG</option>
                                <option value="jpeg">JPEG</option>
                                <option value="png">PNG</option>
                                <option value="pdf">PDF</option>
                                <option value="doc">DOC</option>
                                <option value="docx">DOCX</option>
                                <option value="txt">TXT</option>
                                <option value="xlx">XLX</option>
                                <option value="xlsx">XLSX</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>        
                    </div>`;
        }
        
        var i = 0;
        var optionItem = '';
        formItem.options.forEach(option => {
            var isRemove = '';

            if(i != 0){
                isRemove = `
                    <span class="input-group-text cursor-pointer btn-label-danger removeOption">
                        <span class="tf-icons las la-times-circle me-1"></span>
                    </span>
                    `;
            }

            if (i == 0) {
                isRemove = `
                    <span class="input-group-text cursor-pointer btn-label-primary addOption">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                    </span>
                    `;
            }
            
            i += 1;
            optionItem += `
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="options[]" value="${option}" required>
                    ${isRemove}
                </div>
            `;
        });

        if (formItem.type == 'select' || formItem.type == 'checkbox' || formItem.type == 'radio') {
            html += `
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label required">Add Options</label>
                    <div class="col-md-8 select2-design">
                        <div class="options">
                            ${optionItem}
                        </div>
                    </div>
                </div>
            `;
        }

        $('.generatorSubmit').text('Update');
        $('.extra_area').html(html);
        $('.extra_area').find('select').val(formItem.extensions);

    }

    resetAll(){
        $(formGenerator.formClassName).trigger("reset");
        $('.extra_area').html('');
        $('.generatorSubmit').text('Add');
        $('[name=update_id]').val('');
        
    }

    closeModal(){
        var modal = $('#formGenerateModal');
        modal.modal('hide');
    }

    showModal(){
        this.resetAll();
        var modal = $('#formGenerateModal');
        modal.modal('show');
    }
}