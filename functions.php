<?php



function dlg_gc_format_money($money){
	
	
}


function dlg_gc_get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}
function dlg_gold_calculator_xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
    //  print "'xml_parser_create()' function not found!";
        return array('error' => "XML Parser not found");
    }





    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;


            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
}  
function dlg_gold_admin_page(){
	
		global $wpdb ;
		
		
		//original image

$feed= file_get_contents(SP_GOLD_XML_URL);

$thefeed = dlg_gc_get_include_contents($feed);

$gold_arr =  dlg_gold_calculator_xml2array($thefeed);

		//purity
		
		
		
		$strPurity_10K = .416;
        $strPurity_14K = .583;
		$strPurity_16K = .666;
        $strPurity_18K = .75;
        $strPurity_21K = .875;
        $strPurity_22K = .916; 



		//value
		$gold_gram = $gold_arr['GoldPrice'][get_option('dlg_gold_calculator_currency')];
		
		$update_date = $gold_arr['GoldPrice_attr']['date'];
		if($update_date  == ''){
			
		$html .= '<div style="border:1px solid red;background-color:#efc9c9;padding:5px;margin:5px;">You need to activate this domain to receive updates, please click here: <a href="http://www.dlgresults.com/gold-calculator-subscription/">http://www.dlgresults.com/gold-calculator-subscription/</a> to register your domain.</div>';	
		}
		$html .= '
		
		<h1>DLG Gold Calculator</h1>
		<p>Please define the contact form email address as well as the percentage of the going gold rate the customer will get back</p>
	
    <form action="admin.php?page=gold-calculator" method="post">

  <table class="wp-list-table widefat fixed posts" cellspacing="0">


   <tr>
    <td width="250"><strong>Display rate: </strong><br><em>This is the percentage of the actual rate that you want to display</em> </td>
    <td><input type="text" name="rate" value="'.get_option('dlg_gold_calculator_rate').'"> 
    % value of gold</td>
  </tr>
   <tr>
    <td><strong>Money Symbol:</strong><br><em>The money symbol for your currency</em> </td>
    <td><input type="text" name="dlg_gold_calculator_money_symbol" value="'.get_option('dlg_gold_calculator_money_symbol').'"> 
    </td>
  </tr>
     <tr>
    <td><strong>Money Sign Position: </strong><br><em>Choose weather your money symbol goes before or after the amount</em></td>
    <td>
	<select name="dlg_gold_calculator_money_position">
	<option selected="selected" value="'.get_option('dlg_gold_calculator_money_position').'">'.get_option('dlg_gold_calculator_money_position').'</option>
	<option value="Before">Before</option>
	<option value="After">After</option>
	</select></td>
  </tr>
       <tr>
    <td><strong>Gold Rate Currency: </strong> <br><em>Choose the currency for your rate, this information is pulled from kitco.</em></td>
    <td>
	<select name="dlg_gold_calculator_currency">
	<option selected="selected" value="'.get_option('dlg_gold_calculator_currency').'">'.get_option('dlg_gold_calculator_currency').'</option>
	<option value="ARS">Argentine Peso</option>
<option value="AUD">Australian Dollar</option>
<option value="BRL">Brazilian Real</option>
<option value="CAD">Canadian Dollar</option>
<option value="CHF">Swiss Franc</option>
<option value="CNY">Yuan Renminbi</option>
<option value="COP">Colombian Peso</option>
<option value="EUR">Euro</option>
<option value="GBP">Pound Sterling</option>
<option value="HKD">Hong Kong Dollar</option>
<option value="IDR">Indonesian Rupiah</option>
<option value="INR">Indian Rupee</option>
<option value="JPY">Yen</option>
<option value="KWD">Kuwaiti Dinar</option>
<option value="MXN">Mexican Peso</option>
<option value="MYR">Malaysian Ringgit</option>
<option value="NZD">New Zealand Dollar</option>
<option value="PEN">Peruvian Nuevo Sol</option>
<option value="PHP">Philippine Peso</option>
<option value="RUR">Russian Rouble</option>
<option value="SEK">Swedish Krona</option>
<option value="SGD">Singapore Dollar</option>
<option value="TRY">Turkish Lira</option>
<option value="USD">United States Dollar</option>
<option value="VUV">Vanuatu Vatu</option>
</select> <em><p><strong>Current '.get_option('dlg_gold_calculator_currency').' rate:</strong> '.$gold_gram .' <strong style="padding-left:20px">Last update: </strong>  '.date('F d, Y h:mA', strtotime($update_date)).'</em></p>
	
	
	
	</td>
  </tr>

   <tr>
    <td> </td>
    <td><input type="submit" name="submit-gc-options" value="Save"></td>
  </tr>
</table>

   </form> 
    
';
		
	return $html;
}
?>