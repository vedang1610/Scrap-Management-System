
// Arrange Row Numbere in first column of Table
function resetTableRowNumber(tableName)
{
     $('#'+tableName+' tbody tr').each(function(idx){
          $(this).children(":eq(0)").html(idx + 1);
     });
}

// Validate Number With 2 decilam Points (ex - 10.00)
function addDecimalValidationEvent(textBoxName)
{
     $('#'+textBoxName+'').keypress(function(event) {
          if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
              (event.which != 0 && event.which != 8))) {
            event.preventDefault();
          }
        
          var text = $(this).val();
        
          if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
          }
        });
}

function addDecimalValidationEvent2(event)
{
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
      ((event.which < 48 || event.which > 57) &&
        (event.which != 0 && event.which != 8))) {
      event.preventDefault();
    }
  
    var text = $(this).val();
  
    if ((text.indexOf('.') != -1) &&
      (text.substring(text.indexOf('.')).length > 2) &&
      (event.which != 0 && event.which != 8) &&
      ($(this)[0].selectionStart >= text.length - 2)) {
      event.preventDefault();
    }
}