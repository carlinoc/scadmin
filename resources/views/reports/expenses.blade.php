@extends('adminlte::page')

@section('title', 'Reporte de Gastos')

@section('content_header')
    <h1>Reporte de Gastos</h1>
@stop

@section('content')
    @role(['Admin'])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="#" method="POST" id="frmReportExpense">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <select class="form-control" name="dateRange" id="dateRange" required>
                                    <option value="this_week">Esta Semana</option>
                                    <option value="last_week">La Semana Pasada</option>
                                    <option value="this_month">Este Mes</option>
                                    <option value="last_month">El Mes Pasado</option>
                                    <option value="custom">Seleccionar Fechas</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <x-adminlte-select2 name="categoryId" id="categoryId" style="width: 220px!important">
                                    <option value="0">- Todas las Categorías -</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category }}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>
                            <div class="col-auto">
                                <x-adminlte-select2 name="subCategoryId" id="subCategoryId" style="width: 220px!important">
                                    <option value="0">- Todas las Sub Categorías -</option>
                                </x-adminlte-select2>
                            </div>
                            <div class="col">
                                <button id="showReport" type="submit" class="btn btn-primary">Ver Reporte</button>
                            </div>
                        </div>
                        <div id="rowDates" class="row mt-2" style="display:none;">
                            <div class="col-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="endDate" name="endDate" placeholder="Fecha Fin">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <canvas id="expensesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>        
                            </div>
                            <div class="col-2">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <span>Total Gastos</span>
                                        <h4 id="hTotalExpenses">S/ 0.00</h4>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                    <div class="small-box-footer p-1">
                                    </div>    
                                </div>
                    
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <span>Total Caja Principal</span>
                                        <h5 id="hMainBox">S/ 0:00</h5>
                                        <span>Total Caja Diaria</span>
                                        <h5 id="hPayBox">S/ 0:00</h5>
                                        <span>Total Cuenta POS</span>
                                        <h5 id="hPOS">S/ 0:00</h5>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4" id="topExpenses">
                            <div class="col-10">
                                <h5 class="text-info text-center">Top Gastos</h5>
                                <table id="dtExpenses" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">Nro</th>
                                            <th style="width: 80px;">Fecha</th>
                                            <th style="width: 100px;">Gasto</th>
                                            <th style="width: 120px;">Descripcion</th>
                                            <th style="width: 80px;">Caja</th>
                                            <th style="width: 80px;">S/ Total</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>    
                </form>        
            </div>
        </div> 
    </div>
    
    
    @endrole   
    
    @role(['Mozo', 'Maitre'])
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole 
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<style>
    .info-box{
        min-height: 60px!important;
        padding: .2rem!important;
    }
    .info-box-text{
        padding: 0px!important;
        margin: 0px!important;
        line-height: 18px!important;
    }
    .info-box-number{
        margin-top: 0px!important;
    }
    .info-box-number2{
        margin-top: 0px!important;
        display: block!important;
        font-weight: 500!important;
    }
    .col-vercent{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;
    const ctx = document.getElementById('expensesChart');

    let chartLabel=[];
    let chartData=[];
    let _subCategoryId = "";
    let _dtExpenses = $("#dtExpenses");
    
    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });

    $(document).ready(function(){
        $("#topExpenses").hide();

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

        $("#categoryId").on('change', function(e) {            
            e.preventDefault();
            let parentId = $(this).val();
            if(parentId != ""){
                fetchSubCategories(parentId);
            }
        });

        $("#showReport").on('click', function(e) {
            e.preventDefault();
            $("#topExpenses").hide();

            chartLabel=[];
            chartData=[];
            fetchExpenses();
            fetchTopExpenses();
        })
    });

    async function fetchExpenses(){
        let route = "{{ route('report.expenselist') }}";        
        let dt = getFormParams('frmReportExpense');

        fetch(route, {
            method: 'post',
            body: dt,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                const data = result;
                let totalExpense = 0.00;
                let totalPOS = 0.00;
                let totalPayBox = 0.00;
                let totalMainBox = 0.00;

                for (let i = 0; i < data.list.length; i++) {
                    let dt = data.list[i];
                    chartLabel[i] = dt.date;
                    chartData[i] = dt.total;
                    totalExpense += parseFloat(dt.total);

                    if(dt.boxType==1){
                        totalMainBox += parseFloat(dt.total);
                    }
                    if(dt.boxType==2){
                        totalPayBox += parseFloat(dt.total);
                    }
                    if(dt.boxType==3){
                        totalPOS += parseFloat(dt.total);
                    }
                }
                $("#hTotalExpenses").html("S/ " + formatMoney(totalExpense));
                $("#hMainBox").html("S/ " + formatMoney(totalMainBox));
                $("#hPayBox").html("S/ " + formatMoney(totalPayBox));    
                $("#hPOS").html("S/ " + formatMoney(totalPOS));  
                                                                
                let chartStatus = Chart.getChart("expensesChart");
                if (chartStatus != undefined) {   
                    chartStatus.destroy();
                }
                
                const datalist = {
                    labels: chartLabel,
                    datasets: [
                        {
                            label: 'Gastos',
                            data: chartData,
                            borderColor: CHART_COLORS.red,
                            backgroundColor: CHART_COLORS.red,
                        }    
                    ]
                };                

                new Chart(ctx, {
                    type: 'bar',
                    data: datalist
                });
            }
        });
    }

    async function fetchSubCategories(parentId) {
        const response = await fetch("/expensecategories/subcategories/" + parentId, {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch subcategories");       
        }                    
        const data = await response.json();
        $("#subCategoryId").empty();
        $("#subCategoryId").append('<option value="0">Todas las Sub Categorías</option>');
        for(let i = 0; i < data.list.length; i++) {
            $("#subCategoryId").append('<option value="' + data.list[i].id + '">' + data.list[i].category + '</option>');
        }
        if(_subCategoryId != "") {
            $("#subCategoryId").val(_subCategoryId).change();
        }
    }

    async function fetchTopExpenses(){
        let route = "{{ route('report.topexpense') }}";        
        let dt = getFormParams('frmReportExpense');

        fetch(route, {
            method: 'post',
            body: dt,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                $("#topExpenses").show();
                _dtExpenses.DataTable().destroy();    
                _dtExpenses.DataTable({
                    "data": result.list,
                    "responsive": true,
                    "columns": [
                        {
                            "render": function(data, type, row, meta) {
                                return (meta.row + 1);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.date;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                let concept = "";
                                if(row.category != null){
                                    concept = " <small class='badge badge-light'>" + row.category + "</small>";
                                }
                                return getExpenseType(row.expenseType) + concept;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.description;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return  getBoxType(row.boxType);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return  formatMoney(row.total);
                            }
                        }
                    ]
                });
            }
        });
    }
</script>    
@stop    