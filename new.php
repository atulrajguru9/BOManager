<?php
session_start();
require_once 'SalesforceAPI.php';

$access_token = $_SESSION['access_token'];
$instance_url = $_SESSION['instance_url'];

$salesforce = new SalesforceAPI($instance_url, '35.0','','');
$salesforce->setToken($access_token);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BO Manager</title>
<link rel="stylesheet" type="text/css" href="jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="jquery/lib/jquery.js"></script>
<script type="text/javascript" src="jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="jquery/lib/jquery.validate.min.js"></script>

<script>
/*
TODO
1) validation
2) Auto fill zise api name
3) field API name
4) submit generate xml
*/
 
  var itemCount = 0;
  
 $(document).ready(function(){
  
    var objs=[];
    var temp_objs=[];
     
    $( "#add_button" ).click(function() {   
        if(	$("#field_label").val() == ''){
			alert('Please enter Label');
			$("#field_label").focus();
			return;
		}
		if(	$("#field_name").val() == ''){
			alert('Please enter Name');
			$("#field_name").focus();
			return;
		}
		if(	$("#data_type").val() == 'Text' && $("#length").val() == ''){
			alert('Please enter length');
			$("#length").focus();
			return;
		}
		if(	$("#data_type").val() == 'DateTime' || $("#data_type").val() == 'Lookup'){
			$("#length").val('');
		}
		
        var html = "";
         
        var obj={
            "ROW_ID" : itemCount,
            "field_name" :  $("#field_name").val(),
			"field_label" :  $("#field_label").val(),
            "data_type" : $("#data_type").val(),
			"referenceTo" : ( $("#data_type").val() == 'Lookup') ? $("#referenceTo").val() : '',
            "length" : $("#length").val()
        }   
     
        // add object
        objs.push(obj);
                     
        itemCount++;
        // dynamically create rows in the table
        html = "<tr id='tr"+ itemCount + "'><td>"+ obj['field_label'] + "</td> <td>"+ obj['field_name'] + "</td> <td>" +  obj['data_type'] + " </td> <td>" +  obj['referenceTo'] + " </td> <td>" +  obj['length'] + " </td><td><input type='button'  id='" + itemCount + "' value='remove'></td> </tr>";         
         
        //add to the table
        $("#fields_table").append(html)
         
        // The remove button click
        $("#"+itemCount).click(function() {
            var buttonId = $(this).attr("id");
            //write the logic for removing from the array
            $("#tr"+ buttonId).remove();            
        });
		
		$( "#field_name" ).val('');
		$( "#length" ).val('');
		$( "#field_label" ).val('');
         
    });

	$( "#preview_XML" ).click(function() {   
		var html = '<?xml version="1.0" encoding="UTF-8"?> \n <CustomObject xmlns="http://soap.sforce.com/2006/04/metadata">    \n <deploymentStatus>Deployed</deploymentStatus>   \n';  
		//alert(objs);
		var tag_len, tag_ref;
		
		var html_pro = '';
		
		for(var i in objs){
			if(objs[i].length != 0)
				tag_len = '\n\t\t <length>' + objs[i].length +'</length>';
			else
				tag_len = '';
						
			if(objs[i].data_type == 'Lookup'){
				var randomnumber = Math.floor(Math.random() * 101);
				tag_ref = '\n\t\t <referenceTo>' + objs[i].referenceTo +'</referenceTo> ';
				tag_ref += '\n\t\t <relationshipName>' + objs[i].referenceTo +'Lookup'+ randomnumber +'</relationshipName> ';
			}
			else
				tag_ref = '';
			
			html += '  \n\t <fields> \n\t\t <fullName>' + objs[i].field_name + '</fullName>\n\t\t <label>' + objs[i].field_label + '</label>' + tag_len +'\n\t\t <type>'+objs[i].data_type +'</type> '+tag_ref +'\n\t </fields>';	
			html_pro += '\n\t <fieldPermissions> \n\t\t <editable>false</editable> \n\t\t <field>' + $( "#obj_name" ).val() + '.' + objs[i].field_name +'</field> \n\t\t <readable>true</readable> \n\t</fieldPermissions>';
		}
		
		html += '\n\t <label>'+ $( "#obj_label" ).val() + '</label>  ';  
		html += '\n\t <pluralLabel>'+ $( "#obj_plabel" ).val() + '</pluralLabel>  ';  
		html += '\n </CustomObject>';
		$( "#XML_data" ).val (html);
		$( "#XML_data_pro" ).val (html_pro);		
    });	 
	
	$( "#obj_label" ).change(function() {   
		var s = $( "#obj_label" ).val();
		$( "#obj_plabel" ).val( s + 's');				
		var apiname = s.split(' ').join('_');
		$( "#obj_name" ).val(apiname + '__b');	
    });
	
	$( "#field_label" ).change(function() {   
		var s = $( "#field_label" ).val();
		var apiname = s.split(' ').join('_');
		$( "#field_name" ).val(apiname + '__c');
    });
	
	$( "#data_type" ).change(function() {   
		if($( "#data_type" ).val() == 'Lookup'){
			$( "#length" ).val('');
			$( "#referenceTo" ).removeAttr("disabled");
		}else{
			$( "#referenceTo" ).attr("disabled", "disabled");
		}
		if($( "#data_type" ).val() == 'DateTime')
			$( "#length" ).val('')
		
    });
	
 });
   
