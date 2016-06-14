jQuery(function(){ 

	
	//calculate function
	jQuery('.submit-calculator').bind('click', function() {
		
		
		
	
		
		
		
		var gold10k = jQuery('#calculate-10').val();
		var gold14k = jQuery('#calculate-14').val();
		var gold18k = jQuery('#calculate-18').val();
		var gold20k = jQuery('#calculate-20').val();
		var gold22k = jQuery('#calculate-22').val();
		var gold24k = jQuery('#calculate-24').val();
		
		
		if( jQuery('.grams:checked').val()){
			
		var gold_gram = jQuery('#gold_gram').val();
		}else{
		var gold_gram = jQuery('#gold_gram').val() * 1.555;
		}
		
		var gold_ounce = jQuery('#gold_ounce').val();
		
	   
	    var strPurity_10K = .417;
        var strPurity_14K = .585;
        var strPurity_18K = .75;
        var strPurity_20K = .833;
        var strPurity_22K = .916; 
		
		
		if(gold10k != ""){
		var total10k = 	(gold10k * gold_gram) * strPurity_10K ;
		jQuery('#total-10k').text('$' + total10k.toFixed(2));	
		}else{
		total10k =0;
		jQuery('#total-10k').empty();		
		}
		
		if(gold14k != ""){
		var total14k = 	(gold14k * gold_gram) * strPurity_14K;
		jQuery('#total-14k').text('$' + total14k.toFixed(2));	
		}else{
		total14k =0;	
		jQuery('#total-14k').empty();	
		}
		
		if(gold18k != ""){
		var total18k = 	(gold18k * gold_gram) * strPurity_18K ;
		jQuery('#total-18k').text('$' + total18k.toFixed(2));	
		}else{
		total18k =0;
		jQuery('#total-18k').empty();		
		}
		
		if(gold20k != ""){
		var total20k = 	(gold20k * gold_gram) * strPurity_20K ;
		jQuery('#total-20k').text('$' + total20k.toFixed(2));	
		}else{
		total20k =0;	
		jQuery('#total-20k').empty();	
		}
		
		if(gold22k != ""){
		var total22k = 	(gold22k * gold_gram) * strPurity_22K ;
		jQuery('#total-22k').text('$' + total22k.toFixed(2));	
		}else{
		total22k =0;
		jQuery('#total-22k').empty();		
		}	
		
		if(gold24k != ""){
		var total24k = 	gold24k * gold_gram;
		jQuery('#total-24k').text('$' + total24k.toFixed(2));
		
		}else{
		total24k =0;
		jQuery('#total-24k').empty();		
		}
		
		
		total = total10k  + total14k + total18k + total20k + total22k + total24k;		
		jQuery('#update_price').text(total.toFixed(2));
		
	});
	
	
	//clear the calculator
	jQuery('.clear-calculator').bind('click', function() {
		
clearAll();
		
	});	
	

		
			});
			
			
	function clearAll(){
		
				jQuery('#total-10k').empty();
		jQuery('#total-14k').empty();
		jQuery('#total-18k').empty();
		jQuery('#total-20k').empty();
		jQuery('#total-22k').empty();
		jQuery('#total-24k').empty();
		
		jQuery('#calculate-10').val('');
		jQuery('#calculate-14').val('');
		jQuery('#calculate-18').val('');
		jQuery('#calculate-20').val('');
		jQuery('#calculate-22').val('');
		jQuery('#calculate-24').val('');
		
	jQuery('#update_price').text('0.00');
	}