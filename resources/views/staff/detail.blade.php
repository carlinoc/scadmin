@extends('adminlte::page')

@section('title', 'Detalle del Personal')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Detalle del Personal:</h1>
        </div>
        <div class="col">
            <a href="{{ route('staff.index') }}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-data-tab" data-toggle="pill"
                                href="#custom-tabs-four-data" role="tab"
                                aria-controls="custom-tabs-four-data" aria-selected="True">Datos Generales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-salaries-tab" data-toggle="pill"
                                href="#custom-tabs-four-salaries" role="tab"
                                aria-controls="custom-tabs-four-salaries" aria-selected="false">Sueldos y Adelantos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-schedule-tab" data-toggle="pill"
                                href="#custom-tabs-four-schedule" role="tab"
                                aria-controls="custom-tabs-four-schedule" aria-selected="false">Horario</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-data" role="tabpanel"
                            aria-labelledby="custom-tabs-four-data-tab">
                            <form action="" method="POST" id="frmAddStaff">    
                            @csrf
                                <input type="hidden" id="staffId" name="staffId" value="{{$staff->id}}">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Nombre</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{$staff->name}}" placeholder="Nombre del proveedor">
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>DNI</label>
                                                    <input type="text" class="form-control" id="dni" name="dni" value="{{$staff->dni}}" placeholder="Ingresar DNI">
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Nro. Telefono 1</label>
                                                    <input type="text" class="form-control" id="phone1" name="phone1" value="{{$staff->phone1}}" placeholder="Número de celular">
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Nro. Telefono 2</label>
                                                    <input type="text" class="form-control" id="phone2" name="phone2" value="{{$staff->phone2}}" placeholder="Número de celular o teléfono">
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <input type="text" class="form-control" id="address" name="address" value="{{$staff->address}}" placeholder="Ingresar dirección">
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" id="email" name="email" value="{{$staff->email}}" placeholder="Correo Electrónico">
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Area de trabajo</label>
                                                    <x-adminlte-select2 name="areaId" id="areaId" label-class="text-lightblue" data-placeholder="Seleccione un area" required>
                                                        <option value=""></option>
                                                        @foreach($areas as $area)
                                                            @if($staff->areaId == $area->id)
                                                                <option value="{{$area->id}}" selected >{{$area->area}}</option>
                                                            @else
                                                                <option value="{{$area->id}}">{{$area->area}}</option>
                                                            @endif    
                                                        @endforeach
                                                    </x-adminlte-select2>
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción">{{$staff->description}}</textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button id="addStaff" type="button" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-salaries" role="tabpanel"
                            aria-labelledby="custom-tabs-four-salaries-tab">
                            <form action="#" method="POST" id="frmListExpense">
                            @csrf
                            <input type="hidden" id="staffId" name="staffId" value="{{$staff->id}}">
                            <div class="row mb-2">
                                <div class="col-4">  
                                    <select class="form-control" name="dateRange" id="dateRange" required>
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text bg-gradient-info">
                                                <i class="fas fa-location-arrow"></i>
                                            </div>
                                        </x-slot>
                                        <option value="today">Solo Hoy</option>
                                        <option value="yesterday">Solo Ayer</option>
                                        <option value="this_week">Esta Semana</option>
                                        <option value="last_week">La Semana Pasada</option>
                                        <option value="this_month">Este Mes</option>
                                        <option value="last_month">El Mes Pasado</option>
                                        <option value="this_year">Este año</option>
                                        <option value="custom">Seleccionar Fechas</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control" name="staffPayType" id="staffPayType" required>
                                        <option value="0">- Todo -</option>
                                        <option value="1">Adelanto de Sueldo</option>
                                        <option value="2">Sueldo</option>
                                        <option value="3">Horas Extras</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button id="showReport" type="submit" class="btn btn-primary">Ver Detalle</button>
                                </div>
                            </div>
                            <div id="rowDates" class="row" style="display: none" >
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="startDate" name="startDate"
                                            placeholder="Fecha Inicio">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="endDate" name="endDate"
                                            placeholder="Fecha Fin">
                                    </div>
                                </div>
                            </div>
                            </form>
                            <div class="row">
                                <div class="col-10">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <label id="lTotalExpense">Total S/ 0.00</label>
                                            <div class="card-tools">
                                                {{-- <button id="newSalary" type="button" class="btn btn-success">+ Nuevo</button> --}}
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtSalary" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:80px">Id</th>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:100px">Tipo</th>
                                                        <th style="width:100px">Monto S/</th>
                                                        <th style="width:80px">Caja</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbSalary">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-schedule" role="tabpanel"
                            aria-labelledby="custom-tabs-four-schedule-tab">

                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>

    @include('staff.add-expense')
@stop