</script>
</head>
<body>
<form action=build.php method=post>
<input type='hidden' name=path value="<?php echo $_GET['path']?>">
<table border='1' width='90%' align='center'  style="border-collapse:collapse " cellspacing='3' cellpadding='5'>
<th colspan="9" bgcolor="#0099FF">Create Big Object</th>

<tr ><td colspan="9"> Label : <input type=Text size=30 name=obj_label id=obj_label> </td></tr>
<tr ><td colspan="9"> Plural Label : <input type=Text size=30 id=obj_plabel name=obj_plabel> </td></tr>
<tr ><td colspan="9"> Api Name : <input type=Text size=30 name=obj_name id=obj_name> </td></tr>
<tr>
 
    <td> Label : </td>
    <td><input name="name" type="text" id="field_label" size="20" value="f1"/></td>
	<td> Name : </td>
    <td><input name="name" type="text" id="field_name" size="20" value="f1__c"/></td> 
    <td> Type : 
		<select name="Type" id="data_type">
		  <option value="Text">Text</option>
		  <option value="Lookup">Lookup</option>
		  <option value="DateTime">DateTime</option>		  
		</select>		
		Ref : <select name="referenceTo" id="referenceTo" disabled>
		<?php
		foreach ($salesforce->getAllObjects()->sobjects as $sObjs){
			echo '<option value="' . $sObjs->name .'">'. $sObjs->name .'</option>';
		}
		?>
		</select>
	</td>
     
    <td> Length : </td>
    <td><input name="Length" type="text" id="length" size="3"/></td>
     
    <td><input name="add_button" type="button" id="add_button" size="20" value="Add" /></td> </tr>

</table>
 <Br>
<table border='1' id='fields_table'  width='70%' align='center'  style='border-collapse:collapse' cellspacing='3' cellpadding='5'>
	<TR> 
		<td> <b>Field Label</b></td> 
		<td> <b>Field Name</b></td>
		<td> <b>Data Type</b></td> 
		<td> <b>Reference To</b></td> 
		<td> <b>Length</b></td>
		<td> <b>Remove</b></td> 
		
	</tr>
</table>
</div>
 
 <table border='0' width='70%' align='center'  style="border-collapse:collapse " cellspacing='3' cellpadding='5'>
 	<tr><td colspan="9"> 
		<input type=button name=preview value="Prewview XML" id="preview_XML"> 
		&nbsp;&nbsp;&nbsp;
		<input type=submit name=Submit value="Create BO"> 
	</td></tr>
	<tr><td colspan="9" valign="Top"> <br><br>
		Object XML:
		<Textarea rows="20" cols="90" name=XML_data id="XML_data" ></Textarea>
	</td></tr>
	<tr><td colspan="9" valign="Top"> <br><br>
		Profile XML:
		<Textarea rows="20" cols="90" name=XML_data_pro id="XML_data_pro" ></Textarea>
	</td></tr>
	
</table>
</form>
</body>
</html>