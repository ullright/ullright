/**
 * Unobstrusivly replace a time duration input with select boxes
 * 
 * @param fragmentation   format of the minute select box. example:
 *                        fragmentation = 15 results in a list 0,15,30,45 min
 */
jQuery.fn.replaceTimeDurationSelect = function(fragmentation) 
{
  return this.each(function(foo)
  {
    $(this).hide();
    
    var hoursId = this.id + '_hours';
    var minutesId = this.id + '_minutes';
    
    var hoursInput = $('<select id="' + hoursId + '"></select>');
    var minutesInput = $('<select id="' + minutesId + '"></select>');
    
    $(this).after(minutesInput);
    $(this).after(hoursInput);
    hoursInput.after(" : ");
    
    var hoursList = generateHoursList();
    generateOptions(hoursInput, hoursList);

    var minutesList = generateMinutesList(fragmentation);
    generateOptions(minutesInput, minutesList);
    
    var id = this.id;
    hoursInput.change(function(){updateHour(this.value, id)});
    minutesInput.change(function(){updateMinute(this.value, id)});
    
    var parts = $(this).val().split(':');
    hoursInput.val(parts[0]);
    minutesInput.val(parts[1]);
    
    });
};


/**
 * Add options to the given select element
 * @param element
 * @param options
 * @return
 */
function generateOptions(element, options)
{
  var option = $('<option value=""></option>');
  $(element).append(option);  
  
  for (var i = 0; i < options.length; i++)
  {
    var option = $('<option value="' + options[i] + '">' + options[i] + '</option>');
    $(element).append(option);
  }
}


/**
 * Generate an array of minutes using the given fragmentation in minutes
 * 
 * Example: fragmentation = 15min -> 0,15,30,45
 * 
 * @param fragmentation
 * @return array
 */
function generateMinutesList(fragmentation)
{
  var list = new Array();
  for (var i = 0; i < 60; i = i + fragmentation)
  {
    var asString = String(i);
    if (asString.length == 1)
    {
      asString = '0' + asString;
    }  
    list.push(asString);
    
  }
  
  return list;
}


/**
 * Generate hours
 * 
 * @return array
 */
function generateHoursList()
{
  var list = new Array();
  for (var i = 0; i < 24; i++)
  {
    list.push(i);
  }

  return list;
}


/**
 * Update the original field with the selected hour
 * 
 * @param value
 * @param input
 */
function updateHour(value, id) 
{
  var input = document.getElementById(id);
  var time = input.value;
  
  if (!time)
  {
    time = value + ':00';
  }
  else
  {
    var parts = time.split(':');
    
    time = value + ':' + parts[1];
  }

  input.value = time;
}  


/**
 * Update the original field with the selected minute
 * 
 * @param value
 * @param input
 */
function updateMinute(value, id) 
{
  var input = document.getElementById(id);
  var time = input.value;

  if (!time)
  {
    time = '0:' + value;
  }
  else
  {
    var parts = time.split(':');
    
    time = parts[0] + ':' + value;
  }

  input.value = time;
}  