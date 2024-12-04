@extends('adminlte::page')

@section('title', 'Mantenimiento de Categorias de Gastos')

@section('content_header')
    <h1>Mantenimiento de Categorias de Gastos</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newCategory" class="btn btn-primary">Nueva Categoria</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtCategories" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Categoria</th>
                            <th>Cat. Principal</th>
                            <th>Sección</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('expensecategories.modal')
    @endrole   
    
    @role('Mozo')
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _expenseCategoryId = $("#expenseCategoryId");
    let _isParent = $("#isParent");
    let _parentId = $("#parentId");
    let _category = $("#category");
    let _expenseType = $("#expenseType");

    let _dtCategories = $("#dtCategories");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchExpenseCategories();

        $('#newCategory').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nueva Categoría");
            _modal.modal('show');
        });

        $("#parentId").on('change', function(e) {            
            e.preventDefault();
            var parentId = $(this).val();
            //fetchSubCategories(parentId);
        });

        $("#isParent").on('change', function(e) {
            e.preventDefault();
            var isChecked = $(this).is(":checked");
            if(isChecked) {
                $('#dCategories').hide();
                $('#dExpenseType').show();
                _category.focus();
            }else{
                $('#dCategories').show();
                $('#dExpenseType').hide();
            }
        });

        $('#addCategory').on('click', function(e) {
            e.preventDefault();
            var isChecked = $("#isParent").is(":checked");
            if(!isChecked) {
                let parentId = $("#parentId").val();
                if(parentId=="") {
                    showErrorMsg("Seleccione una categoría principal");
                    $("#parentId").focus();
                    return;
                }
            }
            
            let elements = [
                ['category', 'Ingrese el nombre de la categoría']
            ];

            if(emptyfy(elements)) {
                let expenseCategoryId = _expenseCategoryId.val();
                
                let route = "{{ route('expensecategories.add') }}";
                if(expenseCategoryId!="") {
                    route = "{{ route('expensecategories.edit') }}";
                }
                let data = getFormParams('frmExpenseCategory');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modal.modal('hide');
                        clearForm();
                        showSuccessMsg(result.message);
                        fetchExpenseCategories();   
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });

        _dtCategories.on('click', '.editItem', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];

            with (rw) {
                _expenseCategoryId.val(id);
                if(parentId==null){
                    _isParent.prop("checked", true);    
                    $('#dCategories').hide();
                    $('#dExpenseType').show();
                }else{
                    _isParent.prop("checked", false);
                    $('#dCategories').show();
                    $('#dExpenseType').hide();
                }
                _parentId.val(parentId).change();
                _category.val(category);
                _expenseType.val(expenseType).change();    
            }
            
            _modalLabel.text("Editar Categoría");
            _modal.modal('show');
        });

        _dtCategories.on('click', '.removeItem', function (e) {
            e.preventDefault();
            let expenseCategoryId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar la categoría ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/expensecategories/remove/" + expenseCategoryId, {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-Token": _token
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            showSuccessMsg(result.message);
                            fetchExpenseCategories();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });

    async function fetchExpenseCategories() {
        const response = await fetch("/expensecategories/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch expensecategories");       
        }                    
        const data = await response.json();
        _ds = data.list;
        _dtCategories.DataTable().destroy();
        _dtCategories.DataTable({
            "data": data.list,
            "responsive": true,
            order: [[0, 'desc']],
            "columns": [
                {
                    "render": function(data, type, row, meta) {
                        return row.id;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.category;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        if(row.parent!=null){
                            return '<small class="badge badge-secondary">'+row.parent+'</small>';    
                        }else{
                            return "";
                        }
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return  getExpenseType(row.expenseType);
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editItem"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeItem"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _expenseCategoryId.val('');
        _isParent.prop("checked", true);
        _parentId.val('').change();
        _category.val('');
        _expenseType.val(1).change();

        $('#dCategories').hide();
        $('#dExpenseType').show();
    }
</script>        
@stop