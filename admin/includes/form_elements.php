<?php  // ** Lampkin 2024 ** // ?>
<?php //! Form Elements VERSION 11.5   ?>
<?php // ** Modified: strlen  ** //?>

<?php
	function formButtons($var='')
	{
		$form_buttons = '<div class="btn-group" role="group" aria-label="Save or Cancel buttons">';
		if( strpos($var,'save') !== false )
		{
			$form_buttons .= '
			<button type="submit" id="submitForm" class="btn btn-success formBtns" role="button"> <i class="fa-solid fa-floppy-disk-pen"></i> S A V E </button>
			';
		}

		if( strpos($var,'clear') !== false )
		{
			$form_buttons .= '
			<button type="reset" id="clearForm" class="btn btn-warning formBtns" accesskey="r" onClick="window.location.reload();"><i class="fas fa-window-close"></i> clear </button>
			';
		}

		if( strpos($var,'reset') !== false )
		{
			$form_buttons .= '
			<button type="reset" id="clearForm" class="btn btn-warning formBtns" accesskey="r"><i class="fas fa-window-close"></i> reset </button>
			';
		}

		if( strpos($var,'cancel') !== false )
		{
			$form_buttons .= '
			<a href="" id="cancelForm" class="btn btn-danger formBtns" role="button" ><i class="fas fa-window-close"></i> cancel</a>
			';
		}

		$form_buttons .= '</div>';

		return $form_buttons;
	}

	function formElements(
		$field_var,
		$data,
		$field_name,
		$field_type,
		$placeholder,
		$required,
		$field_size,
		$alt_class='',
		$disabled_readonly = '',
		$addl_var = '',
		$tooltip = '',
		$footie = '',
		$typeahead = '',
		$form_id = 'data_entry',
		$javascript = '',
		$min = '',
		$max = '',
		$txt_rows = ''
		)
	{


	global $db_name,$db_table,$var_active;
	global $con, $con_master;
	$conn = $db_name == 'datamaster' ? $con_master : $con;
	$formChunk = '';

	$data = isset($data) ? trim($data) : null;
	$required = ( $required !== 'no' && strlen($required) > 2 ) ? 'required' : $required;

	$readonly = ( $disabled_readonly == 'readonly' ) ? 'readonly' : '';
	$disabled = ( $disabled_readonly == 'disabled' ) ? 'disabled' : '';

	$placeholder = ( strlen($placeholder) > 1 ) ? htmlentities(addslashes($placeholder)) : '';

	$insert_typeahead = ( $typeahead !== 'no' && strlen($typeahead) > 1 ) ? 'yes' : 'no';

	$type_ahead = ( $insert_typeahead == 'yes' ) ? 'typeahead' : ''; // Typeahead can contain a size value; Like 10, 12, 14
	$typeahead_size = ( empty($typeahead) || !is_numeric($typeahead) ) ? '10' : $typeahead;

	if( strlen($tooltip) > 3 )
	{
		$tooltipChunk = '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' . $tooltip . '">';
	}
	else
	{
		$tooltipChunk = '';
	}

	$formChunk .= '<div class="form-group m-0 p-0">';
	if( $field_type !== 'password')
	{
		$formChunk .= $tooltipChunk;
		$formChunk .= '
			<label for="' . $field_var . '" class="col-md-12 px-0 form-label ' . $required . ' ' . $readonly . ' ' . $disabled . '" id="label_' . $field_var . '">' . $field_name;
		$formChunk .= '</label>';
		$formChunk .= '</span>';
	}

	$onkeyup = "countChars('" . $field_size . "','" . $field_var . "','len_" . $field_var . "');";

		//! SELECT &&  SELECT STATE
	if ( $field_type == 'select' )
	{
		$var_array = $addl_var;
		$formChunk .= '
		<select class="form-select class_' . $field_var . ' input_class ' . $alt_class . '" id="' . $field_var . '" name="' . $field_var . '" data-info="' . $data . '"  ' . $required . ' ' . $javascript . '>
			';
			$select_term = strlen($placeholder) > 4 ? $placeholder : 'Select';
		if( is_array($addl_var) && count($addl_var) > 0 )
		{
		$formChunk .= '
			<option value=""> -- ' . $select_term . ' -- </option>
			';
			foreach( $addl_var AS $key => $value )
			{
				$term = ( !is_array($addl_var) && strpos($addl_var,$value) !== false ) ? $key : $value;  // This will show the "TX" instead of "Texas"
				$selectme = '';
				if( is_numeric($key) )
				{
					$selectme = ( $key == $data ) ? 'selected' : $selectme;
				}
				else
				{
					$selectme = ( (strpos(strtolower($data), strtolower($key)) !== false) ) ? 'selected' : $selectme;
				}
				$selectme = ( empty(strtolower($data)) && (strtolower($key) == strtolower($placeholder)) ) ? 'selected' : $selectme;
				$formChunk .= '
				<option value="' . $key .'" ' . $selectme . '> ' . $value .' </option>';
			}
		}
		else
		{
			$formChunk .= '
				<span id="option_values_' . $field_var . '"></span>
			';
		}
		$formChunk .= '
		</select>';
	}

		//! SELECT SELF DISTINCT
	elseif ( $field_type == 'select_distinct_self' )
	{
		$query = "
		SELECT DISTINCT(" . $field_var . ") AS " . $field_var . "
		FROM `" . $db_table . "`
		WHERE `" . $var_active . "` = 'yes'
		ORDER BY `" . $field_var . "`
		";
		$formChunk .=  '<select class="form-select class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" data-info="' . $data . '" ' . $required . ' id="' . $field_var . '" >
		';
		$select_term = strlen($placeholder) > 4 ? $placeholder : 'Select';

		$formChunk .=  '<option value=""> -- ' . $select_term . ' -- </option>
		';
		$x=0;
		if ( $result = mysqli_query($conn, $query) )
		{
			$cnt = mysqli_num_rows($result);
			/* fetch associative array */
			while ($row = mysqli_fetch_assoc($result))
			{
				if ( !empty($row[$field_var]) )
				{
					$selectme = ( (strpos(strtolower($data), strtolower($row[$field_var])) !== false) ) ? 'selected' : '';
					$formChunk .= '<option value="' . htmlentities($row[$field_var]) . '" ' . $selectme . '> ' . $row[$field_var] . ' </option>
					';
				}
				$x++;
			}
			/* free result set */
			mysqli_free_result($result);
		}
		$formChunk .= '</select>';
	}

		//! SELECT MULTIPLE SPECIAL
	if ( $field_type == 'selectmultiplespecial' )
	{
		$var_array = $addl_var; // If state grab state_array
		// $json_array = '['; foreach($var_array AS $key => $value) { $json_array .= '{key: "' . trim($key) . '", value: "' . trim($value) . '"},'; } $json_array .= ']';
		$json_array = is_array($addl_var) ? implode(',',$addl_var) : $addl_var;
		// $json_array = $addl_var;
		$formChunk .= '<input type="hidden" id="array_' . $field_var . '" class="form-control input_class" value="' . $json_array . '" />';
		$formChunk .= '<input type="hidden" id="' . $field_var . '" name="' . $field_var . '" class="form-control input_class" value="' . $data . '" ' . $required . '  />';
		$formChunk .= '<div class="alert alert-secondary m-0" id="display_' . $field_var . '"></div>';
		$formChunk .= '
		<select class="form-select class_' . $field_var . ' multi-select input_class ' . $alt_class . '" id="multiple_' . $field_var . '" data-info="' . $data . '" data-field="' . $field_var . '"  ' . $javascript . '>
			';
		if( is_array($addl_var) && count($addl_var) > 0 )
		{
			$select_term = strlen($placeholder) > 4 ? $placeholder : 'Select';

		$formChunk .= '
			<option value=""> -- ' . $select_term . ' -- </option>
			';
			foreach( $addl_var AS $key => $value )
			{
				$term = ( !is_array($addl_var) && strpos($addl_var,$value) !== false ) ? $key : $value;  // This will show the "TX" instead of "Texas"
				$selectme = '';
				// if( is_numeric($key) )
				// {
				// 	$selectme = ( $key == $data ) ? 'selected' : $selectme;
				// }
				// else
				// {
				// 	$selectme = ( (strpos(strtolower($data), strtolower($key)) !== false) ) ? 'selected' : $selectme;
				// }
				$selectme = ( empty(strtolower($data)) && (strtolower($key) == strtolower($placeholder)) ) ? 'selected' : $selectme;
				$formChunk .= '
				<option value="' . $key .'" ' . $selectme . '> ' . $value .' </option>';
			}
		}
		else
		{
			$formChunk .= '
			<span id="option_values_' . $field_var . '"></span>
			';
		}
		$formChunk .= '
		</select>';
	}


		//! TEXTAREA
	elseif ( $field_type == 'textarea' )
	{
		$id_tinyMCE = ( $placeholder == 'tinyMCE' ) ? 'tinyMCE' : $field_var;
		// $rowlen = (strlen($data)>'10') ? rowlen($data,125) : ((strlen($placeholder)>'0') ? $placeholder : '1');
		$txt_rows = (strlen(trim($txt_rows))>0) ? $txt_rows : ((strlen($data)>'10') ? rowlen($data,80) : '1');
		$formChunk .= '
		<textarea class="form-control class_' . $field_var . ' ' . $id_tinyMCE . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" rows="' . $txt_rows . '" placeholder="' . $placeholder . '" onKeyUp="' . $onkeyup . '" ' . $readonly . ' ' . $disabled . ' ' . $required . ' >' . $data . '</textarea>
		';
	}


		//! COLOR
	elseif ( $field_type == 'color' || $field_type == 'colorhex' )
	{
		$formChunk .= '
			<input type="color" class="form-control form-control-color class_' . $field_var . ' input_class ' . $alt_class . '" id="' . $field_var . '" data-info="' . $data . '" name="' . $field_var . '" value="' . $data . '" title="Choose your color"  />
				';
	}
	elseif ( $field_type == 'select_color' )
	{
		$sql = " SELECT *  FROM `colors` WHERE `color_active` = 'yes' ORDER BY `color_group`,`color_name` ";
		$results = mysqli_query($con,$sql);
		$cnt = mysqli_num_rows($results);

		$colors_fields_array = query('getColumns', 'colors', $db_name, '', '', '');

		$x = 1;
		$formChunk .= '
		<div class="d-grid gap-2">
			<a class="btn btn-secondary btn-block" data-bs-toggle="collapse" href="#colorCollapse" role="button" aria-expanded="false" aria-controls="colorCollapse">
				Click to view color list. <i class="fas fa-arrow-alt-circle-down"></i>
			</a>
		</div>

		<div class="collapse mb-4" id="colorCollapse">
			<div class="card card-body">

				<div class="container m-0 p-0">
					<div class="row m-0 p-0">
						';
						$col_header = '<div class="col-sm">';
						$colCnt = '2';
						$formChunk .=   $col_header;
						while( $row = mysqli_fetch_assoc($results) )
						{
							foreach($colors_fields_array as $field_key => $field_value)
							{ $$field_value = $row[$field_value]; }
							$checkme = ''; if ( strtolower($data) == strtolower($color_hex) ) { $checkme = 'checked'; }

							$breakpoint = ceil($cnt / $colCnt) + 1;
							if ( $x == $breakpoint )
							{
								$formChunk .=  ' </div>' . $col_header;
							}
							$color_name = preg_replace('/(?<!\ )[A-Z]/', ' $0', $color_name);
							$color_overlay = $row['color_overlay']; $text_clr = ( $color_overlay == 'Light' ) ? '#fff' : '#000';
							$formChunk .= '
							<div class="form-check" style="background-color:' . $color_hex . ';color:' . $text_clr . ';"><!-- ' . $x . '-->
								<input class="form-check-input input_class" type="radio" name="' . $field_var . '" id="' . $field_var . '_' . $$field_value . '' . $x . '" value="' . $color_hex . '" ' . $checkme . ' />
								<label class="form-check-label" for="' . $field_var . '_' . $$field_value . '' . $x . '"  id="label_' . $field_var . '_' . $$field_value . '' . $x . '">
										' . $color_name . '
								</label>
							</div>
							';
							$x++;
						}
						$formChunk .= '</div>';
						$formChunk .=  '
					</div>
				</div>

			</div>
		</div>
		';
	}

		//! FILE
	if ( $field_type == 'file' )
	{
		$formChunk .= '
			<input type="hidden" class="form-control class_' . $field_var . ' input_class" name="file_' . $field_var . '"  id="file_' . $field_var . '" value="' . $data . '" />

			<div class="custom-file m-0">
				<input type="file" class="form-control class_' . $field_var . ' input_class" id="upload_' . $field_var . '" name="upload_' . $field_var . '" style="padding:3px;"  ' . $required . ' />
				<label class="custom-file-label sr-only class_' . $field_var . ' input_class" for="upload_' . $field_var . '"  id="label_' . $field_var . '">Choose file</label>
			</div>

				';


		if( $addl_var == 'deleteme' && !empty($data) )
		{
			$formChunk .= '
				<div class="form-check">
					<input class="form-check-input class_' . $field_var . ' input_class" type="checkbox" id="delete_' . $field_var . '" name="delete_' . $field_var . '"  />
					<label class="form-check-label class_' . $field_var . '" for="delete_' . $field_var . '"  id="label_' . $field_var . '" > Delete ' . $data . '</label>
				</div>
				';
		}
	}


		//! SELECT MULTIPLE
	if ( $field_type == 'selectmultiple' )
	{
		// if( !is_array($data) ) { $data = explode(',',$data); }
		$formChunk .= '
		<select class="form-select class_' . $field_var . ' input_class multi-select" id="' . $field_var . '" name="' . $field_var . '[]" data-info="' . $data . '" size="2" multiple ' . $required . '  >
			';
		if( is_array($addl_var) && count($addl_var) > 0 )
		{
			foreach( $addl_var AS $key => $value )
			{
				$term = ( !is_array($addl_var) && strpos($addl_var,$value) !== false ) ? $key : $value;  // This will show the "TX" instead of "Texas"
				$selectme = ( strpos(strtolower($data),strtolower($key)) !== false ) ? 'selected' : '';
				$formChunk .= '
				<option value="' . $key .'" ' . $selectme . '> ' . $value .' </option>';
			}
		}
		else
		{
			$formChunk .= '
			<span id="option_values_' . $field_var . '"></span>
			';
		}
		$formChunk .= '
		</select>
		<span style="font-size:9px;">Hold control key to select multiple.</span>';
	}

		//! CHECK
	if ( $field_type == 'check' || $field_type == 'checkbox' )
	{

		$formChunk .= '
		<input type="hidden" id="' . $field_var . '" name="check_' . $field_var . '[]" value="" />
		';

		$x=0;
		$formChunk .=  '<div class="border p-2 border-rounded">
		';

		if( !is_array($addl_var) )
		{
			if( strpos($addl_var,',') !== false )
			{
				$base_array = explode(',',$addl_var);
				foreach( $base_array AS $key => $value )
				{
					$addl_var[$value] = $value;
				}
			}
			else
			{
				$addl_var = array($addl_var => $addl_var);
			}
		}

		if( count($addl_var) > 0 )
		{
			foreach( $addl_var AS $key => $value )
			{
				if( !empty($key) )
				{
					if( is_int($key) )
					{
						$data_array = explode(',',$data);
						$checkme = ( in_array($key,$data_array) !== false ) ? 'checked' : '';
					}
					else
					{
						$checkme = ( strpos($data,$key) !== false ) ? 'checked' : '';
					}
					//If we're working with ID numbers, we need to find it a different way.

					if( strlen($value) > 3 )
					{
						$formChunk .= '
						<div class="form-check form-check-inline">
							<input class="form-check-input input_class" type="checkbox" value="' . $key . '" name="check_' . $field_var . '[]" id="check_' . $field_var . '_' . $x . '" ' . $checkme . '   />
							<label class="form-check-label " for="check_' . $field_var . '_' . $x . '" id="label_check_' . $field_var . '' . $x . '" >
								' . $value . '
							</label>
						</div>

						';
						$x++;
					}
				}
				else
				{
					$formChunk .=  '<em>None</em>';
				}
			}

		} else {
			$formChunk .=  '<em>None</em>';
		}



		$formChunk .=  '</div>';
	}

		//! RADIO
	elseif ( $field_type == 'radio' )
	{
		if( !is_array($addl_var) ) { $addl_var = explode(',',$addl_var); }
		$col_cnt = strlen($placeholder) < 2 ? 12/ceil(count($addl_var)) : $placeholder;
		$formChunk .= '<div class="row m-1 p-1 border rounded">';
		$x = 1;
		foreach( $addl_var AS $key => $value )
		{
			$selectme = ( (strpos(strtolower($data), strtolower($key)) !== false) ) ? 'checked' : '';
			$selectme = ( empty($data) && $x == 1 ) ? 'checked' : $selectme; //If there's no data, click the first option
			$formChunk .= '
				<div class="form-check col-md-' . $col_cnt . '">
					<input class="form-check-input input_class" type="radio" value="' . $key . '" name="' . $field_var . '" id="' . $field_var . '' . $x . '" ' . $selectme . '  >
					<label class="form-check-label" for="' . $field_var . '' . $x . '" >
						' . $value . ' <span id="radio_span_' . $field_var . '' . $x . '"></span>
					</label>
				</div>
			';
			$x++;
		}
		$formChunk .= '</div>';
	}
		//! PASSWORD
	elseif ( $field_type == 'password' )
	{
		if(!empty($data)){$tooltipChunk='<span data-bs-toggle="tooltip" data-bs-placement="left" title="Only enter password if changing existing password.">';}

		$formChunk .= '
						<label for="' . $field_var . '" class="col-md-12 pl-0 form-label ' . $required . ' ">
							' . $field_name . '
								<span class=" text-end" id="pwrd_response" style="font-size:11px;"></span>
						</label>

						<input type="hidden" class="form-control class_' . $field_var . '" name="old_pwrd" value="' . $data . '" />';
		$formChunk .= $tooltipChunk;
		$formChunk .= '
						<input type="' . $field_type . '" class="form-control class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" placeholder="' . $placeholder . '"  maxlength="' . $field_size . '" value="' . $data . '" onKeyUp="validatePassword(\'' . $field_var . '\')" ' . $required . '  />
			</span>
		';
	}
		//! date
	elseif ( $field_type == 'date'  )
	{
			$formChunk .= '
			<input type="' . $field_type . '" class="form-control date_form class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" value="' . $data . '" placeholder="' . $placeholder . '" min="' . $min . '" max="' . $max . '" data-id="' . $field_var . '" ' . $readonly . ' ' . $disabled . ' ' . $required . '  />
							';
	}
		//! datetime
	elseif ( $field_type == 'datetime-local' || $field_type == 'datetime'  )
	{
			$data_repair = date('Y-m-d\TH:i:s', strtotime($data));

			$formChunk .= '
			<input type="' . $field_type . '" class="form-control  class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" value="' . $data_repair . '" placeholder="' . $placeholder . '" min="' . $min . '" max="' . $max . '" data-id="' . $field_var . '" ' . $readonly . ' ' . $disabled . ' ' . $required . '  />
							';
	}
		//! NUMBER
	elseif ( $field_type == 'number' )
	{
			$formChunk .= '
			<input type="' . $field_type . '" .. class="form-control  class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" value="' . $data . '" placeholder="' . $placeholder . '" min="' . $min . '" max="' . $max . '" data-id="' . $field_var . '"  maxlength="' . $field_size . '" ' . $readonly . ' ' . $disabled . ' ' . $required . ' />
							';
	}
		//! TEXT
	elseif (  $field_type == 'time'  )
	{
			$formChunk .= '
			<input type="' . $field_type . '" class="form-control  class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" value="' . $data . '" placeholder="' . $placeholder . '" data-id="' . $field_var . '"  step="' . $field_size . '" min="' . $min . '" max="' . $max . '" data-id="' . $field_var . '" ' . $readonly . ' ' . $disabled . ' ' . $required . ' />
							';
	}
		//! TEXT
	elseif ( $field_type == 'text'  )
	{
			$formChunk .= '
			<input type="' . $field_type . '" class="form-control ' . $type_ahead . ' class_' . $field_var . ' input_class ' . $alt_class . '" name="' . $field_var . '" id="' . $field_var . '" value="' . $data . '" placeholder="' . $placeholder . '" data-id="' . $field_var . '" list="datalist_' . $field_var . '" maxlength="' . $field_size . '" onKeyUp="' . $onkeyup . '" ' . $readonly . ' ' . $disabled . ' ' . $required . '  /><datalist id="datalist_' . $field_var . '"></datalist>
							';
	}

		//! This adds the password show, the field counter or space below the form item.
	$formChunk .= '
	<div class="col-md-12 row p-1 m-0 feedback" id="feedback_' . $field_var . '">';

	if ( $field_type == 'password' )
	{
		$formChunk .= '
			<div class="col-md-12 pl-4">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" id="showPass" onchange="showPassword(\'' . $field_var . '\')"  style="font-size:.7rem;" />
					<label class="form-check-label" for="showPass" style="font-size:.7rem;"> Show password </label>
				</div>
			</div>
			';
	}
	elseif ( ( $field_type == 'text' || $field_type == 'textarea' || $field_type == 'file'  ) && $field_size > 0 && strlen($footie) < 3)
	{
	// ($field_size-strlen($data))
		$formChunk .= '
			<div class="col-md-8 m-0 p-0" id="msg_' . $field_var . '"></div>
			<div class="col-md-4 m-0 p-0 text-end">
				<small class="form-text text-muted"><span id="len_' . $field_var . '">' . strlen($data) . '</span>/' . $field_size . '</small>
			</div>
	';
	}
	elseif ( ( $field_type == 'text' || $field_type == 'textarea' || $field_type == 'file'   ) && $field_size > 0 && strlen($footie) > 3)
	{
	// ($field_size-strlen($data))
		$formChunk .= '
			<div class="col-md-8 m-0 p-0" id="msg_' . $field_var . '">' . $footie . '</div>
			<div class="col-md-4 m-0 p-0 text-end" id="">
				<small class="form-text text-muted"><span id="len_' . $field_var . '">' . strlen($data) . '</span>/' . $field_size . '</small>
			</div>
	';
	}
	elseif ( ( $field_type == 'date' || $field_type !== 'select' || $field_type == 'file'   ) && strlen($footie) > 3 )
	{
		$formChunk .= $footie;
	}
	// elseif (  ( $field_type == 'date' || $field_type !== 'select' || $field_type == 'file'   ) && $field_size < 4 )
	// {
	// 	// ($field_size-strlen($data))
	// 	$formChunk .= '
	// 	<div class="col-md-12 m-0 p-0 text-end ' . $field_size . '">
	// 		<small class="form-text text-muted"><span id="len_' . $field_var . '">z' . strlen($data) . '</span>/' . $field_size . '</small>
	// 	</div>
	// 	';
	// }
	else
	{
		$formChunk .= '
			<small>&nbsp;</small>';
	}


	$formChunk .= '
			</div>';

	$formChunk .= '
	</div>';

	// if( $insert_typeahead == 'yes' )
	// {
	// 	$formChunk .= type_ahead($db_table,$field_var,$form_id,'distinct',$typeahead_size);
	// }

	echo $formChunk;

}
?>
