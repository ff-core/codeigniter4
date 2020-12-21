class Cadastros {
    constructor() {
        this.loadGride();

        if (document.getElementById('btnVoltar')){
			document.getElementById('btnVoltar').addEventListener('click', function(e) {
 				this.btnVoltar(e);
				e.preventDefault();
			}.bind(this));
        }

        if (document.getElementById('btnConsultar')){
			document.getElementById('btnConsultar').addEventListener('click', function(e) {
 				this.btnConsultar(e);
				e.preventDefault();
			}.bind(this));
        }      

        /**
         * Inicio
         * Botoes para envio de informação ao servidor
         */
        if (document.getElementById('idBtnSaveAdd'+alias)){
			document.getElementById('idBtnSaveAdd'+alias).addEventListener('click', function(e) {
 				this.btnClickSaveAdd(e);
				e.preventDefault();
			}.bind(this));
        }
        
        if (document.getElementById('idBtnSaveEdit'+alias)){
			document.getElementById('idBtnSaveEdit'+alias).addEventListener('click', function(e) {
				this.btnClickSaveEdit(e);
				e.preventDefault();
			}.bind(this));
        }

        if (document.getElementById('idBtnCancelAdd'+alias)){
			document.getElementById('idBtnCancelAdd'+alias).addEventListener('click', function(e) {
				this.btnClickCancel(e);
				e.preventDefault();
			}.bind(this));
        }
        
        if (document.getElementById('idBtnCancelEdit'+alias)){
			document.getElementById('idBtnCancelEdit'+alias).addEventListener('click', function(e) {
				this.btnClickCancel(e);
				e.preventDefault();
			}.bind(this));
        }

        if (document.getElementById('idBtnCancelDelete'+alias)){
			document.getElementById('idBtnCancelDelete'+alias).addEventListener('click', function(e) {
				this.btnClickCancel(e);
				e.preventDefault();
			}.bind(this));
        }

        if (document.getElementById('idBtnCancelView'+alias)){
			document.getElementById('idBtnCancelView'+alias).addEventListener('click', function(e) {
				this.btnClickCancel(e);
				e.preventDefault();
			}.bind(this));
        }
        
        if (document.getElementById('idBtnDelete'+alias)){
			document.getElementById('idBtnDelete'+alias).addEventListener('click', function(e) {
				this.btnClickSaveDelete(e);
				e.preventDefault();
			}.bind(this));
        }
        /**
         * Fim
         * Botoes para envio de informação ao servidor
         */
    }

    btnVoltar(e){
        history.go(-1);
    }

    btnConsultar(e){
        var data = new FormData(e.target.form);
        this.loadGride(data);
    }

    loadGride(form = ''){
        /*var data = new FormData();
        data.append('action','GetAll');
        var retorno = function(xhr){
            console.log(xhr.responseText);
            var json = JSON.parse(xhr.responseText)
            console.log(json)
        }
        this.post(document.URL,data,retorno)*/

        if(form === '')
            form = new FormData();
        form.append('action','GetAll');

        var obj = {};
        for(var key of form.keys()){
            obj[key] = form.get(key)
        }

        var self = this
        this.dataTable = $('#datatable-primary').DataTable({
            'processing': true,
            'destroy': true,
            'ajax': { 
                'url': document.URL,
                'type': "POST",
                "datatype": "JSON",
                'data': obj
            },
            'order': [[ 0, "desc" ]],
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
            "drawCallback": function (oSettings, json) {
                /**
                 * Inicio
                 * Botoes para buscar informação ao servidor do registro
                 */
                if (document.getElementsByClassName('idActionEdit'+alias)){
                    var Selection = document.getElementsByClassName('idActionEdit'+alias);

                    for(let i = 0; i < Selection.length; i++) {
                        Selection[i].addEventListener('click', function(e) {
                            self.btnClickBuscarParaEditar(e);
                            e.preventDefault();
                        }.bind(self));
                    }		
                }

                if (document.getElementsByClassName('idActionView'+alias)){
                    var Selection = document.getElementsByClassName('idActionView'+alias);

                    for(let i = 0; i < Selection.length; i++) {
                        Selection[i].addEventListener('click', function(e) {
                            self.btnClickBuscarParaVisualizar(e);
                            e.preventDefault();
                        }.bind(self));
                    }
                }

                if (document.getElementsByClassName('idActionDelete'+alias)){
                    var Selection = document.getElementsByClassName('idActionDelete'+alias);

                    for(let i = 0; i < Selection.length; i++) {
                        Selection[i].addEventListener('click', function(e) {
                            self.btnClickBuscarParaDeletar(e);
                            e.preventDefault();
                        }.bind(self));
                    }
                }
                /**
                 * Fim
                 * Botoes para buscar informação ao servidor do registro
                 */
            }
        });
    }

    refreshGride(){
        this.dataTable.ajax.reload();
    }

    /**
     * Inicio
     * Funcoes para envio de inforaçao ao servidor
     */
    btnClickSaveAdd(e) {
        var self = this
        var form = e.target.form
        var formData = new FormData(form);
        var mostraResultadoAdd = function (xhr){
            var json = JSON.parse(xhr.responseText)
            var div = document.createElement('div')
            if(json.status == 'false'){
                var error = ''
                for(var k in json.errors) {
                    error = error.concat(json.errors[k], '<br/>')
                }
                
                div.innerHTML = 
                `<div class="alert alert-warning" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading">Ops!</h4>
                    <p>${json.message}</p>
                    <hr>
                    <p class="mb-0">${error}</p>
                </div>`;
                var modal = document.getElementById('modalAdd'+alias)
                modal.insertBefore(div, modal.childNodes[0])
            } else {
                div.innerHTML = 
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Wow!</strong> ${json.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> `;
                form.reset()
                //$('#modalAdd'+alias).modal('hide');
                bootstrap.Modal.getInstance(document.getElementById('modalAdd'+alias)).hide();
                self.refreshGride();
                var alert = document.getElementById('regionTable')
                alert.insertBefore(div, alert.childNodes[0])
            }
            
        };
        this.post(e.target.formAction, formData, mostraResultadoAdd);   
    }

    btnClickSaveEdit(e) {
        var self = this
        var form = e.target.form
        var formData = new FormData(form);
        var mostraResultadoEdit = function (xhr){
            var json = JSON.parse(xhr.responseText)
            var div = document.createElement('div')
            
            if(json.status == 'false'){
                var error = ''
                for(var k in json.errors) {
                    error = error.concat(json.errors[k], '<br/>')
                }
                
                div.innerHTML = 
                `<div class="alert alert-warning" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading">Ops!</h4>
                    <p>${json.message}</p>
                    <hr>
                    <p class="mb-0">${error}</p>
                </div>`;
                var modal = document.getElementById('modalEdit'+alias)
                modal.insertBefore(div, modal.childNodes[0])
            } else {
                div.innerHTML = 
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Wow!</strong> ${json.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> `;
                form.reset()
                //$('#modalEdit'+alias).modal('hide');
                bootstrap.Modal.getInstance(document.getElementById('modalEdit'+alias)).hide();
                self.refreshGride();
                var alert = document.getElementById('regionTable')
                alert.insertBefore(div, alert.childNodes[0])
            }
            
        };
        
        this.post(e.target.formAction, formData, mostraResultadoEdit);
    }

    btnClickSaveDelete(e) {
        var self = this
        var form = e.target.form
        var formData = new FormData(form);
        var mostraResultadoDelete = function (xhr){
            var json = JSON.parse(xhr.responseText)
            var div = document.createElement('div')
            
            if(json.status == 'false'){
                var error = ''
                for(var k in json.errors) {
                    error = error.concat(json.errors[k], '<br/>')
                }
                
                div.innerHTML = 
                `<div class="alert alert-warning" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading">Ops!</h4>
                    <p>${json.message}</p>
                    <hr>
                    <p class="mb-0">${error}</p>
                </div>`;
                var modal = document.getElementById('modalDelete'+alias)
                modal.insertBefore(div, modal.childNodes[0])
            } else {
                div.innerHTML = 
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Wow!</strong> ${json.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> `;
                form.reset()
                //$('#modalDelete'+alias).modal('hide');
                bootstrap.Modal.getInstance(document.getElementById('modalDelete'+alias)).hide();
                self.refreshGride();
                var alert = document.getElementById('regionTable')
                alert.insertBefore(div, alert.childNodes[0])
            }
            
        };

        this.post(e.target.formAction, formData, mostraResultadoDelete);
    }

    btnClickCancel(e) {
        console.log(e);
    }

    post(locate, data, callback){
        var xhr = new XMLHttpRequest();
        xhr.open("POST", locate, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() { // Chama a função quando o estado mudar.
            if (this.readyState === 4 && this.status === 200) {
                callback(xhr);
            }
        }
        xhr.send(data);
    }

    /**
     * Fim
     * Funcoes para envio de inforaçao ao servidor
     */

    /**
     * Inicio
     * Funcoes para buscar inforaçao ao servidor
     */

    btnClickBuscarParaEditar(e){
        var mostraResultadoModalEdit = function (xhr){
            var json = JSON.parse(xhr.responseText);
            console.log(json);
            var inputs = document.getElementById('frm_'+alias+'_Edit').querySelectorAll("input,select")
            document.getElementById('frm_'+alias+'_Edit').reset();
            for (var key in json) {   
                for (var i in inputs){
                    if (inputs[i].name == key){
                        inputs[i].value = json[key]
                    }
                }
             }
        };
        this.get(e.target.dataset.id, mostraResultadoModalEdit);
    }

    btnClickBuscarParaVisualizar(e){
        var mostraResultadoModalVisualizar = function (xhr){
            var json = JSON.parse(xhr.responseText);
            var inputs = document.getElementById('frm_'+alias+'_View').querySelectorAll("input,select")
            document.getElementById('frm_'+alias+'_View').reset();
            for (var key in json) {   
                for (var i in inputs){
                    if (inputs[i].name == key){
                        inputs[i].value = json[key]
                    }
                }
             }
        };
        this.get(e.target.dataset.id, mostraResultadoModalVisualizar);
    }

    btnClickBuscarParaDeletar(e){
        var mostraResultadoModalDeletar = function (xhr){
            var json = JSON.parse(xhr.responseText);
            var inputs = document.getElementById('frm_'+alias+'_Delete').querySelectorAll("input,select")
            document.getElementById('frm_'+alias+'_Delete').reset();
            for (var key in json) {   
                for (var i in inputs){
                    if (inputs[i].name == key){
                        inputs[i].value = json[key]
                    }
                }
             }
        };
        this.get(e.target.dataset.id, mostraResultadoModalDeletar);
    }
    

    get(id, callback){
        var xhr = new XMLHttpRequest();
        let formData = new FormData();
        formData.append("id", id);
        formData.append("action", "Get");
 
        xhr.open("POST", base_url, true);      
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() { // Chama a função quando o estado mudar.
            if (this.readyState === 4 && this.status === 200) {
                callback(xhr);
            }
        }

        xhr.send(formData);
    }

    /**
     * Fim
     * Funcoes para buscar inforaçao ao servidor
     */
  }
  
  let cad = new Cadastros();