@section('css')
    <link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
    <script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/vendor/admin/main.js"></script>
    <script>
        const _token = document.head.querySelector("[name~=csrf-token][content]").content;

        let _modalExpense = $('#modalExpense');
        let _modalExpenseTitle = $('#modalExpenseTitle');
        let _payboxExpenseId = $('#payboxExpenseId');
        let _staffPayType = $('#staffPayType1');
        let _expense = $('#expense');
        let _description = $('#description1');
        let _ds = null;
        
        $(function() {
            $("#startDate").datepicker({
                "dateFormat": "yy-mm-dd",
                "orientation": "bottom"
            });
            $("#endDate").datepicker({
                "dateFormat": "yy-mm-dd",
                "orientation": "bottom"
            });
        });

        $(document).ready(function() {

            $("#startDate").on('changeDate', function(ev){
                $(this).datepicker('hide');
            });
            $("#endDate").on('changeDate', function(ev){
                $(this).datepicker('hide');
            });

            $('#dateRange').on('change', function(e) {
                e.preventDefault();
                var range = this.value;
                if(range=="custom"){
                    $("#rowDates").show();    
                }else{
                    $("#rowDates").hide();    
                }
            });
           
            $('#addStaff').on('click', function(e) {
                e.preventDefault();
                let elements = [
                    ['name', 'Ingrese el nombre del personal'],
                    ['phone1', 'Ingrese el telefono del personal'],
                    ['areaId', 'Seleccione el area de trabajo']
                ];

                if(emptyfy(elements)) {
                    let route = "{{ route('staff.edit') }}";
                    let data = getFormParams('frmAddStaff');
                    fetch(route, {
                        method: 'post',
                        body: data,
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            showSuccessMsg(result.message);
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    })
                }
            });

            $("#newSalary").on('click', function(e) {
                e.preventDefault();
                clearFormExpense();
                _modalExpenseTitle.text("Nuevo Registro");
                _modalExpense.modal('show');
            });

            $("#showReport").on('click', function(e) {
                e.preventDefault();
                fetchSalaries();
            });

            $("#addExpense").on('click', function(e) {
                e.preventDefault();
                let payboxExpenseId = _payboxExpenseId.val();

                let elements = [
                    ['staffPayType', 'Seleccione el concepto'],
                    ['expense', 'Ingrese el monto']
                ];

                if(emptyfy(elements)) {
                    let route = "{{ route('staff.addexpense') }}";
                    if(payboxExpenseId!="") {
                        route = "{{ route('staff.editexpense') }}";
                    }
                                        
                    let data = getFormParams('frmAddExpense');
                    fetch(route, {
                        method: 'post',
                        body: data,
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            showSuccessMsg(result.message);
                            _modalExpense.modal('hide');
                            fetchSalaries();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                            _modalExpense.modal('hide');
                        }
                    })
                }
            });

            $("#dtSalary").on('click', '.editItem', function (e) {
                e.preventDefault();
                let index = $(this).data('index');
                let rw = _ds[index];
                with (rw) {
                    _payboxExpenseId.val(id);    
                    _expense.val(expense);
                    _staffPayType.val(staffPayType).change();    
                    _description.val(description);
                }
                
                _modalExpenseTitle.text("Editar Registro");
                _modalExpense.modal('show');
            });

            $("#dtSalary").on('click', '.removeItem', function (e) {
                e.preventDefault();
                let posExpenseId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el Registro?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/staff/removeexpense/" + posExpenseId, {
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
                                $("#showReport").trigger('click');                    
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });

            $("#dateRange").val("this_month").change();
            $("#showReport").trigger('click');
        });

        function fetchSalaries() {
            let route = "{{ route('staff.listexpense') }}";        
            let data = getFormParams('frmListExpense');

            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success") {
                    $('#lTotalExpense').html('Total S/ ' + result.totalExpense);
                    _ds = result.list;
                    $("#dtSalary tbody").empty();
                    for($i = 0; $i < _ds.length; $i++) {
                        dr = _ds[$i]; 
                        addRowExpense(dr.expenseDate, dr.expense, dr.id, $i, dr.staffPayType, dr.boxType);
                    }       
                }
            });
        }

        function addRowExpense(vdate, vexpense, vid, vindex, vstaffPayType, vboxType) {
            let table = document.getElementById("tbSalary");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");
            
            c1.innerText = vid;
            c2.innerText = getOnlytHour(vdate);
            c3.innerHTML = getStaffPayType(vstaffPayType);
            c4.innerText = vexpense;
            c5.innerHTML = getBoxType(vboxType);
                       
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
            row.appendChild(c5);
                        
            table.appendChild(row);
        }

        function clearFormExpense(){
            _payboxExpenseId.val('');    
            _expense.val('');
            _description.val('');

            _staffPayType.val("").change();
        }
    </script>
@stop
