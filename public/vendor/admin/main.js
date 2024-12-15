function formatCurrency(val){
    let currencyVal = val.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); 
    return currencyVal;
}

function showErrorMsg(message){
    Swal.fire({
        title: "Atención",
        html: message,
        icon: "error",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Aceptar"
    });
}

function showSuccessMsg(message){
    Swal.fire({
        title: "Muy bien",
        html: message,
        icon: "success",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Aceptar"
    });
}

function showWarningMsg(message){
  Swal.fire({
      title: "Atención",
      html: message,
      icon: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Aceptar"
  });
}

function getFormParams(formId){
    const data = new URLSearchParams();
    const myform = document.getElementById(formId);
    for (const pair of new FormData(myform)) {
      data.append(pair[0], pair[1]);
    }
    return data;  
}

function emptyfy(elements) {
    let message='';
    let _element=null;
    for (i=0; i<elements.length; i++) {
        if(document.getElementById(elements[i][0]).value==''){
            message += '- ' + elements[i][1] + '<br>';  
            if(_element==null){
                _element = document.getElementById(elements[i][0]);         
            }
        }
    }
    if(_element != null){
      _element.focus();
      Swal.fire({
        title: "Atención",
        html: message,
        icon: "warning",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Aceptar"
      });
      return false;  
    } 
    return true;
}

function getBoxType($boxType) {
  switch ($boxType) {
      case 1:
          return '<small class="badge badge-success">Caja Principal</small>';
      case 2:
          return '<small class="badge badge-secondary">Caja Diaria</small>';
      case 3:
        return '<small class="badge badge-info">Cuenta POS</small>';    
  }
}

function getTipsType($paymentType) {
  switch ($paymentType) {
      case 1:
          return '<small class="badge badge-success">Efectivo</small>';
      case 2:
          return '<small class="badge badge-secondary">Tarjeta</small>';
      default:
          return '<small class="badge badge-success">Efectivo</small>';
  }
}

function getPayType($paymentType) {
  switch ($paymentType) {
      case 0:
          return '<small class="badge badge-success">Efectivo</small>';
      case 1:
          return '<small class="badge badge-secondary">Tarjeta</small>';
      case 2:
          return '<small class="badge badge-primary">Yape/Plin</small>';
      case 3:
        return '<small class="badge badge-danger">Por Pagar</small>';        
      default:
          return '<small class="badge badge-success">Efectivo</small>';
  }
}

function getVoucherType($paymentType) {
  switch ($paymentType) {
      case 0:
          return '<small class="badge badge-secondary">Sin Documento</small>';
      case 1:
          return '<small class="badge badge-info">Boleta</small>';
      case 2:
          return '<small class="badge badge-danger">Factura</small>';
      default:
          return '';
  }
}

function getExpenseType($expenseType) {
  switch ($expenseType) {
      case 1:
          return '<small class="badge badge-info">Proveedor</small>';
      case 2:
          return '<small class="badge badge-warning">Servicio</small>';
      case 3:
          return '<small class="badge badge-success">Personal</small>';
      case 4:
          return '<small class="badge badge-secondary">Otros</small>';
      default:
          return '';
  }
}

function getBoxType($boxType) {
  switch ($boxType) {
      case 1:
          return '<small class="badge badge-danger">Caja Principal</small>';
      case 2:
          return '<small class="badge badge-primary">Caja Diaria</small>';
      case 3:
        return '<small class="badge badge-warning">Cuenta POS</small>';    
  }
}

function getStaffPayType($staffPayType) {
  switch ($staffPayType) {
      case 1:
          return ' <small class="badge badge-info">Adelanto</small>';
      case 2:
          return ' <small class="badge badge-danger">Sueldo</small>';
      case 3:
          return ' <small class="badge badge-primary">Extras</small>';
      default:
          return '';
  }
}

function getOnlytDate(currenDate) {
  return currenDate.substring(0, 10);  
}

function getOnlytHour(currenDate) {
  return currenDate.substring(0, 16);  
}

(function($) {
    $.fn.inputFilter = function(callback, errMsg) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
        if (callback(this.value)) {
          // Accepted value
          if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
            $(this).removeClass("input-error");
            this.setCustomValidity("");
          }
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          // Rejected value - restore the previous one
          $(this).addClass("input-error");
          this.setCustomValidity(errMsg);
          this.reportValidity();
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          // Rejected value - nothing to restore
          this.value = "";
        }
      });
    };
}(jQuery));


const COLORS = [
  '#4dc9f6',
  '#f67019',
  '#f53794',
  '#537bc4',
  '#acc236',
  '#166a8f',
  '#00a950',
  '#58595b',
  '#8549ba'
];

function color(index) {
  return COLORS[index % COLORS.length];
}

const CHART_COLORS = {
  red: 'rgb(255, 99, 132, 0.70)',
  orange: 'rgb(255, 159, 64, 0.70)',
  yellow: 'rgb(255, 205, 86, 0.70)',
  green: 'rgb(75, 192, 192, 0.70)',
  blue: 'rgb(54, 162, 235, 0.70)',
  purple: 'rgb(153, 102, 255, 0.70)',
  grey: 'rgb(201, 203, 207, 0.70)'
};

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign +
      (j ? i.substr(0, j) + thousands : '') +
      i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
      (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};