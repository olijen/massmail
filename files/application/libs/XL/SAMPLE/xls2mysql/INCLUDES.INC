<?php

function print_error( $msg )
	{
		print <<<END
		<tr>
			<td colspan=5><font color=red><b>������: </b></font>$msg</td>
			<td><font color=red><b>�����������</b></font></td>
		</tr>

END;
	}

function getHeader( $exc, $data )
{
		// ������
	
		$ind = $data['data'];
		if( $exc->sst[unicode][$ind] )
			return convertUnicodeString ($exc->sst['data'][$ind]);
		else
			return $exc->sst['data'][$ind];

}


function convertUnicodeString( $str )
{
	for( $i=0; $i<strlen($str)/2; $i++ )
	{
		$no = $i*2;
		$hi = ord( $str[$no+1] );
		$lo = $str[$no];
		
		if( $hi != 0 )
			continue;
		elseif( ! ctype_alnum( $lo ) )
			continue;
		else
			$result .= $lo;
	}
	
	return $result;
}

function uc2html($str) {
	$ret = '';
	for( $i=0; $i<strlen($str)/2; $i++ ) {
		$charcode = ord($str[$i*2])+256*ord($str[$i*2+1]);
		$ret .= '&#'.$charcode;
	}
	return $ret;
}



function get( $exc, $data )
{
	switch( $data['type'] )
	{
		// ������
	case 0:
		$ind = $data['data'];
		if( $exc->sst[unicode][$ind] )
			return uc2html($exc->sst['data'][$ind]);
		else
			return $exc->sst['data'][$ind];

		// �����
	case 1:
		return (integer) $data['data'];

		// ������������ �����
	case 2:
		return (float) $data['data'];
        case 3:
		return gmdate("m-d-Y",$exc->xls2tstamp($data[data]));

	default:
		return '';
	}
}



function fatal($msg = '') {
	echo '[��������� ������]';
	if( strlen($msg) > 0 )
		echo ": $msg";
	echo "<br>\n���������� ������� �����������<br>\n";
	if( $f_opened) @fclose($fh);
	exit();
}


function getTableData ( $ws, $exc ) {
	
	
	global $excel_file, $db_table;
	global $db_host, $db_name, $db_user, $db_pass;
	
	$data = $ws['cell'];
	
echo <<<FORM

	<form action="" method="POST" name="db_export">
	<table border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666">
	<tr bgcolor="#f1f1f1">

FORM;

// ����� ����� �����
	
if ( !$_POST['useheaders'] ) {
	for ( $j = 0; $j <= $ws['max_col']; $j++ ) {

		$field = "field" . $j;
						
		echo <<<HEADER

		<td>
		<input type="checkbox" name="fieldcheck[$j]" value="$j" checked title="�������� �����">
		<input type="text" name="fieldname[$j]" value="$field" title="�������� ����">
		</td>

HEADER;
	}
}
else {
	for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
		
		$field = getHeader ( $exc, $data[0][$j] );
			
		$field = ereg_replace ( "^[0-9]+", "", $field );
		
		if (empty ($field) )
			$field = "field" . $j;
		
		echo <<<HEADER

		<td>
		<input type="checkbox" name="fieldcheck[$j]" value="$j" checked title="�������� ����">
		<input type="text" name="fieldname[$j]" value="$field" title="��� ����">
		</td>

HEADER;
	}
}

	
	echo "</tr>";
	
	foreach( $data as $i => $row ) { // �������� ������ � ���������� ������ SQL
		
				
		if ( $i == 0 && $_POST['useheaders'] )
		continue;
		
		echo "<tr bgcolor=\"#ffffff\">";
		
		for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
		
			$cell = get ( $exc, $row[$j] );
			echo "<td>$cell</td>";
					
		}
		
		echo "</tr>";
		$i++;
	}
	
	if ( empty ( $db_table ) )
		$db_table = "Table1";
					
echo <<<FORM2
	
	</table><br>
	<table align="center" width="390">
	<tr><td>��� �������:</td><td>&nbsp;<input type="text" name="db_table" value="$db_table"></td></tr>
	<tr><td>���������� ������� ���� ����������:</td><td><input type="checkbox" name="db_drop" checked></td></tr>
	<tr><td colspan="2">
	<i>��������� ��� �����, ����� �������� ������ � ������������ �������.<br><font color="red">
	�������� �������� �� ��, ��� ���� ����� ����� �� ������������� ������ ����� � ������� ��������� ������!</td></tr>
	<tr><td>��� ���� ���� ������:</td><td>&nbsp;<input type="text" size=30 name="db_host" value="$db_host"></td></tr>
	<tr><td>��� ���� ������:</td><td>&nbsp;<input type="text" size=30 name="db_name" value="$db_name"></td></tr>
	<tr><td>������������ ���� ������:</td><td>&nbsp;<input type="text" size=30 name="db_user" value="$db_user"></td></tr>
	<tr><td>������ ������� � ���� ������:</td><td>&nbsp;<input type="password" size=30 name="db_pass" value="$db_pass"></td></tr>
	<tr><td></td><td><input type="hidden" name="excel_file" value="$excel_file">
	<input type="hidden" name="step" value="2">
	&nbsp;<input type="submit" name="submit" value="Output"></td></tr>
	</form>
	</table>
	<br>&nbsp;
<div align="right">
<a href="http://www.zakkis.ca" style="font-size: 9px; text-decoration: none; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;">ZAKKIS Tech. 2003  All Rights Reserved.</a>&nbsp;&nbsp;
</div>
FORM2;

} 



function prepareTableData ( $exc, $ws, $fieldcheck, $fieldname ) {
	
			
	$data = $ws['cell'];
	
	foreach( $data as $i => $row ) { //  �������� ������ � ���������� ������ SQL
		
				
		if ( $i == 0 && $_POST['useheaders'] )
		continue;
		
		$SQL[$i] = "";
		
		for ( $j = 0; $j <= $ws['max_col']; $j++ ) {
		
			if ( isset($fieldcheck[$j]) ) {
			
								
				$SQL[$i] .= $fieldname[$j];
				$SQL[$i] .= "=\"";
				$SQL[$i] .= addslashes ( get ( $exc, $row[$j] ) );
				$SQL[$i] .= "\"";
				
				$SQL[$i] .= ",";
			}
		
				
		}
		
		$SQL[$i] = rtrim($SQL[$i], ',');
		
		$i++;
	}
	
	return $SQL;	
			
} 

?>