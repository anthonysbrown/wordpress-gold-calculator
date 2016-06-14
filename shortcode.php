<?php



function display_gold_calculator($atts){
	
	global $wpdb ;
	

$feed= file_get_contents(SP_GOLD_XML_URL);

$thefeed = dlg_gc_get_include_contents($feed);

$gold_arr =  dlg_gold_calculator_xml2array($thefeed);


  $percentage = get_option('dlg_gold_calculator_rate') / 100;	    
		
		//purity
		
		
		
		$strPurity_10K = .417;
        $strPurity_14K = .585;
		$strPurity_16K = .666;
        $strPurity_18K = .750;
        $strPurity_20K = .833;
        $strPurity_22K = .916; 



		//value
		$full_price_gram = $gold_arr['GoldPrice'][get_option('dlg_gold_calculator_currency')] ;
		$full_price_ounce = $gold_arr['GoldPrice'][get_option('dlg_gold_calculator_currency')] * 31;
		$gold_gram = $gold_arr['GoldPrice'][get_option('dlg_gold_calculator_currency')] ;
		$gold_gram = number_format($gold_gram, 2, '.', '') * $percentage;
		
		$gold_ounce = ($full_price_ounce) * $percentage;
		$gold_ounce = number_format($gold_ounce, 2, '.', '');
	
		$pennyweight = $gold_gram /20;
		
		
		
		
		
		//price calulations
		$prices['g'][10] = ($gold_gram * $strPurity_10K) * $percentage;
		$prices['pw'][10] = ($gold_ounce * $strPurity_10K) /20 * $percentage;
		
		$prices['g'][14] = ($gold_gram * $strPurity_14K) * $percentage;
		$prices['pw'][14] = ($gold_ounce* $strPurity_14K) /20 * $percentage;
		
		$prices['g'][16] = ($gold_gram * $strPurity_16K) * $percentage;
		$prices['pw'][16] = ($gold_ounce * $strPurity_16K) /20 * $percentage;
		
		$prices['g'][18] = ($gold_gram * $strPurity_18K) * $percentage;
		$prices['pw'][18] = ($gold_ounce * $strPurity_18K) /20 * $percentage;
		
		$prices['g'][20] = ($gold_gram * $strPurity_20K) * $percentage;
		$prices['pw'][20] = ($gold_ounce * $strPurity_20K) /20 * $percentage;
		
		$prices['g'][22] = ($gold_gram * $strPurity_22K)* $percentage;
		$prices['pw'][22] = ($gold_ounce * $strPurity_22K) /20* $percentage;
		
		$prices['g'][24] = ($gold_gram)* $percentage ;
		$prices['pw'][24] =( $gold_ounce) /20* $percentage;
		
		

	$calculator .= '
<div id="calculator">
<input type="hidden" name="percentage" value="'.$percentage.'" id="percentage" />
<input type="hidden" name="gold_gram" value="'.$gold_gram.'" id="gold_gram" />
<input type="hidden" name="gold_ounce" value="'.$gold_ounce.'" id="gold_price" />
<input type="hidden" name="gold_ounce" value="'.$full_price_ounce.'" id="full_price_ounce" />
<input type="hidden" name="gold_ounce" value="'.$full_price_gram.'" id="full_price_gram" />
<div id="today">&nbsp;</div>
<div id="numbers">';
if (get_option('dlg_gold_calculator_money_position') == 'Before') { $calculator .= get_option('dlg_gold_calculator_money_symbol'); }

$calculator .='<span id="update_price">0.00</span>';
if (get_option('dlg_gold_calculator_money_position') == 'After') { $calculator .= get_option('dlg_gold_calculator_money_symbol'); }


$calculator .='</div>

<div id="options">
Units: <span style="margin-left:30px"><input type="radio" name="weight" class="pennyweights submit-calculator" value="1" > Pennyweights <input   type="radio" name="weight" checked class="grams submit-calculator" value="1"> Grams</span>
</div>

<div id="weights">
<div class="weight"><input type="text"  id="calculate-10" class="gold-input"><span class="amount"  id="total-10k"></span> </div>
<div class="weight"><input type="text"  id="calculate-14" class="gold-input"><span class="amount"  id="total-14k"></span> </div>
<div class="weight"><input type="text"  id="calculate-18" class="gold-input"><span class="amount"  id="total-18k"></span> </div>
<div class="weight"><input type="text"  id="calculate-20" class="gold-input"><span class="amount"  id="total-20k"></span> </div>
<div class="weight"><input type="text"  id="calculate-22" class="gold-input"><span class="amount"  id="total-22k"></span> </div>
<div class="weight"><input type="text"  id="calculate-24" class="gold-input"><span class="amount"  id="total-24k"></span> </div>

</div>
<div id="buttons">
  <a href="javascript:void(0);"><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-gold-calculator/images/calc-clear.jpg" width="61" height="33" border="0" class="clear-calculator"></a> <a href="javascript:void(0);"><img class="submit-calculator"  border="0" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-gold-calculator/images/calc-estimate.jpg" width="92" height="32"></a></div>
</div>';
	
	
	return $calculator;
	
}



	add_shortcode( 'gold-calculator', 'display_gold_calculator' );	
?>